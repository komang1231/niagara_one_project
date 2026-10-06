import { parseTanggal, keISO } from "../input-date";

document.addEventListener("DOMContentLoaded", function () {
    const root = document.getElementById("permintaan-root");
    if (!root) return;

    // jQuery opsional: kalau ada, dipakai supaya select2 ikut ke-update
    const $ = window.jQuery;

    /* =====================================================================
     * HELPER
     * ===================================================================== */
    function onChange(el, handler) {
        if ($) $(el).on("change", handler);
        else el.addEventListener("change", handler);
    }

    function setValue(el, value) {
        if ($) {
            $(el).val(value).trigger("change");
        } else {
            el.value = value;
            el.dispatchEvent(new Event("change"));
        }
    }

    // Buka offcanvas lewat tombol bayangan (tidak bergantung window.bootstrap)
    function bukaOffcanvas(id) {
        const t = document.createElement("button");
        t.type = "button";
        t.hidden = true;
        t.dataset.bsToggle = "offcanvas";
        t.dataset.bsTarget = "#" + id;
        document.body.appendChild(t);
        t.click();
        t.remove();
    }

    /* =====================================================================
     * 1. TAB: ingat tab terakhir, sinkron ke URL (?tab=), benerin link pagination
     * ===================================================================== */
    const tabButtons = root.querySelectorAll("[data-permintaan-tab]");

    tabButtons.forEach(function (btn) {
        btn.addEventListener("shown.bs.tab", function () {
            const tab = btn.dataset.permintaanTab;
            sessionStorage.setItem("permintaan-tab", tab);

            const url = new URL(window.location.href);
            url.searchParams.set("tab", tab);
            history.replaceState(null, "", url);
        });
    });

    // Pulihkan tab: prioritas ?tab= di URL, lalu tab terakhir (mis. setelah simpan data)
    const tabTersimpan =
        new URLSearchParams(window.location.search).get("tab") ||
        sessionStorage.getItem("permintaan-tab");
    if (tabTersimpan) {
        const btn = root.querySelector(
            '[data-permintaan-tab="' + tabTersimpan + '"]',
        );
        if (btn && !btn.classList.contains("active")) btn.click();
    }

    // Link pagination tiap tab harus membawa ?tab= milik tab-nya sendiri
    root.querySelectorAll(".tab-pane").forEach(function (pane) {
        pane.querySelectorAll(".pagination a[href]").forEach(function (a) {
            const u = new URL(a.href);
            u.searchParams.set("tab", pane.dataset.tab);
            a.href = u.toString();
        });
    });

    /* =====================================================================
     * 2. (EDIT: ambil data & isi form sekarang ditangani offcanvas-edit.js
     *     global. Di sini cukup mendengarkan event 'edit-data:loaded'
     *     di section chained & cuti di bawah.)
     * ===================================================================== */

    /* =====================================================================
     * 3. MODAL BATALKAN: isi action & label dari tombol yang diklik
     * ===================================================================== */
    const modalBatalkan = document.getElementById("modal-batalkan-permintaan");
    if (modalBatalkan) {
        modalBatalkan.addEventListener("show.bs.modal", function (e) {
            const btn = e.relatedTarget;
            if (!btn) return;
            modalBatalkan.querySelector("form").action = btn.dataset.deleteUrl;
            modalBatalkan.querySelector("[data-batalkan-label]").textContent =
                btn.dataset.deleteLabel || "";
        });
    }

    /* =====================================================================
     * 4. RESET FORM CREATE saat offcanvas ditutup
     * ===================================================================== */
    document.querySelectorAll(".offcanvas").forEach(function (canvas) {
        const form = canvas.querySelector("form[data-reset-on-close]");
        if (!form) return;

        canvas.addEventListener("hidden.bs.offcanvas", function () {
            form.reset();
            form.querySelectorAll(
                'input.flatpickr-input, input[type="date"]',
            ).forEach(function (i) {
                if (i._flatpickr) i._flatpickr.clear();
            });
            form.querySelectorAll("select").forEach(function (s) {
                setValue(s, "");
            });
            form.dispatchEvent(new Event("form:reset"));
        });
    });

    /* =====================================================================
     * 5. CHAINED DROPDOWN (Departemen -> Divisi -> Section -> Job Position)
     *    Dipakai form create & edit lewat atribut data-chained.
     * ===================================================================== */
    document.querySelectorAll("form[data-chained]").forEach(function (form) {
        const pilih = (nama) => form.querySelector('[name="' + nama + '"]');
        const departemen = pilih("departemen_id");
        const divisi = pilih("divisi_id");
        const section = pilih("section_id");
        const jobPosition = pilih("job_position_id");

        // Form ini tidak punya dropdown bertingkat (mis. salah pasang data-chained) -> lewati,
        // supaya tidak error "null" dan tidak menghentikan script di bawahnya.
        if (!departemen || !divisi || !section || !jobPosition) return;

        const url = {
            divisi: form.dataset.urlDivisi,
            section: form.dataset.urlSection,
            position: form.dataset.urlPosition,
        };

        const sedangPrefill = () => form.dataset.prefilling === "1";

        // "Tiket" per select: respons fetch yang sudah usang dibuang
        const tickets = {};
        const newTicket = (s) => (tickets[s.id] = (tickets[s.id] || 0) + 1);

        function resetSelect(select) {
            newTicket(select); // batalkan fetch yang masih jalan
            select.innerHTML = '<option value="">-- Pilih --</option>';
            select.disabled = true;
            setValue(select, "");
        }

        function fillSelect(select, data, selectedId = "") {
            select.innerHTML = '<option value="">-- Pilih --</option>';
            data.forEach(function (item) {
                const option = document.createElement("option");
                option.value = item.id;
                option.textContent = item.nama;
                select.appendChild(option);
            });
            select.disabled = data.length === 0;
            setValue(select, selectedId ?? "");
        }

        function fetchChildren(urlTemplate, parentId, target, selectedId = "") {
            const ticket = newTicket(target);

            return fetch(urlTemplate.replace("__ID__", parentId), {
                headers: { Accept: "application/json" },
            })
                .then(function (res) {
                    if (!res.ok) throw new Error("HTTP " + res.status);
                    return res.json();
                })
                .then(function (data) {
                    if (ticket !== tickets[target.id]) return; // respons usang
                    fillSelect(target, data, selectedId);
                });
        }

        // Data edit sudah terisi -> isi divisi/section/job position berurutan (await!)
        form.addEventListener("edit-data:loaded", async function (event) {
            const d = event.detail || {};
            form.dataset.prefilling = "1";

            try {
                resetSelect(divisi);
                resetSelect(section);
                resetSelect(jobPosition);

                if (d.departemen_id)
                    await fetchChildren(
                        url.divisi,
                        d.departemen_id,
                        divisi,
                        d.divisi_id,
                    );
                if (d.divisi_id)
                    await fetchChildren(
                        url.section,
                        d.divisi_id,
                        section,
                        d.section_id,
                    );
                if (d.section_id)
                    await fetchChildren(
                        url.position,
                        d.section_id,
                        jobPosition,
                        d.job_position_id,
                    );
            } catch (err) {
                console.error("Gagal mengisi chained dropdown:", err);
            } finally {
                delete form.dataset.prefilling;
            }
        });

        // User ganti Departemen
        onChange(departemen, function () {
            if (sedangPrefill()) return;
            resetSelect(divisi);
            resetSelect(section);
            resetSelect(jobPosition);
            if (!this.value) return;
            fetchChildren(url.divisi, this.value, divisi).catch((e) =>
                console.error("Gagal ambil divisi:", e),
            );
        });

        // User ganti Divisi
        onChange(divisi, function () {
            if (sedangPrefill()) return;
            resetSelect(section);
            resetSelect(jobPosition);
            if (!this.value) return;
            fetchChildren(url.section, this.value, section).catch((e) =>
                console.error("Gagal ambil section:", e),
            );
        });

        // User ganti Section
        onChange(section, function () {
            if (sedangPrefill()) return;
            resetSelect(jobPosition);
            if (!this.value) return;
            fetchChildren(url.position, this.value, jobPosition).catch((e) =>
                console.error("Gagal ambil job position:", e),
            );
        });
    });

    /* =====================================================================
     * 6. CUTI: rincian tanggal (Sehari penuh / Setengah hari per tanggal)
     *    + validasi tanggal selesai tidak boleh sebelum tanggal mulai
     * ===================================================================== */
    document.querySelectorAll("form[data-cuti-form]").forEach(function (form) {
        const mulai = form.querySelector('[name="tanggal_mulai"]');
        const selesai = form.querySelector('[name="tanggal_selesai"]');
        const box = form.querySelector("[data-cuti-details]");
        const ringkasan = form.querySelector("[data-cuti-summary]");
        const bulk = form.querySelector("[data-cuti-bulk]");
        const error = form.querySelector("[data-cuti-error]");
        const lampiranInfo = form.querySelector("[data-lampiran-info]");
        const detailWrapper = form.querySelector("[data-cuti-detail-wrapper]");
        const cutiSelect = form.querySelector('[name="cuti_id"]');

        const NAMA_HARI = ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"];
        const MAKS_HARI = 60; // batas aman supaya form tidak kebanjiran baris
        const PESAN_URUTAN =
            "Tanggal selesai tidak boleh lebih awal dari tanggal mulai.";
        const pad = (n) => String(n).padStart(2, "0");
        // Input tanggal tampil "03 Oktober 2026" (Air Datepicker), bukan "2026-10-03",
        // jadi WAJIB dibaca lewat parseTanggal (mendukung dua format itu).
        const keString = keISO;
        const keDate = parseTanggal;

        function isCutiTahunan() {
            const selectedOption =
                cutiSelect?.options[cutiSelect.selectedIndex];
            const namaCuti =
                selectedOption?.textContent.trim().toLowerCase() || "";

            return namaCuti === "cuti tahunan";
        }

        function updateCutiDetailVisibility() {
            const tahunan = isCutiTahunan();

            if (detailWrapper) {
                detailWrapper.hidden = tahunan;
            }

            if (tahunan) {
                box.innerHTML = "";
                ringkasan.textContent = "";
                bulk.hidden = true;
            }
        }
        cutiSelect?.addEventListener("change", updateCutiDetailVisibility);

        function urutanSalah() {
            const awal = keDate(mulai.value);
            const akhir = keDate(selesai.value);
            return !!(awal && akhir && akhir < awal);
        }

        function tampilError(teks) {
            if (!error) return;
            error.textContent = teks || "";
            error.hidden = !teks;
        }

        // Pesan kosong / error di dalam kotak rincian (daftar tanggal disembunyikan)
        function pesan(teks, isError = false) {
            box.innerHTML =
                '<div class="cuti-detail-empty' +
                (isError ? " is-error" : "") +
                '">' +
                '<i class="bi ' +
                (isError ? "bi-exclamation-circle" : "bi-calendar-range") +
                '"></i>' +
                "<span>" +
                teks +
                "</span></div>";
            ringkasan.textContent = "";
            bulk.hidden = true;
        }

        const baris = () => box.querySelectorAll(".cuti-detail-row");
        const setengahDipilih = (row) =>
            row.querySelector('input[type="radio"][value="1"]').checked;

        // Baca pilihan user, supaya tidak hilang saat daftar dirender ulang
        function statusSekarang() {
            const map = {};
            baris().forEach(function (row) {
                map[row.dataset.tanggal] = setengahDipilih(row);
            });
            return map;
        }

        // 3,5 -> "3,5" ; 4 -> "4"
        const angka = (n) => String(n).replace(".", ",");

        function perbaruiRingkasan() {
            let penuh = 0;
            let setengah = 0;
            baris().forEach(function (row) {
                const half = setengahDipilih(row);
                row.classList.toggle("is-half", half);
                if (half) setengah++;
                else penuh++;
            });

            if (penuh + setengah === 0) {
                ringkasan.textContent = "";
                return;
            }

            const total = penuh + setengah * 0.5;
            const bagian = [];
            if (penuh) bagian.push(penuh + " sehari penuh");
            if (setengah) bagian.push(setengah + " setengah hari");
            ringkasan.innerHTML =
                "Total cuti: <strong>" +
                angka(total) +
                " hari</strong> (" +
                bagian.join(" + ") +
                ")";
        }

        // existing = { 'YYYY-MM-DD': true/false }
        function render(existing = {}) {
            if (isCutiTahunan()) {
                updateCutiDetailVisibility();
                return;
            }

            const a = mulai.value;
            const b = selesai.value;

            tampilError("");

            if (!a || !b)
                return pesan(
                    "Pilih tanggal mulai dan selesai untuk menampilkan daftar tanggal.",
                );

            const awal = keDate(a);
            const akhir = keDate(b);

            if (!awal || !akhir)
                return pesan(
                    "Format tanggal tidak dikenali, pilih ulang tanggalnya.",
                    true,
                );

            if (akhir < awal) {
                tampilError(PESAN_URUTAN);
                return pesan(PESAN_URUTAN, true);
            }

            const jumlah = Math.round((akhir - awal) / 86400000) + 1;
            if (jumlah > MAKS_HARI)
                return pesan(
                    "Rentang cuti maksimal " + MAKS_HARI + " hari.",
                    true,
                );

            let html = "";
            for (let i = 0; i < jumlah; i++) {
                const d = new Date(awal);
                d.setDate(awal.getDate() + i);
                const tgl = keString(d);
                const half = !!existing[tgl];
                const nama = "details[" + i + "][setengah_hari]";
                const hari = NAMA_HARI[d.getDay()];
                const label =
                    pad(d.getDate()) +
                    "/" +
                    pad(d.getMonth() + 1) +
                    "/" +
                    d.getFullYear();

                // Radio 0/1 menggantikan checkbox + hidden lama: nilainya selalu terkirim (0 atau 1)
                html +=
                    '<div class="cuti-detail-row' +
                    (half ? " is-half" : "") +
                    '" data-tanggal="' +
                    tgl +
                    '">' +
                    '<div class="cuti-detail-date">' +
                    '<span class="cuti-detail-day">' +
                    hari +
                    "</span>" +
                    '<span class="cuti-detail-full">' +
                    label +
                    "</span>" +
                    "</div>" +
                    '<input type="hidden" name="details[' +
                    i +
                    '][tanggal]" value="' +
                    tgl +
                    '">' +
                    '<div class="cuti-segmented" role="radiogroup" aria-label="Lama cuti ' +
                    hari +
                    " " +
                    label +
                    '">' +
                    '<label class="cuti-segmented__opt">' +
                    '<input type="radio" name="' +
                    nama +
                    '" value="0"' +
                    (half ? "" : " checked") +
                    ">" +
                    "<span>Sehari penuh</span></label>" +
                    '<label class="cuti-segmented__opt">' +
                    '<input type="radio" name="' +
                    nama +
                    '" value="1"' +
                    (half ? " checked" : "") +
                    ">" +
                    "<span>Setengah hari</span></label>" +
                    "</div></div>";
            }
            box.innerHTML = html;
            bulk.hidden = false;
            perbaruiRingkasan();
        }

        // Ganti pilihan di salah satu baris -> hitung ulang ringkasan
        box.addEventListener("change", perbaruiRingkasan);

        // Tombol "Semua sehari penuh" / "Semua setengah hari"
        bulk.addEventListener("click", function (e) {
            const btn = e.target.closest("[data-bulk]");
            if (!btn) return;
            baris().forEach(function (row) {
                row.querySelector(
                    'input[type="radio"][value="' + btn.dataset.bulk + '"]',
                ).checked = true;
            });
            perbaruiRingkasan();
        });

        // Tanggal berubah. Kalau selesai jadi lebih awal dari mulai -> kosongkan selesai.
        // (Air Datepicker sudah memblokir lewat atribut after="tanggal_mulai"; ini lapis kedua
        //  supaya aman juga untuk data edit / nilai yang diisi lewat script.)
        let pesanTahan = false; // true = selesai baru saja dikosongkan karena lebih awal dari mulai

        function tanggalBerubah() {
            if (urutanSalah()) {
                pesanTahan = true;
                if (selesai._datepicker)
                    selesai._datepicker.clear({ silent: true });
                selesai.value = "";
                render();
                tampilError(PESAN_URUTAN);
                return;
            }

            // Air Datepicker ikut memicu event "change" saat selesai dikosongkan; jangan sampai
            // pesan error tadi langsung terhapus. Pesan hilang setelah user memilih tanggal selesai baru.
            if (pesanTahan && !selesai.value) {
                render();
                tampilError(PESAN_URUTAN);
                return;
            }

            pesanTahan = false;
            render(statusSekarang());
        }

        // Air Datepicker tidak memicu event "change", tapi "date:change" (lihat input-date.js)
        [mulai, selesai].forEach(function (el) {
            el.addEventListener("date:change", tanggalBerubah);
            onChange(el, tanggalBerubah);
        });

        // Cegah submit kalau urutan tanggal salah / rincian belum ada
        form.addEventListener("submit", function (e) {
            if (urutanSalah()) {
                e.preventDefault();
                tampilError(PESAN_URUTAN);
                return;
            }
            if (!isCutiTahunan() && !baris().length) {
                e.preventDefault();
                tampilError(
                    "Pilih tanggal mulai dan selesai dulu supaya rincian tanggal cuti terisi.",
                );
            }
        });

        // Create: ditutup lewat data-reset-on-close (event form:reset).
        // Edit: form.reset() dipanggil offcanvas-edit.js tiap kali tombol edit diklik.
        form.addEventListener("form:reset", () => {
            pesanTahan = false;
            render();
        });
        form.addEventListener("reset", function () {
            pesanTahan = false;
            setTimeout(() => render(), 0);
        });

        // Mode edit: render daftar dari data lama
        form.addEventListener("edit-data:loaded", function (event) {
            pesanTahan = false;
            const d = event.detail || {};
            const map = {};
            (d.details || []).forEach(function (row) {
                map[String(row.tanggal).slice(0, 10)] = !!Number(
                    row.setengah_hari,
                );
            });
            render(map);

            if (lampiranInfo) {
                lampiranInfo.textContent = d.lampiran
                    ? "Lampiran saat ini: " +
                      String(d.lampiran).split("/").pop() +
                      " (upload file baru untuk mengganti)"
                    : "";
            }
        });

        render();
    });

    /* =====================================================================
     * 6b. LEMBUR: jam dipilih lewat time picker (lihat time-picker.js)
     *     Jam selesai <= jam mulai = lembur lewat tengah malam (selesai besok).
     *     Yang dicek di sini cuma durasinya (4 - 10 jam), sama dengan PermintaanLemburRequest.
     * ===================================================================== */
    document
        .querySelectorAll("form[data-lembur-form]")
        .forEach(function (form) {
            const mulai = form.querySelector('[name="jam_mulai"]');
            const selesai = form.querySelector('[name="jam_selesai"]');
            const info = form.querySelector("[data-lembur-info]");
            if (!mulai || !selesai) return;

            const MIN_JAM = 4;
            const MAKS_JAM = 10;
            const teksAwal = info ? info.textContent : "";

            const formatJam = (v) => /^([01]\d|2[0-3]):[0-5]\d$/.test(v || "");
            const keMenit = (v) => {
                const p = v.split(":");
                return Number(p[0]) * 60 + Number(p[1]);
            };

            function tampil(teks, status) {
                if (!info) return;
                info.textContent = teks;
                info.classList.toggle("is-ok", status === "ok");
                info.classList.toggle("is-error", status === "error");
            }

            function namaDurasi(menit) {
                const j = Math.floor(menit / 60);
                const m = menit % 60;
                return (
                    (j ? j + " jam" : "") +
                    (j && m ? " " : "") +
                    (m ? m + " menit" : "")
                );
            }

            // Return true kalau durasi valid. Kosong / belum lengkap -> false (biar "required" yang bicara).
            function periksa() {
                if (!formatJam(mulai.value) || !formatJam(selesai.value)) {
                    tampil(teksAwal, "");
                    return false;
                }

                const a = keMenit(mulai.value);
                let b = keMenit(selesai.value);
                const besok = b <= a;
                if (besok) b += 24 * 60; // lintas hari

                const d = b - a;
                const ket = besok ? " (selesai di hari berikutnya)" : "";

                if (d < MIN_JAM * 60 || d > MAKS_JAM * 60) {
                    tampil(
                        "Durasi " +
                            namaDurasi(d) +
                            ket +
                            ". Durasi lembur harus antara " +
                            MIN_JAM +
                            " sampai " +
                            MAKS_JAM +
                            " jam.",
                        "error",
                    );
                    return false;
                }

                tampil("Durasi lembur: " + namaDurasi(d) + ket, "ok");
                return true;
            }

            // 'change' = pilih dari dropdown / selesai mengetik. 'blur' jalan SETELAH time-picker.js
            // membersihkan isian yang formatnya salah.
            [mulai, selesai].forEach(function (el) {
                el.addEventListener("change", periksa);
                el.addEventListener("blur", periksa);
            });

            form.addEventListener("submit", function (e) {
                if (!formatJam(mulai.value) || !formatJam(selesai.value))
                    return; // "required" bawaan browser yang jalan
                if (!periksa()) {
                    e.preventDefault();
                    selesai.focus();
                }
            });

            // Create: form.reset() saat offcanvas ditutup. Edit: reset tiap klik tombol edit.
            form.addEventListener("reset", function () {
                setTimeout(periksa, 0);
            });

            // Mode edit: jam sudah diisi dari server
            form.addEventListener("edit-data:loaded", periksa);

            periksa();
        });

    /* =====================================================================
     * 7. Buka lagi offcanvas create kalau validasi gagal
     * ===================================================================== */
    if (root.dataset.reopen) bukaOffcanvas(root.dataset.reopen);
});
