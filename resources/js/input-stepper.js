// Stepper untuk input angka (jumlah karyawan, dsb)
// Pakai event delegation di document: tombol +/- tetap jalan walau form
// berada di offcanvas / dibuat belakangan, dan tidak bergantung urutan load script.

function nilaiAman(input, atribut, bawaan) {
    const n = parseInt(input[atribut], 10);
    return Number.isNaN(n) ? bawaan : n;
}

function perbaruiTombol(wrapper) {
    const input = wrapper.querySelector('.input-stepper-field');
    if (!input) return;

    const min = nilaiAman(input, 'min', -Infinity);
    const max = nilaiAman(input, 'max', Infinity);
    const sekarang = parseInt(input.value, 10);
    const nilai = Number.isNaN(sekarang) ? min : sekarang;

    const minus = wrapper.querySelector('.stepper-minus');
    const plus = wrapper.querySelector('.stepper-plus');
    if (minus) minus.disabled = nilai <= min;
    if (plus) plus.disabled = nilai >= max;
}

// Paksa nilai masuk rentang [min, max]. Kosong / bukan angka -> kembali ke min.
// Dipanggil saat keluar dari input (blur), change, dan sebelum form submit.
function rapikan(input) {
    const min = nilaiAman(input, 'min', -Infinity);
    const max = nilaiAman(input, 'max', Infinity);
    const n = parseInt(input.value, 10);

    let hasil;
    if (Number.isNaN(n)) hasil = Number.isFinite(min) ? min : 0;
    else hasil = Math.min(max, Math.max(min, n));

    input.value = hasil;
}

document.addEventListener('click', (e) => {
    const btn = e.target.closest('.stepper-btn');
    if (!btn) return;

    const wrapper = btn.closest('.input-stepper');
    const input = wrapper?.querySelector('.input-stepper-field');
    if (!input || btn.disabled) return;

    const stepValue = parseInt(btn.dataset.step, 10) || 0;
    const min = nilaiAman(input, 'min', -Infinity);
    const max = nilaiAman(input, 'max', Infinity);
    const current = parseInt(input.value, 10);
    const dasar = Number.isNaN(current) ? (Number.isFinite(min) ? min : 0) : current;

    // batasin biar gak lewat min/max
    input.value = Math.min(max, Math.max(min, dasar + stepValue));

    // trigger event biar validasi/listener lain yang nempel di input tetep jalan
    input.dispatchEvent(new Event('input', { bubbles: true }));
});

// ---------------------------------------------------------------
// Ketik manual
// ---------------------------------------------------------------

// Stepper ini cuma untuk bilangan bulat: blok karakter yang tidak masuk akal
// (type="number" mengizinkan "-", "+", "e", "." walau tidak berguna di sini).
document.addEventListener('keydown', (e) => {
    const input = e.target.closest?.('.input-stepper-field');
    if (!input) return;
    if (e.ctrlKey || e.metaKey || e.altKey) return; // Ctrl+V, Ctrl+A, dst. tetap boleh

    const min = nilaiAman(input, 'min', -Infinity);
    const dilarang = ['e', 'E', '+', '.', ','];
    if (min >= 0) dilarang.push('-'); // min >= 0 -> angka minus tidak mungkin valid

    if (dilarang.includes(e.key)) e.preventDefault();

    // Enter: rapikan dulu sebelum form dikirim
    if (e.key === 'Enter') rapikan(input);
});

// Setiap ketikan / paste / klik tombol: tangkap nilai yang pasti tidak valid
document.addEventListener('input', (e) => {
    const input = e.target.closest?.('.input-stepper-field');
    const wrapper = e.target.closest?.('.input-stepper');

    if (input) {
        const min = nilaiAman(input, 'min', -Infinity);
        const max = nilaiAman(input, 'max', Infinity);
        const n = parseInt(input.value, 10);

        if (!Number.isNaN(n)) {
            // 0 / minus tidak mungkin jadi valid kalau diteruskan ketik (min > 0),
            // dan minus tidak valid kalau min >= 0 -> langsung kembalikan ke min.
            // (Nilai 1 digit di bawah min, mis. "1" saat min 5, TIDAK disentuh karena
            //  user mungkin masih mengetik "15"; itu dirapikan saat blur.)
            if ((n <= 0 && min > 0) || (n < 0 && min >= 0)) input.value = min;
            // lewat max: tambah digit apa pun tidak akan memperbaikinya
            else if (n > max) input.value = max;
            // "007" -> "7"
            else if (/^0\d/.test(input.value)) input.value = String(n);
        }
    }

    if (wrapper) perbaruiTombol(wrapper);
});

// Keluar dari input / nilai berubah -> pastikan masuk rentang min..max
document.addEventListener('focusout', (e) => {
    const input = e.target.closest?.('.input-stepper-field');
    if (!input) return;
    rapikan(input);
    perbaruiTombol(input.closest('.input-stepper'));
});
document.addEventListener('change', (e) => {
    const input = e.target.closest?.('.input-stepper-field');
    if (input) rapikan(input);

    const wrapper = e.target.closest?.('.input-stepper');
    if (wrapper) perbaruiTombol(wrapper);
});

// Jaga-jaga terakhir: apa pun yang terjadi, form tidak boleh terkirim dengan stepper di luar rentang
document.addEventListener('submit', (e) => {
    e.target.querySelectorAll?.('.input-stepper-field').forEach((input) => {
        rapikan(input);
        perbaruiTombol(input.closest('.input-stepper'));
    });
}, true);

// Form diisi JS (edit) / form di-reset -> status tombol ikut update
document.addEventListener('edit-data:loaded', (e) => {
    e.target.querySelectorAll?.('.input-stepper').forEach(perbaruiTombol);
});
document.addEventListener('reset', (e) => {
    setTimeout(() => e.target.querySelectorAll?.('.input-stepper').forEach(perbaruiTombol), 0);
});

// cegah angka berubah gak sengaja pas user scroll halaman sambil fokus di input
document.addEventListener('wheel', (e) => {
    const input = e.target.closest?.('.input-stepper-field');
    if (input && document.activeElement === input) e.preventDefault();
}, { passive: false });

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.input-stepper').forEach(perbaruiTombol);
});