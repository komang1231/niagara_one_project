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
    if (!digitsOnly) return "";
    return digitsOnly.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function initCurrencyInputs(scope = document) {
    scope.querySelectorAll("[data-currency-group]").forEach((group) => {
        if (group.dataset.currencyInit) return; // cegah double-init kalau offcanvas dibuka berkali-kali
        group.dataset.currencyInit = "1";

        const display = group.querySelector("[data-currency-display]");
        const hidden = group.querySelector("[data-currency-input]");

        display.addEventListener("input", () => {
            const digitsOnly = display.value.replace(/\D/g, "");
            display.value = formatRibuan(digitsOnly);
            hidden.value = digitsOnly;
        });
    });
}

// Dipanggil dari offcanvas-edit.js pas data hasil fetch dimasukkan ke form edit.
// scope = form yang lagi diisi, biar gak nabrak field kembar di form create
function setCurrencyValue(name, rawValue, scope = document) {
    const group = scope.querySelector(`[data-currency-group="${name}"]`);
    if (!group) return;

    const display = group.querySelector("[data-currency-display]");
    const hidden = group.querySelector("[data-currency-input]");

    // Buang desimal dulu ("5000000.0000" -> "5000000"), baru ambil digit.
    // Kalau langsung replace(/\D/g, '') titiknya kebuang & angkanya jadi 10.000x lipat.
    const digitsOnly = String(rawValue ?? "")
        .split(".")[0]
        .replace(/\D/g, "");

    hidden.value = digitsOnly;
    display.value = formatRibuan(digitsOnly);
}

document.addEventListener("DOMContentLoaded", () => initCurrencyInputs());
document.addEventListener("shown.bs.offcanvas", (e) =>
    initCurrencyInputs(e.target),
);

window.initCurrencyInputs = initCurrencyInputs;
window.setCurrencyValue = setCurrencyValue;
