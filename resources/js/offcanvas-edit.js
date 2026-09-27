document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-edit-url]');
    if (!btn) return;

    const offcanvas = document.querySelector(btn.dataset.bsTarget);
    const form = offcanvas?.querySelector('[data-edit-form]');
    if (!form) return;

    form.reset();
    if (btn.dataset.updateUrl) form.action = btn.dataset.updateUrl;

    fetch(btn.dataset.editUrl, { headers: { Accept: 'application/json' } })
        .then((res) => {
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            return res.json();
        })
        .then((data) => {
            fillForm(form, data);
            // event generik: modul lain (lowongan, karyawan, dll) bisa dengerin ini
            // buat isi ulang chained dropdown / komponen custom lainnya.
            form.dispatchEvent(new CustomEvent('edit-data:loaded', { detail: data }));
        })
        .catch((err) => console.error('Gagal mengambil data untuk form edit:', err));
});

function fillForm(form, data) {
    Object.entries(data).forEach(([key, value]) => {
        form.querySelectorAll(`[name="${key}"]`).forEach((field) => {
            if (field.type === 'hidden') {
                // rich text editor (kualifikasi, dll)
                if (field.hasAttribute('data-rich-text-input')) {
                    window.setRichTextContent?.(key, value);
                }
                // currency input (gaji, min_gaji, max_gaji)
                if (field.hasAttribute('data-currency-input')) {
                    window.setCurrencyValue?.(key, value);
                }
                return;
            }

            if (field.type === 'checkbox') {
                field.checked = TRUTHY.includes(String(value).toLowerCase());
            } else if (field.type === 'radio') {
                field.checked = field.value === String(value);
            } else {
                field.value = value ?? '';
            }
        });
    });
}