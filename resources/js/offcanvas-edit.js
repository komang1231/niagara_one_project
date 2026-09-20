/**
 * resources/js/offcanvas-edit.js
 * ---------------------------------------------------------------
 * Loader generik untuk form EDIT yang ada di dalam offcanvas.
 * Dipakai bareng semua modul (departemen, karyawan, lowongan, master data, dst.)
 * tanpa perlu nulis <script> lagi di tiap halaman.
 *
 * CARA PAKAI
 *
 * 1) Tombol edit di tabel — semua info lewat data-attribute:
 *
 *    <x-button variant="icon-edit" icon="bi-pencil"
 *        data-bs-toggle="offcanvas"
 *        data-bs-target="#offcanvas-departemen-edit"
 *        data-edit-url="{{ url('departemen/' . $row->id . '/edit-data') }}"
 *        data-update-url="{{ route('departemen.update', $row->id) }}" />
 *
 * 2) Form di dalam offcanvas edit, tambahkan atribut data-edit-form:
 *
 *    <form method="POST" data-edit-form> @csrf @method('PUT') ... </form>
 *
 * 3) Endpoint data-edit-url harus mengembalikan JSON dengan
 *    key SAMA dengan atribut `name` di field form. Contoh:
 *    { "kode": "DEP-001", "nama": "HR", "status": "aktif" }
 *
 * Kenapa pakai `document.addEventListener` (event delegation)?
 * Karena isi tabel bisa diganti lewat AJAX (filter.js), sehingga tombol
 * lama hilang. Listener di `document` tetap hidup untuk tombol yang baru.
 */

// Nilai yang dianggap "nyala" untuk checkbox/switch.
const TRUTHY = ['1', 'true', 'aktif', 'active', 'yes', 'ya'];

document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-edit-url]');
    if (!btn) return;

    const offcanvas = document.querySelector(btn.dataset.bsTarget);
    const form = offcanvas?.querySelector('[data-edit-form]');
    if (!form) return;

    // Kosongkan dulu supaya data baris sebelumnya tidak sempat terlihat.
    form.reset();

    if (btn.dataset.updateUrl) {
        form.action = btn.dataset.updateUrl;
    }

    fetch(btn.dataset.editUrl, { headers: { Accept: 'application/json' } })
        .then((res) => {
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            return res.json();
        })
        .then((data) => fillForm(form, data))
        .catch((err) => console.error('Gagal mengambil data untuk form edit:', err));
});

function fillForm(form, data) {
    Object.entries(data).forEach(([key, value]) => {
        form.querySelectorAll(`[name="${key}"]`).forEach((field) => {
            // Lewati hidden input (mis. pasangan checkbox / _token / _method).
            if (field.type === 'hidden') return;

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