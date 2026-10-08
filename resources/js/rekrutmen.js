// Offcanvas lihat/edit Rekrutmen (alur sama seperti Surat Peringatan).
//
//   - klik pensil di tabel  -> offcanvas terbuka MODE LIHAT (semua field disabled, Simpan disabled)
//   - klik [Edit] di footer -> MODE EDIT (field terbuka, tombol Edit hilang, Simpan aktif)
//   - [Lihat CV] selalu tampil di kedua mode (kalau kandidat punya CV)
//
// Pengisian field dilakukan offcanvas-edit.js. JSON edit-data harus memuat cv_name & cv_url.
// Dropdown berantai (divisi/section/posisi) diisi async oleh script di form-edit.blade.php,
// yang mengirim event "rekrutmen:prefilled" setelah selesai -> kunci diterapkan ulang di sini.

const FORM_ID = 'offcanvas-rekrutmen-edit-form';

const getForm = () => document.getElementById(FORM_ID);
const getPanel = (form) => form.closest('.offcanvas');

function setDisabled(el, disabled) {
    // Select2 hanya memperbarui tampilannya kalau disabled diubah lewat jQuery
    if (el.tagName === 'SELECT' && window.jQuery) {
        window.jQuery(el).prop('disabled', disabled);
    } else {
        el.disabled = disabled;
    }
}

function setMode(form, mode) {
    const editing = mode === 'edit';
    const panel = getPanel(form);
    form.dataset.mode = mode;

    form.querySelectorAll(
        'input:not([type="hidden"]):not([data-rk-locked]), select, textarea, [data-date-trigger]'
    ).forEach((el) => {
        if (!editing) return setDisabled(el, true);

        // Dropdown berantai yang belum punya pilihan (induknya kosong) tetap nonaktif
        const chained = el.tagName === 'SELECT' && el.hasAttribute('data-rk-chained');
        const kosong = chained && el.options.length <= 1;
        setDisabled(el, kosong);
    });

    panel.querySelector('[data-rk-action="edit"]')?.classList.toggle('d-none', editing);

    const simpan = panel.querySelector(`button[type="submit"][form="${form.id}"]`);
    if (simpan) simpan.disabled = !editing;

    const judul = panel.querySelector('.offcanvas-title');
    if (judul) judul.textContent = editing ? 'Edit Rekrutmen' : 'Detail Rekrutmen';
}

// Info CV saat ini + tombol "Lihat CV"
function tampilkanCv(form, data) {
    const panel = getPanel(form);
    const info = form.querySelector('[data-rk-current-cv]');
    const tombol = panel.querySelector('[data-rk-action="view-cv"]');
    const adaCv = Boolean(data.cv_name);

    if (info) {
        info.textContent = adaCv
            ? `CV saat ini: ${data.cv_name}. Akan tetap digunakan jika tidak mengunggah file baru.`
            : 'Belum ada CV.';
    }

    if (tombol) {
        tombol.classList.toggle('d-none', !adaCv);
        tombol.setAttribute('href', data.cv_url || '#');
    }
}

// 1) Pensil di tabel diklik: kunci dulu
document.addEventListener('click', (e) => {
    const tombol = e.target.closest('[data-rk-edit]');
    if (!tombol) return;

    const form = getForm();
    if (!form) return;

    setMode(form, 'view');
    form.querySelectorAll('[data-file-remove]').forEach((t) => t.click());
});

document.addEventListener('DOMContentLoaded', () => {
    const form = getForm();
    if (!form) return;

    // 2) Data utama terisi dari server
    form.addEventListener('edit-data:loaded', (e) => {
        tampilkanCv(form, e.detail || {});
        setMode(form, 'view');
    });

    // 3) Dropdown berantai selesai diisi (async) -> terapkan ulang mode saat ini
    form.addEventListener('rekrutmen:prefilled', () => {
        setMode(form, form.dataset.mode === 'edit' ? 'edit' : 'view');
    });

    // 4) Tombol Edit di footer
    getPanel(form).querySelector('[data-rk-action="edit"]')?.addEventListener('click', () => {
        setMode(form, 'edit');
    });

    // Reset ke mode lihat tiap offcanvas ditutup
    getPanel(form).addEventListener('hidden.bs.offcanvas', () => setMode(form, 'view'));

    setMode(form, 'view');
});

// 5) Lihat CV: kalau belum punya URL beri tahu user
document.addEventListener('click', (e) => {
    const link = e.target.closest('[data-rk-action="view-cv"]');
    if (!link) return;

    const href = link.getAttribute('href');
    if (href && href !== '#') return;

    e.preventDefault();
    window.Swal?.fire({
        icon: 'info',
        title: 'File belum tersedia',
        text: 'CV kandidat ini tidak dapat dibuka.',
        confirmButtonText: 'OK',
    });
});
