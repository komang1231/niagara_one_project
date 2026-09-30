/**
 * WAJIB pakai jQuery .val().trigger('change') buat field <select>
 * yang di-select2-in, BUKAN field.value = ... biasa. Alasannya:
 * Select2 render kotak tertutupnya sendiri dan cuma refresh pas
 * ada event 'change' resmi. Set .value native doang bikin value
 * ASLI benar tapi KOTAK TERTUTUPNYA masih nampilin cache lama
 * (baru kelihatan bener kalau dropdown-nya dibuka manuall).
 * 
 */

const TRUTHY = ["1", "true", "aktif", "active", "yes", "ya"];

document.addEventListener("click", (e) => {
    const btn = e.target.closest("[data-edit-url]");
    if (!btn) return;

    const offcanvas = document.querySelector(btn.dataset.bsTarget);
    const form = offcanvas?.querySelector("[data-edit-form]");
    if (!form) return;

    form.reset();

    // Select2 juga wajib direset visualnya, bukan cuma native select,
    // supaya kalau user klik edit baris LAIN, gak kebawa opsi baris
    // sebelumnya (soalnya form/select ini dipakai ulang, bukan dibuat baru).
    if (window.jQuery) {
        window.jQuery(form).find("select.select2").val("").trigger("change");
    }

    if (btn.dataset.updateUrl) {
        form.action = btn.dataset.updateUrl;
    }

    fetch(btn.dataset.editUrl, { headers: { Accept: "application/json" } })
        .then((res) => {
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            return res.json();
        })
        .then((data) => {
            fillForm(form, data);
            // Dipakai modul yang punya chained dropdown (Lowongan, Karyawan, Permintaan)
            // buat isi ulang divisi/section/job-position setelah data utama masuk.
            form.dispatchEvent(
                new CustomEvent("edit-data:loaded", { detail: data }),
            );
        })
        .catch((err) =>
            console.error("Gagal mengambil data untuk form edit:", err),
        );
});

function fillForm(form, data) {
    Object.entries(data).forEach(([key, value]) => {
        form.querySelectorAll(`[name="${key}"]`).forEach((field) => {
            // File input tidak boleh di-set value-nya (melempar error, dan
            // event edit-data:loaded jadi tidak pernah terkirim).
            if (field.type === "file") return;

            if (field.type === "hidden") {
                if (field.hasAttribute("data-rich-text-input")) {
                    window.setRichTextContent?.(key, value, form);
                }
                if (field.hasAttribute("data-currency-input")) {
                    window.setCurrencyValue?.(key, value, form);
                }
                return;
            }

            // Flatpickr: pakai setDate supaya tampilan ikut berubah
            if (field._flatpickr) {
                field._flatpickr.setDate(value ?? "", false);
                return;
            }

            if (field.type === "checkbox") {
                field.checked = TRUTHY.includes(String(value).toLowerCase());
            } else if (field.type === "radio") {
                field.checked = field.value === String(value);
            } else if (
                field.tagName === "SELECT" &&
                field.classList.contains("select2")
            ) {
                // INI FIX UTAMANYA — tanpa baris ini, kotak tertutup
                // Select2 gak bakal keupdate walau value asli udah benar.
                if (window.jQuery) {
                    window
                        .jQuery(field)
                        .val(value ?? "")
                        .trigger("change");
                } else {
                    field.value = value ?? "";
                }
            } else {
                field.value = value ?? "";
            }
        });
    });
}