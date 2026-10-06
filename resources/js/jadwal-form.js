/**
 * Jadwal Karyawan — form Tambah Jadwal (tiap baris = karyawan + shift + tanggal)
 * dan legend shift yang bisa di-scroll horizontal.
 *
 * Kontrak data yang dikirim ke server:
 *   jadwal[0][karyawan_id][]  = [1, 2, ...]   (boleh banyak karyawan per baris)
 *   jadwal[0][shift_id]       = 3
 *   jadwal[0][tanggal]        = "15 Oktober 2026"  (atau Y-m-d)
 *   jadwal[1][karyawan_id][] ...
 */

import '../css/jadwal-form.css';
import { initDatePicker, parseTanggal, keISO } from './input-date';

const HARI = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
const BULAN = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

const labelTanggal = (d) => `${HARI[d.getDay()]}, ${d.getDate()} ${BULAN[d.getMonth()]}`;

// bikin elemen kecil dengan textContent (aman dari XSS, nama shift itu input user)
function buat(tag, className, text) {
    const node = document.createElement(tag);
    if (className) node.className = className;
    if (text !== undefined) node.textContent = text;
    return node;
}

function pakaiWarna(node, warna) {
    node.style.setProperty('--c-bg', `var(--shift-${warna}-bg)`);
    node.style.setProperty('--c-text', `var(--shift-${warna}-text)`);
    node.style.setProperty('--c-border', `var(--shift-${warna}-border)`);
}

function badgeLintas() {
    const badge = buat('span', 'jadwal-lintas-badge');
    badge.append(buat('i', 'bi bi-moon-stars-fill'), document.createTextNode(' Lintas hari'));
    return badge;
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-jadwal-form]').forEach(initFormJadwal);
    document.querySelectorAll('[data-legend-scroll]').forEach(initLegendScroll);
});

// ---------------------------------------------------------------
// Select2 shift: tiap opsi tampil dengan titik warna + jam + penanda lintas hari
// ---------------------------------------------------------------

export function formatShift(state) {
    if (!state.id) return state.text; // placeholder
    const d = state.element?.dataset;
    if (!d || !d.nama) return state.text;

    const wrap = buat('span', 'jadwal-opt');
    pakaiWarna(wrap, d.warna);
    wrap.append(buat('span', 'jadwal-opt__dot'), buat('span', 'jadwal-opt__name', d.nama), buat('span', 'jadwal-opt__time', `${d.masuk} – ${d.pulang}`));

    if (d.lintas === '1') {
        const moon = buat('span', 'jadwal-opt__moon');
        moon.append(buat('i', 'bi bi-moon-stars-fill'), document.createTextNode(' +1'));
        wrap.append(moon);
    }

    return window.jQuery(wrap);
}

export function initSelectShift(select) {
    const $ = window.jQuery;
    const $select = $(select);
    const $offcanvas = $select.closest('.offcanvas');

    $select.select2({
        width: '100%',
        placeholder: select.dataset.placeholder || 'Pilih shift',
        allowClear: true,
        minimumResultsForSearch: 6, // search muncul kalau shift-nya banyak
        dropdownParent: $offcanvas.length ? $offcanvas : $(document.body),
        templateResult: formatShift,
        templateSelection: formatShift,
        language: { noResults: () => 'Tidak ada shift aktif' },
    });
}

// ---------------------------------------------------------------
// Select2 karyawan (multiple) + opsi "Pilih semua karyawan"
// ---------------------------------------------------------------

const SEMUA = '__all__'; // nilai opsi "Pilih semua" (tidak pernah ikut terkirim ke server)

const daftarKaryawan = (select) => Array.from(select.options).map((o) => o.value).filter((v) => v && v !== SEMUA);
const terpilihKaryawan = (select) => (window.jQuery(select).val() || []).filter((v) => v !== SEMUA);

// Tampilan opsi di dropdown: "Pilih semua" dibuat beda dari opsi karyawan biasa
function formatKaryawanOpsi(state) {
    if (state.id !== SEMUA) return state.text;

    const select = state.element?.parentElement;
    const total = select ? daftarKaryawan(select).length : 0;
    const penuh = select && total > 0 && terpilihKaryawan(select).length === total;

    const wrap = buat('span', 'jadwal-opt-all');
    wrap.append(buat('i', 'bi bi-check2-all'), buat('span', null, penuh ? 'Batalkan semua' : 'Pilih semua karyawan'), buat('span', 'jadwal-opt-all__count', `${total} orang`));

    return window.jQuery(wrap);
}

// Tampilan di kotak (tertutup): 1 ringkasan saja, tag lain disembunyikan lewat CSS.
function formatKaryawanTerpilih(state) {
    if (!state.id) return state.text; // placeholder

    const select = state.element?.parentElement;
    const nama = String(state.text).trim();
    if (!select) return nama;

    const terpilih = terpilihKaryawan(select);
    const total = daftarKaryawan(select).length;

    if (terpilih[0] !== state.id) return nama; // bukan tag pertama -> disembunyikan CSS
    if (terpilih.length > 1 && terpilih.length === total) return `Semua karyawan (${total})`;
    if (terpilih.length > 1) return `${nama} +${terpilih.length - 1} lainnya`;

    return nama;
}

function initSelectKaryawan(select) {
    const $ = window.jQuery;
    const $select = $(select);
    const $offcanvas = $select.closest('.offcanvas');

    // opsi "Pilih semua" selalu paling atas
    if (!select.querySelector(`option[value="${SEMUA}"]`)) {
        select.insertAdjacentHTML('afterbegin', `<option value="${SEMUA}">Pilih semua karyawan</option>`);
    }

    $select.select2({
        width: '100%',
        placeholder: select.dataset.placeholder || 'Pilih karyawan',
        allowClear: true,
        closeOnSelect: false, // bisa pilih banyak karyawan tanpa dropdown menutup terus
        dropdownParent: $offcanvas.length ? $offcanvas : $(document.body),
        templateResult: formatKaryawanOpsi,
        templateSelection: formatKaryawanTerpilih,
        language: { noResults: () => 'Tidak ada hasil' },
    });

    // Klik "Pilih semua": semua terpilih. Klik lagi saat sudah semua: dikosongkan.
    $select.on('select2:selecting', (e) => {
        if (e.params.args.data.id !== SEMUA) return;

        e.preventDefault(); // opsi ini cuma tombol, tidak ikut jadi nilai
        const semua = daftarKaryawan(select);
        const penuh = semua.length > 0 && terpilihKaryawan(select).length === semua.length;

        $select.val(penuh ? [] : semua).trigger('change');
        $select.select2('close');
    });

    // Tooltip: daftar nama lengkap (karena kotaknya cuma menampilkan ringkasan)
    $select.on('change', () => {
        const nama = Array.from(select.selectedOptions)
            .filter((o) => o.value !== SEMUA)
            .map((o) => o.textContent.trim());
        $select.next('.select2-container').find('.select2-selection').attr('title', nama.join(', '));
    });
}

// ---------------------------------------------------------------
// Form
// ---------------------------------------------------------------

function initFormJadwal(form) {
    const $ = window.jQuery;
    const rows = form.querySelector('[data-jadwal-rows]');
    const tpl = form.querySelector('template[data-jadwal-template]');
    // tombol "Tambah Shift" ada 2: kartu placeholder (di dalam form) dan tombol di footer offcanvas (di luar form)
    const offcanvas = form.closest('.offcanvas') || form;
    const tombolTambah = Array.from(offcanvas.querySelectorAll('[data-add-row]'));
    const placeholder = rows.querySelector('[data-add-card]');
    const summary = form.querySelector('[data-jadwal-summary]');

    if (!rows || !tpl || !tombolTambah.length) return;

    const MAX = Number(rows.dataset.max || 31);
    let nextIndex = Number(rows.dataset.nextIndex || 0);

    const semuaBaris = () => Array.from(rows.querySelectorAll('[data-jadwal-row]'));

    // ---------- error helper ----------
    function tampilError(container, field, pesan) {
        const box = container.querySelector(`[data-error="${field}"]`);
        if (!box) return;
        box.textContent = pesan;
        box.classList.toggle('d-block', Boolean(pesan));
    }

    // ---------- info shift LINTAS HARI (jam shift sudah tampil di dropdown) ----------
    // Shift biasa: tidak ada info tambahan (hemat tempat). Lintas hari: 1 baris tipis
    // yang bilang kapan shift-nya selesai.
    function perbaruiInfo(row) {
        const select = row.querySelector('.jadwal-shift-select');
        const input = row.querySelector('[data-air-datepicker]');
        const box = row.querySelector('[data-row-info]');
        const opt = select.selectedOptions[0];

        box.replaceChildren();

        if (!opt || !opt.value || opt.dataset.lintas !== '1') {
            box.hidden = true;
            return;
        }

        const { warna, pulang } = opt.dataset;
        const tgl = parseTanggal(input.value);

        pakaiWarna(box, warna);

        let teks;
        if (tgl) {
            const esok = new Date(tgl.getFullYear(), tgl.getMonth(), tgl.getDate() + 1);
            teks = `Selesai ${labelTanggal(esok)} pukul ${pulang}`;
        } else {
            teks = `Selesai di hari berikutnya pukul ${pulang}`;
        }

        box.append(badgeLintas(), buat('span', null, teks));
        box.hidden = false;
    }

    // ---------- karyawan terpilih di 1 baris ----------
    const karyawanBaris = (row) => terpilihKaryawan(row.querySelector('.jadwal-karyawan-select'));

    // ---------- karyawan yang sama di tanggal yang sama (beda baris) ----------
    const PESAN_KEMBAR = 'Ada karyawan yang sudah dipakai di tanggal ini pada baris lain.';

    function cekKembar() {
        const peta = new Map(); // "tanggal|karyawan" -> baris-baris yang memakainya

        semuaBaris().forEach((row) => {
            row.classList.remove('is-duplicate');

            const tgl = parseTanggal(row.querySelector('[data-air-datepicker]').value);
            if (!tgl) return;

            karyawanBaris(row).forEach((id) => {
                const key = `${keISO(tgl)}|${id}`;
                peta.set(key, [...(peta.get(key) || []), row]);
            });
        });

        let adaKembar = false;
        peta.forEach((list) => {
            if (list.length < 2) return;
            adaKembar = true;
            list.forEach((row) => {
                row.classList.add('is-duplicate');
                tampilError(row, 'tanggal', PESAN_KEMBAR);
            });
        });

        // bersihkan pesan kembar di baris yang sudah aman
        semuaBaris().forEach((row) => {
            if (!row.classList.contains('is-duplicate')) {
                const box = row.querySelector('[data-error="tanggal"]');
                if (box?.textContent === PESAN_KEMBAR) tampilError(row, 'tanggal', '');
            }
        });

        return adaKembar;
    }

    // ---------- ringkasan + nomor baris ----------
    function perbaruiRingkasan() {
        const list = semuaBaris();

        // baris yang sudah lengkap (karyawan + shift + tanggal) -> dihitung ke ringkasan
        const lengkap = list.filter((row) => karyawanBaris(row).length && row.querySelector('.jadwal-shift-select').value && row.querySelector('[data-air-datepicker]').value);
        const jumlahEntri = lengkap.reduce((total, row) => total + karyawanBaris(row).length, 0);

        list.forEach((row, i) => {
            row.querySelector('[data-row-number]').textContent = i + 1;
            row.querySelector('[data-remove-row]').hidden = list.length === 1; // minimal 1 baris
        });

        // sudah mentok maksimal: tombol footer mati, tombol placeholder disembunyikan
        const penuh = list.length >= MAX;
        tombolTambah.forEach((btn) => (btn.disabled = penuh));
        if (placeholder) placeholder.hidden = penuh;

        if (jumlahEntri) {
            summary.textContent = `${lengkap.length} baris jadwal · ${jumlahEntri} entri akan disimpan`;
            summary.hidden = false;
        } else {
            summary.hidden = true;
        }
    }

    // ---------- 1 baris ----------
    function initBaris(row) {
        const select = row.querySelector('.jadwal-shift-select');
        const selectKaryawan = row.querySelector('.jadwal-karyawan-select');
        const input = row.querySelector('[data-air-datepicker]');

        initSelectKaryawan(selectKaryawan);
        initSelectShift(select);
        initDatePicker(input);

        $(selectKaryawan).on('change', () => {
            tampilError(row, 'karyawan', '');
            cekKembar();
            perbaruiRingkasan();
        });

        $(select).on('change', () => {
            tampilError(row, 'shift', '');
            perbaruiInfo(row);
            perbaruiRingkasan();
        });

        input.addEventListener('date:change', () => {
            tampilError(row, 'tanggal', '');
            perbaruiInfo(row);
            cekKembar();
            perbaruiRingkasan();
        });

        row.querySelector('[data-remove-row]').addEventListener('click', () => hapusBaris(row));

        perbaruiInfo(row);
    }

    function tambahBaris() {
        if (semuaBaris().length >= MAX) return;

        const html = tpl.innerHTML.replaceAll('__INDEX__', String(nextIndex++));
        // kartu baru selalu masuk SEBELUM kartu placeholder
        if (placeholder) placeholder.insertAdjacentHTML('beforebegin', html);
        else rows.insertAdjacentHTML('beforeend', html);

        const row = semuaBaris().at(-1);
        initBaris(row);
        perbaruiRingkasan();
        row.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
    }

    function hapusBaris(row) {
        if (semuaBaris().length === 1) return;

        const select = row.querySelector('.jadwal-shift-select');
        const selectKaryawan = row.querySelector('.jadwal-karyawan-select');
        const input = row.querySelector('[data-air-datepicker]');

        $(select).select2('destroy');
        $(selectKaryawan).select2('destroy');
        input._datepicker?.destroy();
        row.remove();

        cekKembar();
        perbaruiRingkasan();
    }

    // ---------- validasi sebelum submit ----------
    form.addEventListener('submit', (e) => {
        let valid = true;

        semuaBaris().forEach((row) => {
            if (!karyawanBaris(row).length) {
                tampilError(row, 'karyawan', 'Pilih minimal 1 karyawan.');
                valid = false;
            }
            if (!row.querySelector('.jadwal-shift-select').value) {
                tampilError(row, 'shift', 'Shift wajib dipilih.');
                valid = false;
            }
            if (!row.querySelector('[data-air-datepicker]').value) {
                tampilError(row, 'tanggal', 'Tanggal wajib diisi.');
                valid = false;
            }
        });

        if (cekKembar()) valid = false;

        if (!valid) {
            e.preventDefault();
            const pertama = form.querySelector('.invalid-feedback.d-block, .is-duplicate');
            pertama?.scrollIntoView({ block: 'center', behavior: 'smooth' });
        }
    });

    tombolTambah.forEach((btn) => btn.addEventListener('click', tambahBaris));

    // ---------- start ----------
    semuaBaris().forEach(initBaris);
    cekKembar();
    perbaruiRingkasan();
}

// ---------------------------------------------------------------
// Legend shift: kalau jenis shift banyak -> scroll horizontal
// ---------------------------------------------------------------

function initLegendScroll(scroll) {
    const wrap = scroll.closest('.jadwal-legend__items');
    const prev = wrap?.querySelector('[data-legend-prev]');
    const next = wrap?.querySelector('[data-legend-next]');

    function perbarui() {
        const sisa = scroll.scrollWidth - scroll.clientWidth;
        const meluap = sisa > 2;

        scroll.classList.toggle('has-more-start', meluap && scroll.scrollLeft > 2);
        scroll.classList.toggle('has-more-end', meluap && scroll.scrollLeft < sisa - 2);

        if (prev && next) {
            prev.hidden = next.hidden = !meluap;
            prev.disabled = scroll.scrollLeft <= 2;
            next.disabled = scroll.scrollLeft >= sisa - 2;
        }
    }

    const geser = (arah) => scroll.scrollBy({ left: arah * Math.max(180, scroll.clientWidth * 0.6), behavior: 'smooth' });
    prev?.addEventListener('click', () => geser(-1));
    next?.addEventListener('click', () => geser(1));

    scroll.addEventListener('scroll', perbarui, { passive: true });
    new ResizeObserver(perbarui).observe(scroll);
    perbarui();
}