// Offcanvas Perubahan Karyawan (buat + lihat/edit) dan toggle status di tabel.
//
// Markup: resources/views/perubahan-karyawan/*.blade.php (penanda: atribut data-pk-*)
//
// Alur form BUAT:
//   pilih karyawan -> tampil Data Saat Ini -> pilih jenis perubahan (aturan field disabled)
//   -> chained dropdown Departemen > Divisi > Section > Job Position -> dokumen -> ringkasan -> simpan
//
// Alur form LIHAT/EDIT (satu offcanvas yang sama, tanpa halaman detail):
//   klik pensil di tabel -> MODE LIHAT (semua terkunci)
//   klik [Edit]          -> MODE EDIT  (field sesuai aturan terbuka)
//   [Batal] saat edit    -> kembali ke MODE LIHAT (nilai dikembalikan)
//   [Batal] saat lihat   -> tutup offcanvas
//
// Catatan Select2: nilai <select> WAJIB diubah lewat jQuery .val().trigger('change')
// (lihat catatan di offcanvas-edit.js), dan disabled lewat jQuery .prop('disabled').

import $ from "jquery";
import { Offcanvas } from "bootstrap";

// Field Job Level & Cabang bisa dikunci tergantung jenis perubahan.
// Departemen/Divisi/Section/Job Position selalu boleh diubah di semua jenis.
const ATURAN = {
    promosi: { level: true, cabang: true },
    demosi: { level: true, cabang: true },
    rotasi: { level: false, cabang: false },
    mutasi: { level: false, cabang: true },
};

const LABEL_JENIS = {
    promosi: "Promosi",
    demosi: "Demosi",
    rotasi: "Rotasi",
    mutasi: "Mutasi",
};

const HINT_JENIS = {
    promosi: "Promosi: semua data dapat diubah.",
    demosi: "Demosi: semua data dapat diubah.",
    rotasi: "Rotasi: Job Level dan Cabang tidak berubah.",
    mutasi: "Mutasi: Job Level tidak berubah.",
};

const RANTAI = ["departemen", "divisi", "section", "posisi"]; // urutan chained dropdown
const SEMUA = [...RANTAI, "level", "cabang"];

const LABEL_FIELD = {
    departemen: "Departemen",
    divisi: "Divisi",
    section: "Section",
    posisi: "Job Position",
    level: "Job Level",
    cabang: "Cabang",
};

const instances = new WeakMap();

// ---------------------------------------------------------------
// Helper kecil
// ---------------------------------------------------------------
function buat(tag, kelas, teks) {
    const el = document.createElement(tag);
    if (kelas) el.className = kelas;
    if (teks !== undefined) el.textContent = teks;
    return el;
}

function toast(icon, title) {
    window.Swal?.fire({
        toast: true,
        position: "top-end",
        icon,
        title,
        showConfirmButton: false,
        timer: 2600,
        timerProgressBar: true,
    });
}

// ---------------------------------------------------------------
// Satu instance per <form data-pk-form>
// ---------------------------------------------------------------
class FormPerubahan {
    constructor(form) {
        this.form = form;
        this.panel = form.closest(".offcanvas");
        this.isEdit = form.hasAttribute("data-pk-edit-form");
        this.mock = form.dataset.mock === "1";
        this.mode = this.isEdit ? "view" : "create"; // create | view | edit

        this.row = null; // data baris yang sedang dibuka (form lihat/edit)
        this.lama = null; // data saat ini: { field: { id, nama } }
        this.diam = 0; // >0 = perubahan nilai dari kode, jangan jalankan handler "user ganti pilihan"
        this.tiket = {}; // pembatal respons fetch yang sudah usang
        this.tokenBuka = 0;
        this.otomatis = new Set(); // field yang nilainya diisi otomatis karena dikunci (level/cabang)
        this.errorNodes = new Map();

        const q = (sel) => form.querySelector(sel);
        this.karyawan = q("[data-pk-karyawan]");
        this.jenis = q("[data-pk-jenis]");
        this.after = q("[data-pk-after]");
        this.kosong = q("[data-pk-empty]");
        this.hint = q("[data-pk-rule-hint]");
        this.ringkasan = q("[data-pk-summary]");
        this.ringkasanList = q("[data-pk-summary-list]");
        this.nomorSk = q('[name="nomor_sk"]');
        this.tanggal = q('[name="tanggal_efektif"]');
        this.tanggalTrigger = this.tanggal
            ?.closest(".input-date-wrapper")
            ?.querySelector("[data-date-trigger]");
        this.fileInput = q('[name="file_sk"]');

        this.baru = {};
        this.lamaEl = {};
        this.keep = {};
        this.catatan = {};
        SEMUA.forEach((f) => {
            this.baru[f] = q(`[data-pk-baru="${f}"]`);
            this.lamaEl[f] = q(`[data-pk-lama="${f}"]`);
            this.keep[f] = q(`[data-pk-keep="${f}"]`);
            this.catatan[f] = q(`[data-pk-lock-note="${f}"]`);
        });

        this.url = {
            divisi: form.dataset.urlDivisi,
            section: form.dataset.urlSection,
            posisi: form.dataset.urlPosisi,
        };

        // elemen di footer / banner
        this.simpanBtn = this.panel.querySelector(
            `button[type="submit"][form="${form.id}"]`,
        );
        this.batalBtn = this.panel.querySelector(
            '.offcanvas-footer [data-bs-dismiss="offcanvas"]',
        );
        this.editBtn = this.panel.querySelector('[data-pk-action="edit"]');
        this.fileBtn = this.panel.querySelector('[data-pk-action="view-file"]');
        this.judul = this.panel.querySelector(".offcanvas-title");
        this.banner = q("[data-pk-banner]");
        this.fileBox = q("[data-pk-current-file]");
        this.fileNama = q("[data-pk-file-name]");

        this.pasangEvent();
        this.setMode(this.mode); // set kondisi awal tombol footer / banner
    }

    // ------------------------------------------------------------
    // Event
    // ------------------------------------------------------------
    pasangEvent() {
        const $form = $(this.form);

        $(this.karyawan).on("change", () => !this.diam && this.pilihKaryawan());
        $(this.jenis).on("change", () => !this.diam && this.pilihJenis());

        RANTAI.forEach((f, i) => {
            $(this.baru[f]).on(
                "change",
                () => !this.diam && this.rantaiBerubah(i),
            );
        });

        // Ringkasan ikut berubah + error field itu hilang begitu user memperbaikinya
        $form.on("change input date:change", (e) => {
            this.hapusError(e.target);
            if (!this.diam) this.render();
        });

        this.form.addEventListener("submit", (e) => this.submit(e));

        // Form dipakai ulang tiap dibuka -> kembalikan ke kondisi awal waktu ditutup
        this.panel.addEventListener("hidden.bs.offcanvas", () => {
            if (this.isEdit) {
                this.setMode("view");
            } else {
                this.resetBuat();
            }
        });

        if (this.isEdit) {
            this.editBtn?.addEventListener("click", () => this.setMode("edit"));

            // Batal saat MODE EDIT hanya membatalkan perubahan, tidak menutup offcanvas.
            // stopPropagation mencegah handler data-bs-dismiss milik Bootstrap (delegasi di document).
            this.panel.addEventListener("click", (e) => {
                const batal = e.target.closest(
                    '.offcanvas-footer [data-bs-dismiss="offcanvas"]',
                );
                if (!batal || this.mode !== "edit") return;

                e.preventDefault();
                e.stopPropagation();
                this.batalEdit();
            });

            // Lihat Surat SK: kalau file belum ada, kasih tahu user
            this.fileBtn?.addEventListener("click", (e) => {
                const href = this.fileBtn.getAttribute("href");
                if (href && href !== "#") return; // file asli -> buka tab baru seperti biasa

                e.preventDefault();
                window.Swal?.fire({
                    icon: "info",
                    title: "Surat SK belum tersedia",
                    text: "File SK untuk perubahan ini belum dapat dibuka.",
                    confirmButtonText: "OK",
                });
            });
        }
    }

    // ------------------------------------------------------------
    // Set nilai / disabled (aman untuk Select2)
    // ------------------------------------------------------------
    setVal(el, nilai) {
        this.diam++;
        try {
            $(el)
                .val(nilai ?? "")
                .trigger("change");
        } finally {
            this.diam--;
        }
    }

    setDisabled(el, nonaktif) {
        if (!el) return;
        if (el.tagName === "SELECT") {
            $(el).prop("disabled", nonaktif);
        } else {
            el.disabled = nonaktif;
        }
    }

    // Pastikan <option> untuk nilai tersimpan ada (mis. divisi yang sudah nonaktif tidak ikut di daftar aktif)
    pastikanOpsi(select, id, nama) {
        if (id === "" || id === null || id === undefined) return;
        const ada = [...select.options].some(
            (o) => String(o.value) === String(id),
        );
        if (!ada) select.append(new Option(nama || `#${id}`, id));
    }

    // ------------------------------------------------------------
    // Karyawan & jenis perubahan
    // ------------------------------------------------------------
    pilihKaryawan() {
        const opt = this.karyawan.selectedOptions[0];

        if (!opt || !opt.value) {
            this.lama = null;
        } else {
            this.lama = {};
            SEMUA.forEach((f) => {
                this.lama[f] = {
                    id: opt.dataset[`${f}Id`] ?? "",
                    nama: opt.dataset[f] ?? "",
                };
            });
        }

        this.isiLama();
        this.kosongkanBaru();
        this.terapkanAturan();
        this.render();
    }

    pilihJenis() {
        this.terapkanAturan();
        this.render();
    }

    isiLama() {
        SEMUA.forEach((f) => {
            if (this.lamaEl[f])
                this.lamaEl[f].value = this.lama?.[f]?.nama || "";
        });
    }

    kosongkanBaru() {
        this.otomatis.clear();

        // Departemen tidak dikosongkan karena option-nya berasal dari server.
        // Yang di-reset hanya child dari chained dropdown.
        RANTAI.slice(1).forEach((f) => this.kosongkan(f));

        this.setVal(this.baru.departemen, "");

        ["level", "cabang"].forEach((f) => this.setVal(this.baru[f], ""));
    }

    // Job Level & Cabang yang dikunci oleh jenis perubahan diisi dengan data saat ini ("tidak berubah").
    terapkanAturan() {
        const aturan = ATURAN[this.jenis.value];

        ["level", "cabang"].forEach((f) => {
            const dikunci = !!aturan && aturan[f] === false;

            if (dikunci) {
                const lama = this.lama?.[f];
                if (lama && lama.id !== "") {
                    this.pastikanOpsi(this.baru[f], lama.id, lama.nama);
                    this.setVal(this.baru[f], lama.id);
                    this.otomatis.add(f);
                }
            } else if (this.otomatis.has(f)) {
                // sebelumnya terkunci (terisi otomatis), sekarang boleh diubah -> user harus memilih sendiri
                this.setVal(this.baru[f], "");
                this.otomatis.delete(f);
            }
        });
    }

    // ------------------------------------------------------------
    // Chained dropdown: Departemen > Divisi > Section > Job Position
    // ------------------------------------------------------------
    rantaiBerubah(i) {
        for (let j = i + 1; j < RANTAI.length; j++) this.kosongkan(RANTAI[j]);

        const nilai = this.baru[RANTAI[i]].value;
        if (nilai && i < RANTAI.length - 1) {
            this.muat(RANTAI[i + 1], nilai).then(() => this.render());
        }
        this.render();
    }

    ambilTiket(f) {
        this.tiket[f] = (this.tiket[f] || 0) + 1;
        return this.tiket[f];
    }

    kosongkan(f) {
        this.ambilTiket(f); // batalkan fetch yang masih jalan
        const el = this.baru[f];
        el.innerHTML = '<option value=""></option>';
        this.setVal(el, "");
    }

    async muat(f, indukId, pilihId = "", pilihNama = "") {
        const tiket = this.ambilTiket(f);
        let data = [];

        try {
            const res = await fetch(this.url[f].replace("__ID__", indukId), {
                headers: { Accept: "application/json" },
            });
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            data = await res.json();
        } catch (err) {
            console.error(`Gagal memuat pilihan ${LABEL_FIELD[f]}:`, err);
        }

        if (tiket !== this.tiket[f]) return; // respons usang, buang

        const el = this.baru[f];
        el.innerHTML = '<option value=""></option>';
        data.forEach((item) => el.append(new Option(item.nama, item.id)));
        this.pastikanOpsi(el, pilihId, pilihNama);
        this.setVal(el, pilihId);
    }

    // ------------------------------------------------------------
    // Render: status disabled, catatan kunci, visibilitas, ringkasan
    // ------------------------------------------------------------
    render() {
        const mode = this.mode;
        const lihat = mode === "view";
        const adaKaryawan = this.isEdit || !!this.karyawan.value;
        const jenis = this.jenis.value;
        const aturan = ATURAN[jenis];

        this.after.classList.toggle("d-none", !adaKaryawan);
        this.kosong?.classList.toggle("d-none", adaKaryawan);

        this.setDisabled(this.karyawan, this.isEdit); // karyawan tidak bisa diganti di form lihat/edit
        this.setDisabled(this.jenis, lihat || !adaKaryawan);

        SEMUA.forEach((f) => {
            const el = this.baru[f];
            let nonaktif = lihat || !aturan || aturan[f] === false;

            // anak chained dropdown aktif hanya kalau induknya sudah dipilih dan punya pilihan
            const idx = RANTAI.indexOf(f);
            if (!nonaktif && idx > 0) {
                const induk = this.baru[RANTAI[idx - 1]];
                nonaktif = !induk.value || el.options.length <= 1;
            }

            this.setDisabled(el, nonaktif);

            // select disabled tidak ikut terkirim -> kirim lewat hidden input supaya nilainya tidak hilang
            const keep = this.keep[f];
            keep.value = el.value;
            keep.disabled = !(nonaktif && el.value);
        });

        // catatan "tidak berubah pada ..." di bawah Job Level & Cabang
        ["level", "cabang"].forEach((f) => {
            const dikunci = !!aturan && aturan[f] === false;
            const note = this.catatan[f];
            note.classList.toggle("d-none", !dikunci);
            if (dikunci)
                note.querySelector("span").textContent =
                    `Tidak berubah pada ${LABEL_JENIS[jenis]}`;
        });

        this.hint.querySelector("span").textContent =
            HINT_JENIS[jenis] ||
            "Pilih jenis perubahan untuk menentukan data yang dapat diubah.";

        // dokumen
        this.setDisabled(this.nomorSk, lihat);
        this.setDisabled(this.tanggal, lihat);
        this.setDisabled(this.tanggalTrigger, lihat);

        // ringkasan hanya saat mengisi (buat / edit), bukan saat mode lihat
        const tampilRingkasan = adaKaryawan && !lihat;
        this.ringkasan.classList.toggle("d-none", !tampilRingkasan);
        if (tampilRingkasan) this.renderRingkasan();
    }

    namaTerpilih(f) {
        const el = this.baru[f];
        return el.value ? el.selectedOptions[0]?.textContent.trim() || "" : "";
    }

    renderRingkasan() {
        const list = this.ringkasanList;
        list.replaceChildren();

        const baris = (label, isi) => {
            list.append(buat("dt", null, label), isi);
        };
        const dd = (teks, kelas) => {
            const el = buat("dd", kelas);
            if (teks !== undefined) el.textContent = teks;
            return el;
        };

        const opt = this.karyawan.selectedOptions[0];
        baris(
            "Karyawan",
            dd(opt?.value ? opt.textContent.trim().replace(/\s+/g, " ") : "-"),
        );
        baris("Jenis Perubahan", dd(LABEL_JENIS[this.jenis.value] || "-"));

        SEMUA.forEach((f) => {
            const lama = this.lama?.[f]?.nama || "-";
            const baru = this.namaTerpilih(f);
            const berubah =
                baru &&
                String(this.baru[f].value) !== String(this.lama?.[f]?.id ?? "");

            const isi = dd(undefined, "pk-summary__flow");
            isi.append(
                buat("span", "pk-summary__old", lama),
                buat("i", "bi bi-arrow-right"),
            );
            isi.append(
                baru
                    ? buat(
                        "span",
                        berubah
                            ? "pk-summary__new is-changed"
                            : "pk-summary__new",
                        baru,
                    )
                    : buat("span", "pk-summary__empty", "Belum dipilih"),
            );
            baris(LABEL_FIELD[f], isi);
        });

        baris("Tanggal Efektif", dd(this.tanggal.value || "-"));
    }

    // ------------------------------------------------------------
    // Mode lihat / edit (hanya form edit)
    // ------------------------------------------------------------
    setMode(mode) {
        this.mode = mode;
        this.form.dataset.mode = mode;
        if (!this.isEdit) {
            this.render();
            return;
        }

        const edit = mode === "edit";
        this.editBtn?.classList.toggle("d-none", edit);
        this.simpanBtn?.classList.toggle("d-none", !edit);

        const labelSimpan = this.simpanBtn?.querySelector("span");
        if (labelSimpan) labelSimpan.textContent = "Simpan Perubahan";

        if (this.judul)
            this.judul.textContent = edit
                ? "Edit Perubahan Karyawan"
                : "Detail Perubahan Karyawan";

        if (this.banner) {
            this.banner.classList.toggle("pk-banner--edit", edit);
            this.banner.classList.toggle("pk-banner--view", !edit);
            this.banner.querySelector("[data-pk-banner-icon]").className =
                `bi ${edit ? "bi-pencil-square" : "bi-eye"}`;
            this.banner.querySelector("[data-pk-banner-text]").textContent =
                edit
                    ? "Mode edit — ubah data yang diperlukan, lalu klik Simpan Perubahan."
                    : "Mode lihat — data terkunci. Klik Edit untuk mengubah.";
        }

        this.hapusSemuaError();
        this.render();
    }

    batalEdit() {
        this.setMode("view");
        if (this.row) this.isiDariRow(this.row); // kembalikan semua field ke nilai awal
    }

    // Dipanggil saat ikon pensil di tabel diklik
    buka(row, updateUrl) {
        this.row = row;
        if (updateUrl) this.form.action = updateUrl;
        this.setMode("view");
        this.isiDariRow(row);
    }

    async isiDariRow(row) {
        const token = ++this.tokenBuka;

        // reset sisa tampilan sebelumnya
        this.form
            .querySelectorAll("[data-file-remove]")
            .forEach((t) => t.click());
        this.otomatis.clear();

        // Data Saat Ini = snapshot yang tersimpan di baris ini (bukan data karyawan hari ini)
        this.lama = {};
        SEMUA.forEach((f) => {
            this.lama[f] = {
                id: row[`${f}_lama_id`] ?? "",
                nama: row[`${f}_lama`] ?? "",
            };
        });
        this.isiLama();

        this.pastikanOpsi(this.karyawan, row.karyawan_id, row.karyawan_nama);
        this.setVal(this.karyawan, row.karyawan_id);
        this.setVal(this.jenis, row.jenis_perubahan);

        // Job Level & Cabang punya daftar statis
        ["level", "cabang"].forEach((f) => {
            this.pastikanOpsi(
                this.baru[f],
                row[`${f}_baru_id`],
                row[`${f}_baru`],
            );
            this.setVal(this.baru[f], row[`${f}_baru_id`]);
        });

        // Dokumen
        this.nomorSk.value = row.nomor_sk ?? "";
        this.tanggal.value = row.tanggal_efektif ?? "";
        // minta datepicker menyelaraskan tampilan dengan value (hook ada di input-date.js)
        this.form.dispatchEvent(
            new CustomEvent("edit-data:loaded", { detail: row }),
        );
        this.tampilkanFile(row);

        this.terapkanAturan();

        // Chained dropdown: isi berurutan sesuai data tersimpan
        this.pastikanOpsi(
            this.baru.departemen,
            row.departemen_baru_id,
            row.departemen_baru,
        );
        this.setVal(this.baru.departemen, row.departemen_baru_id);
        RANTAI.slice(1).forEach((f) => this.kosongkan(f));
        this.render();

        const langkah = [
            ["divisi", "departemen"],
            ["section", "divisi"],
            ["posisi", "section"],
        ];
        for (const [anak, induk] of langkah) {
            const indukId = row[`${induk}_baru_id`];
            if (!indukId) break;
            await this.muat(
                anak,
                indukId,
                row[`${anak}_baru_id`] ?? "",
                row[`${anak}_baru`] ?? "",
            );
            if (token !== this.tokenBuka) return; // user keburu membuka baris lain
        }

        this.render();
    }

    tampilkanFile(row) {
        const ada = !!row.file_sk_name || !!row.file_sk_url;
        if (this.fileNama) {
            this.fileNama.textContent = ada
                ? row.file_sk_name || "File SK"
                : "Surat SK belum tersedia";
        }
        this.fileBox?.classList.toggle("is-empty", !ada);

        if (this.fileBtn) {
            this.fileBtn.setAttribute("href", row.file_sk_url || "#");
        }
    }

    // ------------------------------------------------------------
    // Reset form buat (waktu offcanvas ditutup)
    // ------------------------------------------------------------
    resetBuat() {
        this.form.reset();
        this.lama = null;
        this.otomatis.clear();
        this.hapusSemuaError();

        this.setVal(this.karyawan, "");
        this.setVal(this.jenis, "");
        this.isiLama();
        RANTAI.forEach((f) => this.kosongkan(f));
        ["level", "cabang"].forEach((f) => this.setVal(this.baru[f], ""));
        this.form
            .querySelectorAll("[data-file-remove]")
            .forEach((t) => t.click());

        this.render();
    }

    // ------------------------------------------------------------
    // Validasi & submit
    // ------------------------------------------------------------
    tandaiError(el, pesan) {
        const select = el.tagName === "SELECT";
        el.classList.add("is-invalid");
        if (select)
            $(el)
                .next(".select2-container")
                .find(".select2-selection")
                .addClass("select2-is-invalid");

        const pesanEl = buat("div", "invalid-feedback d-block", pesan);
        const jangkar =
            el.closest(".input-date-wrapper") ||
            (select ? $(el).next(".select2-container")[0] : null) ||
            el.closest(".file-upload") ||
            el;
        jangkar.insertAdjacentElement("afterend", pesanEl);

        this.errorNodes.set(el, pesanEl);
    }

    hapusError(el) {
        const node = this.errorNodes.get(el);
        if (!node) return;

        node.remove();
        el.classList.remove("is-invalid");
        $(el)
            .next(".select2-container")
            .find(".select2-selection")
            .removeClass("select2-is-invalid");
        this.errorNodes.delete(el);
    }

    hapusSemuaError() {
        [...this.errorNodes.keys()].forEach((el) => this.hapusError(el));
    }

    // Return daftar error: [] kalau valid
    validasi() {
        this.hapusSemuaError();
        const error = [];
        const wajib = (el, pesan) => {
            if (!el.value || !String(el.value).trim()) error.push([el, pesan]);
        };

        if (!this.isEdit) wajib(this.karyawan, "Karyawan wajib dipilih.");
        wajib(this.jenis, "Jenis perubahan wajib dipilih.");

        // Hanya field yang enabled yang wajib diisi. Field disabled memakai nilai saat ini (lewat hidden input).
        SEMUA.forEach((f) => {
            if (!this.baru[f].disabled)
                wajib(this.baru[f], `${LABEL_FIELD[f]} baru wajib dipilih.`);
        });

        wajib(this.nomorSk, "Nomor SK wajib diisi.");
        wajib(this.tanggal, "Tanggal efektif wajib diisi.");

        const adaFileTersimpan =
            this.isEdit && !!(this.row?.file_sk_name || this.row?.file_sk_url);
        if (!this.fileInput.files.length && !adaFileTersimpan) {
            error.push([this.fileInput, "File SK wajib diunggah."]);
        }

        error.forEach(([el, pesan]) => this.tandaiError(el, pesan));
        return error;
    }

    setLoading(aktif) {
        if (!this.simpanBtn) return;
        this.simpanBtn.disabled = aktif;
        this.simpanBtn.classList.toggle("is-loading", aktif);
    }

    submit(e) {
        if (this.isEdit && this.mode !== "edit") {
            e.preventDefault(); // mode lihat tidak boleh submit (mis. tekan Enter)
            return;
        }

        this.render(); // sinkronkan hidden input field disabled sebelum dikirim

        const error = this.validasi();
        console.log("HASIL VALIDASI SUBMIT:", error);
        console.log("NILAI this.mock:", this.mock);
        console.log("TIPE this.mock:", typeof this.mock);
        console.log("HASIL VALIDASI SUBMIT:", error);

        if (error.length) {
            e.preventDefault();
            const pertama = error[0][0];
            const target = pertama.closest(".mb-3") || pertama;
            target.scrollIntoView({ behavior: "smooth", block: "center" });
            return;
        }

        if (this.mock) {
            // TODO BACKEND: hapus blok mock ini kalau route store/update sudah ada;
            // form akan submit biasa (POST / PUT) lalu redirect dengan flash message.
            e.preventDefault();
            this.setLoading(true);
            setTimeout(() => {
                this.setLoading(false);
                Offcanvas.getInstance(this.panel)?.hide();
                toast(
                    "success",
                    this.isEdit
                        ? "Perubahan berhasil disimpan (contoh)"
                        : "Perubahan berhasil dibuat (contoh)",
                );
            }, 600);
            return;
        }

        this.setLoading(true); // submit sungguhan jalan terus
    }
}

// ---------------------------------------------------------------
// Inisialisasi
// ---------------------------------------------------------------
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("[data-pk-form]").forEach((form) => {
        instances.set(form, new FormPerubahan(form));
    });
});

// Ikon pensil di tabel: isi offcanvas lihat/edit dari data baris (data-pk-row)
document.addEventListener("click", (e) => {
    const tombol = e.target.closest("[data-pk-open]");
    if (!tombol) return;

    const form = document.querySelector("#offcanvas-pk-edit-form");
    const inst = form && instances.get(form);
    if (!inst) return;

    let row;
    try {
        row = JSON.parse(tombol.dataset.pkRow);
    } catch (err) {
        console.error("Data baris perubahan karyawan tidak valid:", err);
        return;
    }

    inst.buka(row, tombol.dataset.updateUrl);
});
