// Offcanvas lihat/edit Jadwal Karyawan (alurnya sama dengan Surat Peringatan).
//
// Alur:
//   - klik shift (chip) di kalender -> offcanvas terbuka MODE LIHAT (semua field disabled, Simpan disabled)
//   - klik [Edit] di footer         -> MODE EDIT (field terbuka, tombol Edit & Hapus hilang, Simpan aktif)
//   - klik [Hapus] di footer        -> konfirmasi (popup global), lalu DELETE jadwal-karyawan/{id} (soft delete)
//
// Pengisian field & pengisian form.action dilakukan offcanvas-edit.js (fetch data-edit-url,
// pakai data-update-url). Setelah selesai ia mengirim event "edit-data:loaded", yang dipakai
// di sini untuk mengisi dropdown shift (Select2) dan mengunci field.
// JSON edit-data harus memuat: karyawan_id, shift_id, tanggal.

import { initSelectShift } from './jadwal-form';

const FORM_ID = 'offcanvas-jadwal-edit-form';
const DELETE_FORM_ID = 'offcanvas-jadwal-delete-form';

const getForm = () => document.getElementById(FORM_ID);
const getPanel = (form) => form.closest('.offcanvas');

function setMode(form, mode) {
    const editing = mode === 'edit';
    const panel = getPanel(form);
    form.dataset.mode = mode;

    // field yang dikunci/dibuka
    form.querySelectorAll('input:not([type="hidden"]), select, textarea, [data-date-trigger]')
        .forEach((el) => {
            // Select2 hanya memperbarui tampilannya kalau disabled diubah lewat jQuery
            if (el.tagName === 'SELECT' && window.jQuery) {
                window.jQuery(el).prop('disabled', !editing);
            } else {
                el.disabled = !editing;
            }
        });

    // Edit & Hapus hanya tampil di mode lihat
    panel.querySelector('[data-jadwal-view-actions]')?.classList.toggle('d-none', editing);

    const simpan = panel.querySelector(`button[type="submit"][form="${form.id}"]`);
    if (simpan) simpan.disabled = !editing;

    const judul = panel.querySelector('.offcanvas-title');
    if (judul) judul.textContent = editing ? 'Edit Jadwal' : 'Detail Jadwal';
}

// 1) Shift di kalender diklik: kunci dulu. (Reset form & pengisian oleh offcanvas-edit.js;
//    di sini hanya mengosongkan dropdown shift yang tidak ikut ter-reset.)
document.addEventListener('click', (e) => {
    const tombol = e.target.closest('[data-jadwal-edit]');
    if (!tombol) return;

    const form = getForm();
    if (!form) return;

    // Arahkan form Hapus ke jadwal yang diklik (DELETE jadwal-karyawan/{id}, path sama dengan update)
    const deleteForm = document.getElementById(DELETE_FORM_ID);
    if (deleteForm && tombol.dataset.updateUrl) deleteForm.action = tombol.dataset.updateUrl;

    setMode(form, 'view');
    window.jQuery?.(form).find('.jadwal-shift-select').val('').trigger('change');
});

// Chip bisa dibuka lewat keyboard (Enter / Spasi)
document.addEventListener('keydown', (e) => {
    if (e.key !== 'Enter' && e.key !== ' ') return;

    const tombol = e.target.closest?.('[data-jadwal-edit]');
    if (!tombol || e.target !== tombol) return;

    e.preventDefault();
    tombol.click();
});

document.addEventListener('DOMContentLoaded', () => {
    const form = getForm();
    if (!form) return;

    // Dropdown shift dengan jam (Select2)
    form.querySelectorAll('.jadwal-shift-select').forEach(initSelectShift);

    // 2) Data selesai terisi dari server
    form.addEventListener('edit-data:loaded', (e) => {
        const data = e.detail || {};

        // Select2 shift wajib diisi lewat jQuery supaya kotaknya ikut berubah
        window.jQuery?.(form).find('.jadwal-shift-select').val(data.shift_id ?? '').trigger('change');

        setMode(form, 'view'); // field baru saja terisi ulang -> pastikan tetap terkunci
    });

    // 3) Tombol Edit di footer
    getPanel(form).querySelector('[data-jadwal-action="edit"]')?.addEventListener('click', () => {
        setMode(form, 'edit');
    });

    setMode(form, 'view');
});
