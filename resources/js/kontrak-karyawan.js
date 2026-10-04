// Interaksi halaman Kontrak Karyawan.
//
// Markup: resources/views/kontrak-karyawan/*.blade.php (penanda: atribut data-kontrak-* / data-perpanjang-form)
//
// 1) Form create & edit: tanggal berakhir TIDAK diisi user. Begitu tanggal mulai dipilih,
//    tanggal berakhir ditampilkan otomatis (disabled). Ini hanya PERKIRAAN tampilan:
//    - lama kontrak dibaca dari atribut data-durasi-bulan di <form> (diatur di Blade, bukan hardcode di sini)
//    - field tanggal berakhir tidak dikirim ke server; nilai final dihitung backend
//    Kalau aturan backend berubah, cukup ubah $durasiBulan di index.blade.php.
//
// 2) Create: pilih karyawan -> tampil Status Kepegawaian karyawan itu (hanya tampilan).
//
// 3) Tombol Perpanjang di tabel: tampil konfirmasi dulu (SweetAlert), baru form disubmit.
//    Tidak ada input tanggal/karyawan. Periode baru dibaca dari atribut data-baru-mulai /
//    data-baru-akhir kalau backend menyediakan; kalau kosong, ditampilkan perkiraan:
//    mulai baru = tanggal berakhir lama + 1 hari, berakhir baru = mulai baru + durasi - 1 hari.

import $ from 'jquery';
import { localeIndonesia, parseTanggal } from './input-date';

// ---------------------------------------------------------------
// Helper tanggal
// ---------------------------------------------------------------

const pad = (n) => String(n).padStart(2, '0');

// Date -> "01 Oktober 2026" (sama dengan format datepicker)
function formatTampil(date) {
    return `${pad(date.getDate())} ${localeIndonesia.months[date.getMonth()]} ${date.getFullYear()}`;
}

// mulai + durasi bulan - 1 hari  (01 Okt 2026 + 12 bln -> 30 Sep 2027)
function hitungAkhir(mulai, durasiBulan) {
    return new Date(mulai.getFullYear(), mulai.getMonth() + durasiBulan, mulai.getDate() - 1);
}

function tambahHari(date, hari) {
    return new Date(date.getFullYear(), date.getMonth(), date.getDate() + hari);
}

function labelDurasi(durasiBulan) {
    return durasiBulan % 12 === 0 ? `${durasiBulan / 12} tahun` : `${durasiBulan} bulan`;
}

function getDurasi(el) {
    const n = parseInt(el?.dataset.durasiBulan, 10);
    return Number.isFinite(n) && n > 0 ? n : 12;
}

function escapeHtml(teks) {
    return String(teks ?? '').replace(/[&<>"']/g, (c) => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;',
    }[c]));
}

// ---------------------------------------------------------------
// 1) Tanggal berakhir otomatis (create + edit)
// ---------------------------------------------------------------

function perbaruiAkhir(form) {
    const mulaiEl = form.querySelector('[name="tanggal_mulai"]');
    const akhirEl = form.querySelector('[data-kontrak-akhir]');
    if (!mulaiEl || !akhirEl) return;

    const mulai = parseTanggal(mulaiEl.value);

    // Form edit: kalau tanggal mulai dikembalikan ke nilai awal, tampilkan lagi tanggal berakhir dari server
    if (mulai && form.dataset.mulaiAwal && form.dataset.akhirAwal && form.dataset.mulaiAwal === mulaiEl.value) {
        akhirEl.value = form.dataset.akhirAwal;
        return;
    }

    akhirEl.value = mulai ? formatTampil(hitungAkhir(mulai, getDurasi(form))) : '';
}

document.addEventListener('date:change', (e) => {
    const form = e.target.closest?.('[data-kontrak-form]');
    if (form && e.target.name === 'tanggal_mulai') perbaruiAkhir(form);
});

// Form edit sudah terisi dari server (offcanvas-edit.js): rapikan format & simpan nilai awal.
// Event "edit-data:loaded" dikirim ke <form> dan tidak bubbling, jadi dipasang langsung di form.
document.querySelectorAll('[data-kontrak-form][data-edit-form]').forEach((form) => {
    form.addEventListener('edit-data:loaded', () => {
        const mulaiEl = form.querySelector('[name="tanggal_mulai"]');
        const akhirEl = form.querySelector('[data-kontrak-akhir]');

        // Tanggal berakhir dari server bisa "2027-09-30" -> tampilkan "30 September 2027"
        const akhir = parseTanggal(akhirEl?.value);
        if (akhirEl && akhir) akhirEl.value = formatTampil(akhir);

        // input-date.js (listener lebih dulu) sudah menyamakan tanggal mulai ke format tampil
        form.dataset.mulaiAwal = mulaiEl?.value ?? '';
        form.dataset.akhirAwal = akhirEl?.value ?? '';
    });
});

// ---------------------------------------------------------------
// 2) Create: status kepegawaian mengikuti karyawan yang dipilih
// ---------------------------------------------------------------

const selectKaryawan = document.querySelector('select[data-kontrak-karyawan]');

if (selectKaryawan) {
    const form = selectKaryawan.closest('form');
    const statusEl = form.querySelector('[data-kontrak-status]');
    let petaStatus = {};

    try {
        petaStatus = JSON.parse(form.querySelector('[data-kontrak-status-map]')?.textContent || '{}');
    } catch (err) {
        console.error('Peta status kepegawaian tidak valid:', err);
    }

    // Select2 memicu event change lewat jQuery, bukan event native
    $(selectKaryawan).on('change', () => {
        if (statusEl) statusEl.value = petaStatus[selectKaryawan.value] ?? '';
    });

    // form.reset() hanya mengosongkan teks, kotak Select2 & tampilan turunan ikut dibersihkan
    form.addEventListener('reset', () => {
        setTimeout(() => {
            $(selectKaryawan).val('').trigger('change');
            form.querySelector('[data-kontrak-akhir]').value = '';
        }, 0);
    });
}

// ---------------------------------------------------------------
// 3) Konfirmasi Perpanjang
// ---------------------------------------------------------------

function periodeBaru(form) {
    const durasi = getDurasi(form);
    const baruMulai = parseTanggal(form.dataset.baruMulai);
    const baruAkhir = parseTanggal(form.dataset.baruAkhir);

    if (baruMulai && baruAkhir) return { mulai: baruMulai, akhir: baruAkhir, durasi };

    // Perkiraan sementara kalau backend belum mengirim periode baru
    const akhirLama = parseTanggal(form.dataset.akhir);
    if (!akhirLama) return null;

    const mulai = tambahHari(akhirLama, 1);
    return { mulai, akhir: hitungAkhir(mulai, durasi), durasi };
}

function bukaKonfirmasi(form) {
    const mulaiLama = parseTanggal(form.dataset.mulai);
    const akhirLama = parseTanggal(form.dataset.akhir);
    const baru = periodeBaru(form);

    const periodeSaatIni = mulaiLama && akhirLama ? `${formatTampil(mulaiLama)} – ${formatTampil(akhirLama)}` : '-';
    const periodeSesudah = baru ? `${formatTampil(baru.mulai)} – ${formatTampil(baru.akhir)}` : '-';

    return window.Swal.fire({
        title: 'Perpanjang Kontrak?',
        icon: 'question',
        html: `
            <p class="kontrak-confirm__lead">
                Kontrak karyawan akan diperpanjang selama ${labelDurasi(getDurasi(form))} dari periode kontrak saat ini.
            </p>
            <dl class="kontrak-confirm">
                <div><dt>Karyawan</dt><dd>${escapeHtml(form.dataset.karyawan)}</dd></div>
                <div><dt>Nomor Kontrak</dt><dd>${escapeHtml(form.dataset.nomor)}</dd></div>
                <div><dt>Periode saat ini</dt><dd>${periodeSaatIni}</dd></div>
                <div class="kontrak-confirm__next"><dt>Setelah diperpanjang</dt><dd>${periodeSesudah}</dd></div>
            </dl>`,
        showCancelButton: true,
        confirmButtonText: 'Perpanjang Kontrak',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        buttonsStyling: false,
        customClass: {
            confirmButton: 'btn btn-success popup-confirm',
            cancelButton: 'btn btn-danger popup-cancel',
        },
    });
}

document.addEventListener('submit', (e) => {
    const form = e.target;
    if (!form.matches?.('[data-perpanjang-form]')) return;

    e.preventDefault();

    bukaKonfirmasi(form).then((hasil) => {
        // form.submit() tidak memicu event submit lagi, jadi tidak muncul konfirmasi dua kali
        if (hasil.isConfirmed) form.submit();
    });
});
