// Offcanvas lihat/edit Surat Peringatan.
//
// Alur:
//   - klik pensil di tabel  -> offcanvas terbuka MODE LIHAT (semua field disabled, Simpan disabled)
//   - klik [Edit] di footer -> MODE EDIT (field terbuka, tombol Edit hilang, Simpan aktif)
//   - [Lihat Surat] selalu tampil di kedua mode
//
// Pengisian field dilakukan offcanvas-edit.js (fetch data-edit-url). Setelah selesai ia
// mengirim event "edit-data:loaded", yang dipakai di sini untuk info file & mengunci field.
// JSON edit-data harus memuat: kode, karyawan_id, jenis_surat, masa_berlaku, status,
// file_name, file_url.

const FORM_ID = 'offcanvas-sp-edit-form';

const getForm = () => document.getElementById(FORM_ID);
const getPanel = (form) => form.closest('.offcanvas');

function setMode(form, mode) {
    const editing = mode === 'edit';
    const panel = getPanel(form);
    form.dataset.mode = mode;

    // field yang dikunci/dibuka (kode selalu readonly, tidak ikut diubah)
    form.querySelectorAll('input:not([type="hidden"]):not([data-sp-locked]), select, textarea, [data-date-trigger]')
        .forEach((el) => {
            // Select2 hanya memperbarui tampilannya kalau disabled diubah lewat jQuery
            if (el.tagName === 'SELECT' && window.jQuery) {
                window.jQuery(el).prop('disabled', !editing);
            } else {
                el.disabled = !editing;
            }
        });

    panel.querySelector('[data-sp-action="edit"]')?.classList.toggle('d-none', editing);

    const simpan = panel.querySelector(`button[type="submit"][form="${form.id}"]`);
    if (simpan) simpan.disabled = !editing;

    const judul = panel.querySelector('.offcanvas-title');
    if (judul) judul.textContent = editing ? 'Edit Surat Peringatan' : 'Detail Surat Peringatan';
}

// Info file saat ini + tombol "Lihat Surat"
function tampilkanFile(form, data) {
    const panel = getPanel(form);
    const kotak = form.querySelector('[data-sp-current-file]');
    const nama = form.querySelector('[data-sp-file-name]');
    const tombolLihat = panel.querySelector('[data-sp-action="view-file"]');
    const adaFile = Boolean(data.file_name);

    kotak?.classList.toggle('d-none', !adaFile);
    if (nama) nama.textContent = data.file_name ?? '';

    if (tombolLihat) {
        tombolLihat.classList.toggle('d-none', !adaFile);
        tombolLihat.setAttribute('href', data.file_url || '#');
    }
}

// 1) Pensil di tabel diklik: kunci dulu. (Reset form & pengisian oleh offcanvas-edit.js;
//    di sini hanya mengosongkan tampilan upload file yang tidak ikut ter-reset.)
document.addEventListener('click', (e) => {
    const tombol = e.target.closest('[data-sp-edit]');
    if (!tombol) return;

    const form = getForm();
    if (!form) return;

    setMode(form, 'view');
    form.querySelectorAll('[data-file-remove]').forEach((t) => t.click());
});

// 2) Data selesai terisi dari server
document.addEventListener('DOMContentLoaded', () => {
    const form = getForm();
    if (!form) return;

    form.addEventListener('edit-data:loaded', (e) => {
        tampilkanFile(form, e.detail || {});
        setMode(form, 'view'); // field baru saja terisi ulang -> pastikan tetap terkunci
    });

    // 3) Tombol Edit di footer
    getPanel(form).querySelector('[data-sp-action="edit"]')?.addEventListener('click', () => {
        setMode(form, 'edit');
    });

    setMode(form, 'view');
});

// 4) Lihat surat (tombol footer & link file di tabel). Kalau file belum punya URL (href "#") beri tahu user.
document.addEventListener('click', (e) => {
    const link = e.target.closest('[data-sp-file]');
    if (!link) return;

    const href = link.getAttribute('href');
    if (href && href !== '#') return; // file asli -> buka di tab baru seperti biasa

    e.preventDefault();
    window.Swal?.fire({
        icon: 'info',
        title: 'File belum tersedia',
        text: 'File surat ini tidak dapat dibuka.',
        confirmButtonText: 'OK',
    });
});
