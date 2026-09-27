/**
 * resources/js/currency-input.js
 * ---------------------------------------------------------------
 * Reusable currency input (IDR/Rp). Dipakai di form Lowongan
 * (min_gaji, max_gaji) dan Karyawan (gaji).
 *
 * Pola sama kayak rich-text-editor.js: 1 hidden input nyimpen angka
 * mentah yang beneran dikirim ke server, 1 input teks buat user yang
 * otomatis diformat titik ribuan (1.500.000) tiap kali ngetik.
 */

function formatRibuan(digitsOnly) {
    if (!digitsOnly) return '';
    return digitsOnly.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function initCurrencyInputs(scope = document) {
    scope.querySelectorAll('[data-currency-group]').forEach((group) => {
        if (group.dataset.currencyInit) return; // cegah double-init kalau offcanvas dibuka berkali-kali
        group.dataset.currencyInit = '1';

        const display = group.querySelector('[data-currency-display]');
        const hidden = group.querySelector('[data-currency-input]');

        display.addEventListener('input', () => {
            const digitsOnly = display.value.replace(/\D/g, '');
            display.value = formatRibuan(digitsOnly);
            hidden.value = digitsOnly;
        });
    });
}

// Dipanggil dari offcanvas-edit.js pas data hasil fetch dimasukkan ke form edit.
function setCurrencyValue(name, rawValue) {
    const group = document.querySelector(`[data-currency-group="${name}"]`);
    if (!group) return;

    const display = group.querySelector('[data-currency-display]');
    const hidden = group.querySelector('[data-currency-input]');
    const digitsOnly = String(rawValue ?? '').replace(/\D/g, '');

    hidden.value = digitsOnly;
    display.value = formatRibuan(digitsOnly);
}

document.addEventListener('DOMContentLoaded', () => initCurrencyInputs());
document.addEventListener('shown.bs.offcanvas', (e) => initCurrencyInputs(e.target));

window.initCurrencyInputs = initCurrencyInputs;
window.setCurrencyValue = setCurrencyValue;