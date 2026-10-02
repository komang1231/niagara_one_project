/**
 * Jadwal Karyawan — form Tambah Jadwal (banyak baris shift + tanggal)
 * dan legend shift yang bisa di-scroll horizontal.
 *
 * Kontrak data yang dikirim ke server:
 *   karyawan_id[]            = [1, 2, ...]
 *   jadwal[0][shift_id]      = 3
 *   jadwal[0][tanggal]       = "15 Oktober 2026"  (atau Y-m-d, sama seperti sebelumnya)
 *   jadwal[1][shift_id] ...
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

function formatShift(state) {
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

function initSelectShift(select) {
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
    const karyawan = form.querySelector('#jadwal_karyawan_id');

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

    // ---------- info shift terpilih (jam + lintas hari + tanggal selesai) ----------
    function perbaruiInfo(row) {
        const select = row.querySelector('.jadwal-shift-select');
        const input = row.querySelector('[data-air-datepicker]');
        const box = row.querySelector('[data-row-info]');
        const opt = select.selectedOptions[0];

        box.replaceChildren();
        box.classList.remove('is-lintas');

        if (!opt || !opt.value) {
            box.hidden = true;
            return;
        }

        const { warna, masuk, pulang, lintas } = opt.dataset;
        const isLintas = lintas === '1';
        const tgl = parseTanggal(input.value);

        pakaiWarna(box, warna);
        box.classList.toggle('is-lintas', isLintas);

        const baris1 = buat('div', 'jadwal-info__main');
        baris1.append(buat('strong', null, `${masuk} – ${pulang}`));
        if (isLintas) baris1.append(badgeLintas());

        let teks2;
        if (isLintas) {
            if (tgl) {
                const esok = new Date(tgl.getFullYear(), tgl.getMonth(), tgl.getDate() + 1);
                teks2 = `Mulai ${labelTanggal(tgl)} ${masuk}, selesai ${labelTanggal(esok)} ${pulang} (hari berikutnya)`;
            } else {
                teks2 = `Selesai di hari berikutnya pukul ${pulang}`;
            }
        } else {
            teks2 = tgl ? `Mulai & selesai ${labelTanggal(tgl)}` : 'Selesai di hari yang sama';
        }

        box.append(baris1, buat('div', 'jadwal-info__sub', teks2));
        box.hidden = false;
    }

    // ---------- tanggal kembar antar baris ----------
    function cekKembar() {
        const peta = new Map();

        semuaBaris().forEach((row) => {
            const tgl = parseTanggal(row.querySelector('[data-air-datepicker]').value);
            row.classList.remove('is-duplicate');
            if (!tgl) return;
            const key = keISO(tgl);
            peta.set(key, [...(peta.get(key) || []), row]);
        });

        let adaKembar = false;
        peta.forEach((list) => {
            if (list.length < 2) return;
            adaKembar = true;
            list.forEach((row) => {
                row.classList.add('is-duplicate');
                tampilError(row, 'tanggal', 'Tanggal ini sudah dipakai di baris lain.');
            });
        });

        // bersihkan pesan kembar di baris yang sudah aman
        semuaBaris().forEach((row) => {
            if (!row.classList.contains('is-duplicate')) {
                const box = row.querySelector('[data-error="tanggal"]');
                if (box?.textContent === 'Tanggal ini sudah dipakai di baris lain.') tampilError(row, 'tanggal', '');
            }
        });

        return adaKembar;
    }

    // ---------- ringkasan + nomor baris ----------
    function perbaruiRingkasan() {
        const list = semuaBaris();
        const jumlahKaryawan = $(karyawan).val()?.length || 0;
        const jumlahJadwal = list.filter((row) => row.querySelector('.jadwal-shift-select').value && row.querySelector('[data-air-datepicker]').value).length;

        list.forEach((row, i) => {
            row.querySelector('[data-row-number]').textContent = i + 1;
            row.querySelector('[data-remove-row]').hidden = list.length === 1; // minimal 1 baris
        });

        // sudah mentok maksimal: tombol footer mati, kartu placeholder disembunyikan
        const penuh = list.length >= MAX;
        tombolTambah.forEach((btn) => (btn.disabled = penuh));
        if (placeholder) placeholder.hidden = penuh;

        if (jumlahKaryawan && jumlahJadwal) {
            summary.textContent = `${jumlahKaryawan} karyawan × ${jumlahJadwal} jadwal = ${jumlahKaryawan * jumlahJadwal} entri akan disimpan`;
            summary.hidden = false;
        } else {
            summary.hidden = true;
        }
    }

    // ---------- 1 baris ----------
    function initBaris(row) {
        const select = row.querySelector('.jadwal-shift-select');
        const input = row.querySelector('[data-air-datepicker]');

        initSelectShift(select);
        initDatePicker(input);

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
        const input = row.querySelector('[data-air-datepicker]');

        $(select).select2('destroy');
        input._datepicker?.destroy();
        row.remove();

        cekKembar();
        perbaruiRingkasan();
    }

    // ---------- validasi sebelum submit ----------
    form.addEventListener('submit', (e) => {
        let valid = true;

        if (!($(karyawan).val()?.length)) {
            tampilError(form, 'karyawan', 'Pilih minimal 1 karyawan.');
            valid = false;
        }

        semuaBaris().forEach((row) => {
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

    $(karyawan).on('change', () => {
        tampilError(form, 'karyawan', '');
        perbaruiRingkasan();
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