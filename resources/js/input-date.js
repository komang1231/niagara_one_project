// Air Datepicker — komponen tanggal yang dipakai SEMUA menu.
//
// Atur lewat atribut di <x-form.input-date>:
//   min="today"      (default) tidak bisa pilih tanggal sebelum hari ini
//   min="none"       boleh pilih tanggal lampau (mis. data historis)
//   min="2026-10-15" batas minimal tanggal tertentu (format Y-m-d)
//   max="2027-12-31" batas maksimal (default: tidak dibatasi -> bisa pilih tahun depan dst.)
//   after="nama_field_lain"  tanggal ini tidak boleh sebelum field lain (satu <form>)
//                            contoh: Tanggal Tutup after="tanggal_buka"

import AirDatepicker from 'air-datepicker';
import 'air-datepicker/air-datepicker.css';

const localeIndonesia = {
    days: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
    daysShort: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
    daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb'],
    months: [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ],
    monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
    today: 'Hari ini',
    clear: 'Hapus',
    dateFormat: 'dd MMMM yyyy',
    timeFormat: 'HH:mm',
    firstDay: 1
};

// ---------------------------------------------------------------
// Helper tanggal
// ---------------------------------------------------------------

// Hari ini jam 00:00 (supaya perbandingan tanggal tidak kena jam)
export function hariIni() {
    const n = new Date();
    return new Date(n.getFullYear(), n.getMonth(), n.getDate());
}

// Terima "2026-10-15" ATAU "15 Oktober 2026" -> Date lokal (null kalau tidak valid).
// Jangan pakai new Date(string): dibaca UTC dan bisa geser sehari.
export function parseTanggal(value) {
    if (!value) return null;
    const teks = String(value).trim();

    let m = teks.match(/^(\d{4})-(\d{2})-(\d{2})/);
    if (m) return new Date(Number(m[1]), Number(m[2]) - 1, Number(m[3]));

    m = teks.match(/^(\d{1,2})\s+([A-Za-z]+)\s+(\d{4})$/);
    if (m) {
        const bulan = localeIndonesia.months.findIndex((b) => b.toLowerCase() === m[2].toLowerCase());
        if (bulan >= 0) return new Date(Number(m[3]), bulan, Number(m[1]));
    }

    return null;
}

// Date -> "YYYY-MM-DD" (dipakai buat bandingkan / key)
export function keISO(date) {
    const p = (n) => String(n).padStart(2, '0');
    return `${date.getFullYear()}-${p(date.getMonth() + 1)}-${p(date.getDate())}`;
}

// ---------------------------------------------------------------
// Batas minimal
// ---------------------------------------------------------------

function batasDasar(el) {
    const min = el.dataset.min ?? 'today';
    if (min === 'none') return null;
    if (min === 'today') return hariIni();
    return parseTanggal(min);
}

// Field pasangan (mis. Tanggal Buka untuk Tanggal Tutup), dicari di <form> yang sama
function cariPasangan(el) {
    const nama = el.dataset.after;
    if (!nama) return null;
    const lingkup = el.closest('form') || document;
    return lingkup.querySelector(`[name="${nama}"][data-air-datepicker]`);
}

// Batas minimal akhir = yang paling besar antara "batas dasar" dan "tanggal pasangan"
function hitungMin(el) {
    const dasar = batasDasar(el);
    const pasangan = cariPasangan(el);
    const tglPasangan = pasangan ? parseTanggal(pasangan.value) : null;

    if (tglPasangan && (!dasar || tglPasangan > dasar)) return tglPasangan;
    return dasar;
}

// Dipanggil tiap pasangan berubah: update batas minimal, kosongkan kalau jadi tidak valid
function perbaruiBatas(el) {
    const dp = el._datepicker;
    if (!dp) return;

    const min = hitungMin(el);
    dp.update({ minDate: min ?? '' });

    const pasangan = cariPasangan(el);
    const tglPasangan = pasangan ? parseTanggal(pasangan.value) : null;
    const dipilih = parseTanggal(el.value);

    if (dipilih && tglPasangan && dipilih < tglPasangan) {
        dp.clear(); // contoh: tanggal tutup lebih awal dari tanggal buka yang baru
    } else if (!dipilih && min) {
        dp.setViewDate(min); // kalender langsung buka di bulan batas minimal
    }
}

// Samakan tampilan kalender dengan isi input (dipakai setelah form edit terisi / di-reset)
function sinkron(el) {
    const dp = el._datepicker;
    if (!dp) return;

    const mentah = el.value;
    const tgl = parseTanggal(mentah);

    if (tgl) {
        dp.selectDate(tgl, { silent: true });
        // tanggal lampau (data lama di form edit) bisa ditolak picker -> tampilkan apa adanya
        if (!el.value) el.value = mentah;
    } else {
        dp.clear({ silent: true });
    }
}

// ---------------------------------------------------------------
// Inisialisasi 1 input (bisa dipanggil ulang untuk input yang dibuat dinamis)
// ---------------------------------------------------------------

export function initDatePicker(element) {
    if (!element) return null;
    if (element._datepicker) return element._datepicker; // jangan dobel (penyebab tampilan dobel)

    const mentah = element.value;
    const awal = parseTanggal(mentah);
    const min = hitungMin(element);
    const max = element.dataset.max ? parseTanggal(element.dataset.max) : null;

    const datepicker = new AirDatepicker(element, {
        locale: localeIndonesia,
        dateFormat: 'dd MMMM yyyy',
        autoClose: true,

        // tidak ada batas tahun maksimal -> tahun depan dst. bisa dipilih
        minDate: min ?? '',
        maxDate: max ?? '',

        startDate: awal || min || new Date(),
        selectedDates: awal ? [awal] : [],

        // kabari field lain (pasangan tanggal, ringkasan form, dll.)
        onSelect: () => {
            element.dispatchEvent(new CustomEvent('date:change', { bubbles: true }));
        },
    });

    element._datepicker = datepicker;

    // nilai lama (old input / data edit) jangan sampai hilang kalau ditolak picker
    if (mentah && !element.value) element.value = mentah;

    // icon kalender: cari di wrapper-nya sendiri (bukan lewat id, karena id bisa kembar
    // antara form create & edit)
    const trigger = element.closest('.input-date-wrapper')?.querySelector('[data-date-trigger]');
    if (trigger) {
        trigger.addEventListener('click', () => {
            datepicker.visible ? datepicker.hide() : datepicker.show();
        });
    }

    const form = element.closest('form');

    // pasangan berubah -> update batas minimal field ini
    if (element.dataset.after) {
        (form || document).addEventListener('date:change', (e) => {
            if (e.target?.name === element.dataset.after) perbaruiBatas(element);
        });
    }

    // Hook form: cukup dipasang sekali per form
    if (form && !form._dateHook) {
        form._dateHook = true;

        // form edit selesai terisi dari server (lihat offcanvas-edit.js)
        form.addEventListener('edit-data:loaded', () => {
            const semua = form.querySelectorAll('[data-air-datepicker]');
            semua.forEach(sinkron);
            semua.forEach((el) => el.dataset.after && perbaruiBatas(el));
        });

        // form.reset() cuma mengosongkan teks, kalender harus ikut dibersihkan
        form.addEventListener('reset', () => {
            setTimeout(() => {
                const semua = form.querySelectorAll('[data-air-datepicker]');
                semua.forEach(sinkron);
                semua.forEach((el) => el.dataset.after && perbaruiBatas(el));
            }, 0);
        });
    }

    return datepicker;
}

export function initDatePickers(root = document) {
    root.querySelectorAll('[data-air-datepicker]').forEach(initDatePicker);
}

// input yang sudah ada di halaman
initDatePickers();

// supaya bisa dipanggil dari script lain tanpa import
window.initDatePickers = initDatePickers;