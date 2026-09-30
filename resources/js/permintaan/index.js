document.addEventListener('DOMContentLoaded', function () {
    const root = document.getElementById('permintaan-root');
    if (!root) return;

    // jQuery opsional: kalau ada, dipakai supaya select2 ikut ke-update
    const $ = window.jQuery;

    /* =====================================================================
     * HELPER
     * ===================================================================== */
    function onChange(el, handler) {
        if ($) $(el).on('change', handler);
        else el.addEventListener('change', handler);
    }

    function setValue(el, value) {
        if ($) {
            $(el).val(value).trigger('change');
        } else {
            el.value = value;
            el.dispatchEvent(new Event('change'));
        }
    }

    // Buka offcanvas lewat tombol bayangan (tidak bergantung window.bootstrap)
    function bukaOffcanvas(id) {
        const t = document.createElement('button');
        t.type = 'button';
        t.hidden = true;
        t.dataset.bsToggle = 'offcanvas';
        t.dataset.bsTarget = '#' + id;
        document.body.appendChild(t);
        t.click();
        t.remove();
    }

    /* =====================================================================
     * 1. TAB: ingat tab terakhir, sinkron ke URL (?tab=), benerin link pagination
     * ===================================================================== */
    const tabButtons = root.querySelectorAll('[data-permintaan-tab]');

    tabButtons.forEach(function (btn) {
        btn.addEventListener('shown.bs.tab', function () {
            const tab = btn.dataset.permintaanTab;
            sessionStorage.setItem('permintaan-tab', tab);

            const url = new URL(window.location.href);
            url.searchParams.set('tab', tab);
            history.replaceState(null, '', url);
        });
    });

    // Pulihkan tab: prioritas ?tab= di URL, lalu tab terakhir (mis. setelah simpan data)
    const tabTersimpan = new URLSearchParams(window.location.search).get('tab')
        || sessionStorage.getItem('permintaan-tab');
    if (tabTersimpan) {
        const btn = root.querySelector('[data-permintaan-tab="' + tabTersimpan + '"]');
        if (btn && !btn.classList.contains('active')) btn.click();
    }

    // Link pagination tiap tab harus membawa ?tab= milik tab-nya sendiri
    root.querySelectorAll('.tab-pane').forEach(function (pane) {
        pane.querySelectorAll('.pagination a[href]').forEach(function (a) {
            const u = new URL(a.href);
            u.searchParams.set('tab', pane.dataset.tab);
            a.href = u.toString();
        });
    });

    /* =====================================================================
     * 2. (EDIT: ambil data & isi form sekarang ditangani offcanvas-edit.js
     *     global. Di sini cukup mendengarkan event 'edit-data:loaded'
     *     di section chained & cuti di bawah.)
     * ===================================================================== */

    /* =====================================================================
     * 3. MODAL BATALKAN: isi action & label dari tombol yang diklik
     * ===================================================================== */
    const modalBatalkan = document.getElementById('modal-batalkan-permintaan');
    if (modalBatalkan) {
        modalBatalkan.addEventListener('show.bs.modal', function (e) {
            const btn = e.relatedTarget;
            if (!btn) return;
            modalBatalkan.querySelector('form').action = btn.dataset.deleteUrl;
            modalBatalkan.querySelector('[data-batalkan-label]').textContent = btn.dataset.deleteLabel || '';
        });
    }

    /* =====================================================================
     * 4. RESET FORM CREATE saat offcanvas ditutup
     * ===================================================================== */
    document.querySelectorAll('.offcanvas').forEach(function (canvas) {
        const form = canvas.querySelector('form[data-reset-on-close]');
        if (!form) return;

        canvas.addEventListener('hidden.bs.offcanvas', function () {
            form.reset();
            form.querySelectorAll('input.flatpickr-input, input[type="date"]').forEach(function (i) {
                if (i._flatpickr) i._flatpickr.clear();
            });
            form.querySelectorAll('select').forEach(function (s) { setValue(s, ''); });
            form.dispatchEvent(new Event('form:reset'));
        });
    });

    /* =====================================================================
     * 5. CHAINED DROPDOWN (Departemen -> Divisi -> Section -> Job Position)
     *    Dipakai form create & edit lewat atribut data-chained.
     * ===================================================================== */
    document.querySelectorAll('form[data-chained]').forEach(function (form) {
        const pilih = (nama) => form.querySelector('[name="' + nama + '"]');
        const departemen = pilih('departemen_id');
        const divisi = pilih('divisi_id');
        const section = pilih('section_id');
        const jobPosition = pilih('job_position_id');

        const url = {
            divisi: form.dataset.urlDivisi,
            section: form.dataset.urlSection,
            position: form.dataset.urlPosition,
        };

        const sedangPrefill = () => form.dataset.prefilling === '1';

        // "Tiket" per select: respons fetch yang sudah usang dibuang
        const tickets = {};
        const newTicket = (s) => (tickets[s.id] = (tickets[s.id] || 0) + 1);

        function resetSelect(select) {
            newTicket(select); // batalkan fetch yang masih jalan
            select.innerHTML = '<option value="">-- Pilih --</option>';
            select.disabled = true;
            setValue(select, '');
        }

        function fillSelect(select, data, selectedId = '') {
            select.innerHTML = '<option value="">-- Pilih --</option>';
            data.forEach(function (item) {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.nama;
                select.appendChild(option);
            });
            select.disabled = data.length === 0;
            setValue(select, selectedId ?? '');
        }

        function fetchChildren(urlTemplate, parentId, target, selectedId = '') {
            const ticket = newTicket(target);

            return fetch(urlTemplate.replace('__ID__', parentId), { headers: { Accept: 'application/json' } })
                .then(function (res) {
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    return res.json();
                })
                .then(function (data) {
                    if (ticket !== tickets[target.id]) return; // respons usang
                    fillSelect(target, data, selectedId);
                });
        }

        // Data edit sudah terisi -> isi divisi/section/job position berurutan (await!)
        form.addEventListener('edit-data:loaded', async function (event) {
            const d = event.detail || {};
            form.dataset.prefilling = '1';

            try {
                resetSelect(divisi);
                resetSelect(section);
                resetSelect(jobPosition);

                if (d.departemen_id) await fetchChildren(url.divisi, d.departemen_id, divisi, d.divisi_id);
                if (d.divisi_id) await fetchChildren(url.section, d.divisi_id, section, d.section_id);
                if (d.section_id) await fetchChildren(url.position, d.section_id, jobPosition, d.job_position_id);
            } catch (err) {
                console.error('Gagal mengisi chained dropdown:', err);
            } finally {
                delete form.dataset.prefilling;
            }
        });

        // User ganti Departemen
        onChange(departemen, function () {
            if (sedangPrefill()) return;
            resetSelect(divisi);
            resetSelect(section);
            resetSelect(jobPosition);
            if (!this.value) return;
            fetchChildren(url.divisi, this.value, divisi).catch((e) => console.error('Gagal ambil divisi:', e));
        });

        // User ganti Divisi
        onChange(divisi, function () {
            if (sedangPrefill()) return;
            resetSelect(section);
            resetSelect(jobPosition);
            if (!this.value) return;
            fetchChildren(url.section, this.value, section).catch((e) => console.error('Gagal ambil section:', e));
        });

        // User ganti Section
        onChange(section, function () {
            if (sedangPrefill()) return;
            resetSelect(jobPosition);
            if (!this.value) return;
            fetchChildren(url.position, this.value, jobPosition).catch((e) => console.error('Gagal ambil job position:', e));
        });
    });

    /* =====================================================================
     * 6. CUTI: daftar tanggal + toggle setengah hari per tanggal
     * ===================================================================== */
    document.querySelectorAll('form[data-cuti-form]').forEach(function (form) {
        const mulai = form.querySelector('[name="tanggal_mulai"]');
        const selesai = form.querySelector('[name="tanggal_selesai"]');
        const box = form.querySelector('[data-cuti-details]');
        const lampiranInfo = form.querySelector('[data-lampiran-info]');

        const NAMA_HARI = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        const MAKS_HARI = 60; // batas aman supaya form tidak kebanjiran baris
        const pad = (n) => String(n).padStart(2, '0');
        const keString = (d) => d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate());
        const keDate = (s) => {
            const [y, m, d] = s.split('-').map(Number);
            return new Date(y, m - 1, d);
        };

        function pesan(teks) {
            box.innerHTML = '<p class="text-muted small mb-0">' + teks + '</p>';
        }

        // Baca status setengah hari yang sudah dipilih user, supaya tidak hilang saat render ulang
        function statusSekarang() {
            const map = {};
            box.querySelectorAll('.permintaan-detail-row').forEach(function (row) {
                map[row.dataset.tanggal] = row.querySelector('input[type="checkbox"]').checked;
            });
            return map;
        }

        // existing = { 'YYYY-MM-DD': true/false }
        function render(existing = {}) {
            const a = mulai.value;
            const b = selesai.value;

            if (!a || !b) return pesan('Pilih tanggal mulai dan selesai untuk menampilkan daftar tanggal.');

            const awal = keDate(a);
            const akhir = keDate(b);

            if (akhir < awal) return pesan('Tanggal selesai tidak boleh sebelum tanggal mulai.');

            const jumlah = Math.round((akhir - awal) / 86400000) + 1;
            if (jumlah > MAKS_HARI) return pesan('Rentang cuti maksimal ' + MAKS_HARI + ' hari.');

            let html = '';
            for (let i = 0; i < jumlah; i++) {
                const d = new Date(awal);
                d.setDate(awal.getDate() + i);
                const tgl = keString(d);
                const label = NAMA_HARI[d.getDay()] + ', ' + pad(d.getDate()) + '/' + pad(d.getMonth() + 1) + '/' + d.getFullYear();
                const cek = existing[tgl] ? 'checked' : '';

                // input hidden value 0 dulu, lalu checkbox value 1 (kalau dicentang, nilai 1 yang menang)
                html += '<div class="permintaan-detail-row" data-tanggal="' + tgl + '">'
                    + '<span>' + label + '</span>'
                    + '<input type="hidden" name="details[' + i + '][tanggal]" value="' + tgl + '">'
                    + '<input type="hidden" name="details[' + i + '][setengah_hari]" value="0">'
                    + '<div class="form-check form-switch mb-0">'
                    + '<input class="form-check-input" type="checkbox" role="switch" name="details[' + i + '][setengah_hari]" value="1" ' + cek + '>'
                    + '<label class="form-check-label small">Setengah hari</label>'
                    + '</div></div>';
            }
            box.innerHTML = html;
        }

        onChange(mulai, () => render(statusSekarang()));
        onChange(selesai, () => render(statusSekarang()));

        form.addEventListener('form:reset', () => render());

        // Mode edit: render daftar dari data lama
        form.addEventListener('edit-data:loaded', function (event) {
            const d = event.detail || {};
            const map = {};
            (d.details || []).forEach(function (row) {
                map[String(row.tanggal).slice(0, 10)] = !!Number(row.setengah_hari);
            });
            render(map);

            if (lampiranInfo) {
                lampiranInfo.textContent = d.lampiran
                    ? 'Lampiran saat ini: ' + String(d.lampiran).split('/').pop() + ' (upload file baru untuk mengganti)'
                    : '';
            }
        });

        render();
    });

    /* =====================================================================
     * 7. Buka lagi offcanvas create kalau validasi gagal
     * ===================================================================== */
    if (root.dataset.reopen) bukaOffcanvas(root.dataset.reopen);
});