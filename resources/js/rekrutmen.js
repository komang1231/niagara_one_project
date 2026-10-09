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


// ---------------------------------------------------------------------------
// Offcanvas "Data Karyawan Baru" (kandidat Diterima)
//
//   Server yang menentukan kapan dibuka: form-rekrutmen-diterima.blade.php mengisi
//   data-open-id pada <form> kalau ada session('open_diterima') atau old('_rekrutmen_id').
//   Di sini cukup: kalau data-open-id terisi -> isi action form -> buka offcanvas.
// ---------------------------------------------------------------------------

const DITERIMA_ID = 'offcanvas-rekrutmen-diterima';

document.addEventListener('DOMContentLoaded', () => {
    const panel = document.getElementById(DITERIMA_ID);
    const form = panel && panel.querySelector('form');
    if (!form) return;

    const id = form.dataset.openId;
    if (!id) return;

    // Tujuan submit = POST rekrutmen/{id}/lengkapi-karyawan
    form.setAttribute('action', form.getAttribute('action').replace('__ID__', id));

    // Buka lewat tombol bayangan (tidak bergantung window.bootstrap), sama seperti halaman Permintaan
    const t = document.createElement('button');
    t.type = 'button';
    t.hidden = true;
    t.dataset.bsToggle = 'offcanvas';
    t.dataset.bsTarget = '#' + DITERIMA_ID;
    document.body.appendChild(t);
    t.click();
    t.remove();
});