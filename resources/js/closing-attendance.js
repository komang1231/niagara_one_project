// Interaksi halaman Rekapan Absensi (closing-attendance).
//
// Markup: resources/views/closing-attendance/{index,detail}.blade.php
//
// 1) Index: form Closing Periode (offcanvas): dropdown bulan dinamis + konfirmasi sebelum submit.
//
// 2) Detail: Export PDF memakai window.print() (client-side, tanpa backend).
//    Saat dicetak hanya <section class="attendance-report"> yang tampil;
//    aturan tampilan cetak ada di resources/css/closing-attendance.css (@media print).

// ---------------------------------------------------------------
// 1) Form Closing Periode
// ---------------------------------------------------------------

import { Offcanvas } from 'bootstrap';

const BUTTONS_POPUP = {
    confirmButton: 'btn btn-success popup-confirm',
    cancelButton: 'btn btn-danger popup-cancel',
};

function labelPilihan(select) {
    return select?.options[select.selectedIndex]?.text.trim() ?? '';
}

// Dropdown Bulan: hanya Januari s/d bulan berjalan (tahun berjalan), semua bulan untuk tahun
// sebelumnya, dan bulan yang sudah di-closing ditampilkan disabled.
function renderBulanClosing() {
    const bulan = document.getElementById('closing_bulan');
    const tahun = document.getElementById('closing_tahun');

    if (!bulan || !tahun) return;

    const namaBulan = JSON.parse(bulan.dataset.bulan || '{}');
    const closed = JSON.parse(bulan.dataset.closed || '{}');
    const tahunSekarang = Number(bulan.dataset.tahunSekarang);
    const bulanSekarang = Number(bulan.dataset.bulanSekarang);

    const tahunDipilih = Number(tahun.value) || tahunSekarang;
    const maks = tahunDipilih < tahunSekarang ? 12 : tahunDipilih === tahunSekarang ? bulanSekarang : 0;
    const sudahClosing = (closed[tahunDipilih] ?? []).map(Number);
    const nilaiLama = bulan.value;

    bulan.innerHTML = '<option value=""></option>';

    Object.entries(namaBulan).forEach(([no, nama]) => {
        if (Number(no) > maks) return;

        const option = document.createElement('option');
        const sudah = sudahClosing.includes(Number(no));

        option.value = no;
        option.textContent = sudah ? `${nama} (sudah di-closing)` : nama;
        option.disabled = sudah;
        option.selected = !sudah && nilaiLama === no;

        bulan.appendChild(option);
    });

    window.jQuery(bulan).trigger('change');
}

// select2 memicu event change lewat jQuery, bukan event DOM native
document.addEventListener('DOMContentLoaded', () => {
    const tahun = document.getElementById('closing_tahun');

    if (tahun && window.jQuery) {
        window.jQuery(tahun).on('change', renderBulanClosing);
    }
});

document.addEventListener('submit', (e) => {
    const form = e.target;

    if (form.id !== 'offcanvas-closing-form') return;

    e.preventDefault();

    const bulan = form.querySelector('[name="bulan_closing"]');
    const tahun = form.querySelector('[name="tahun_closing"]');

    if (!bulan?.value || !tahun?.value) {
        window.Swal.fire({
            title: 'Periode belum lengkap',
            text: 'Pilih bulan dan tahun yang akan di-closing.',
            icon: 'warning',
            confirmButtonText: 'OK',
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-success popup-ok',
            },
        });

        return;
    }

    window.Swal.fire({
        title: 'Closing periode?',
        text: `Periode ${labelPilihan(bulan)} ${labelPilihan(tahun)} akan ditutup.`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, closing',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        buttonsStyling: false,
        customClass: BUTTONS_POPUP,
    }).then((hasil) => {
        if (!hasil.isConfirmed) return;

        Offcanvas.getInstance(form.closest('.offcanvas'))?.hide();

        form.submit();
    });
});
// ---------------------------------------------------------------
// 2) Export PDF di halaman detail (window.print)
// ---------------------------------------------------------------

document.addEventListener('DOMContentLoaded', () => {
    const report = document.querySelector('.attendance-report');
    if (!report) return; // bukan halaman detail

    // Sebelum cetak: sembunyikan semua yang ada di luar laporan (sidebar, topbar, page header),
    // dan reset ancestor laporan supaya margin/padding layout aplikasi tidak ikut tercetak.
    // Dipanggil lewat event "beforeprint" jadi berlaku juga untuk Ctrl+P.
    function siapkanCetak() {
        let node = report;

        while (node && node !== document.documentElement) {
            node.classList.add('ca-print-ancestor');

            Array.from(node.parentElement?.children ?? []).forEach((saudara) => {
                if (saudara !== node && !['SCRIPT', 'STYLE', 'LINK'].includes(saudara.tagName)) {
                    saudara.classList.add('ca-print-hide');
                }
            });

            node = node.parentElement;
        }
    }

    // Kembalikan tampilan normal setelah dialog cetak ditutup
    function bersihkanCetak() {
        document.querySelectorAll('.ca-print-hide').forEach((el) => el.classList.remove('ca-print-hide'));
        document.querySelectorAll('.ca-print-ancestor').forEach((el) => el.classList.remove('ca-print-ancestor'));
    }

    window.addEventListener('beforeprint', siapkanCetak);
    window.addEventListener('afterprint', bersihkanCetak);

    // Tombol Export PDF -> dialog print browser (pilih "Save as PDF")
    document.getElementById('btnExportPdf')?.addEventListener('click', () => window.print());

    // Datang dari aksi Export PDF di halaman index (?print=1)
    if (new URLSearchParams(window.location.search).get('print') === '1') {
        setTimeout(() => window.print(), 400);
    }
});