// Air Datepicker

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

// cuma satu loop, jangan diinisialisasi dua kali di elemen yang sama (ini penyebab tampilan dobel)
document.querySelectorAll('[data-air-datepicker]').forEach((element) => {
    const currentYear = new Date().getFullYear();

    const datepicker = new AirDatepicker(element, {
        locale: localeIndonesia,
        dateFormat: 'dd MMMM yyyy',
        startDate: new Date(),

        // biar user cuma bisa pilih tahun berjalan
        minDate: new Date(currentYear, 0, 1),
        maxDate: new Date(currentYear, 11, 31),

        autoClose: true,
    });

    // buka/tutup kalender lewat icon trigger
    const trigger = document.querySelector(`[data-date-trigger="${element.id}"]`);
    if (trigger) {
        trigger.addEventListener('click', () => {
            datepicker.visible ? datepicker.hide() : datepicker.show();
        });
    }
});