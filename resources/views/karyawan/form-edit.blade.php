<x-offcanvas.form id="offcanvas-karyawan-edit" title="Edit Karyawan" description="Perbarui data karyawan." size="xl">
    <form id="offcanvas-karyawan-edit-form" method="POST" data-edit-form novalidate>
        @csrf
        @method('PUT')

        {{-- Penanda form mana yang disubmit (dipakai untuk membuka lagi offcanvas kalau validasi gagal) --}}
        <input type="hidden" name="_form" value="offcanvas-karyawan-edit">
        {{-- id karyawan yang sedang diedit; diisi JS, dipakai untuk membuka lagi form ini kalau validasi gagal --}}
        <input type="hidden" name="_edit_id" value="{{ old('_edit_id') }}">

        {{-- =========================
            KODE KARYAWAN
        ========================== --}}
        <x-form.input name="nip" id="edit_nip" label="Kode Karyawan" readonly />

        {{-- =========================
            INFORMASI KARYAWAN
        ========================== --}}
        <h6 class="mb-3 mt-4">Informasi Karyawan</h6>

        <div class="row">

            <div class="col-md-4">
                <x-form.input name="nama" id="edit_nama" label="Nama Karyawan" required />
            </div>

            <div class="col-md-4">
                <x-form.input name="email" id="edit_email" label="Email" type="email" required />
            </div>

            <div class="col-md-4">
                <x-form.input name="no_tlp" id="edit_no_tlp" label="No. Telepon" inputmode="numeric" required />
            </div>

            <div class="col-md-4">
                <x-form.input name="nik" id="edit_nik" label="NIK" inputmode="numeric" required />
            </div>

            <div class="col-md-4">
                <x-form.select name="role_id" id="edit_role_id" label="Role" :options="$roles->pluck('nama', 'id')" nullable required />
            </div>

        </div>


        {{-- =========================
            PENEMPATAN & JABATAN
            Departemen -> Divisi -> Section -> Job Position (chained dropdown).
            Divisi, Section, dan Job Position boleh kosong (nullable di request & migration).
        ========================== --}}
        <h6 class="mb-3 mt-4">Penempatan & Jabatan</h6>

        <div class="row">

            <div class="col-md-4">
                <x-form.select name="departemen_id" id="edit_departemen_id" label="Departemen" :options="$departemens->pluck('nama', 'id')"
                    nullable required />
            </div>

            <div class="col-md-4">
                <x-form.select name="divisi_id" id="edit_divisi_id" label="Divisi" :options="[]" nullable
                    disabled />
            </div>

            <div class="col-md-4">
                <x-form.select name="section_id" id="edit_section_id" label="Section" :options="[]" nullable
                    disabled />
            </div>

            <div class="col-md-4">
                <x-form.select name="job_position_id" id="edit_job_position_id" label="Job Position" :options="[]"
                    nullable disabled />
            </div>

            <div class="col-md-4">
                <x-form.select name="job_level_id" id="edit_job_level_id" label="Job Level" :options="$jobLevels->pluck('nama', 'id')" nullable
                    required />
            </div>

            <div class="col-md-4">
                <x-form.select name="cabang_kantor_id" id="edit_cabang_kantor_id" label="Cabang Kantor"
                    :options="$cabangKantors->pluck('nama', 'id')" nullable required />
            </div>

        </div>


        {{-- =========================
            KEPEGAWAIAN
        ========================== --}}
        <h6 class="mb-3 mt-4">Kepegawaian</h6>

        <div class="row">

            <div class="col-md-4">
                <x-form.select name="status_kepegawaian_id" id="edit_status_kepegawaian_id" label="Status Kepegawaian"
                    :options="$statusKepegawaians->pluck('nama', 'id')" nullable required />
            </div>

            <div class="col-md-4">
                <x-form.currency name="gaji" label="Gaji" required />
            </div>
        </div>


        {{-- =========================
            DATA PRIBADI
        ========================== --}}
        <h6 class="mb-3 mt-4">Data Pribadi</h6>

        <div class="row">

            <div class="col-md-4">
                <x-form.select name="jenjang_pendidikan_id" id="edit_jenjang_pendidikan_id" label="Jenjang Pendidikan"
                    :options="$jenjangPendidikans->pluck('nama', 'id')" nullable required />
            </div>

            <div class="col-md-4">
                <x-form.select name="status_kawin_id" id="edit_status_kawin_id" label="Status Kawin" :options="$statusKawins->pluck('nama', 'id')"
                    nullable required />
            </div>

            <div class="col-md-4">
                <x-form.select name="agama_id" id="edit_agama_id" label="Agama" :options="$agamas->pluck('nama', 'id')" nullable required />
            </div>

        </div>


        {{-- =========================
            ADMINISTRASI
        ========================== --}}
        <h6 class="mb-3 mt-4">Administrasi</h6>

        <div class="row">

            <div class="col-md-4">
                <x-form.input name="no_bpjs_ketenagakerjaan" id="edit_no_bpjs_ketenagakerjaan"
                    label="No. BPJS Ketenagakerjaan" inputmode="numeric" required />
            </div>

            <div class="col-md-4">
                <x-form.input name="no_bpjs_kesehatan" id="edit_no_bpjs_kesehatan" label="No. BPJS Kesehatan"
                    inputmode="numeric" required />
            </div>

            <div class="col-md-4">
                <x-form.input name="no_npwp" id="edit_no_npwp" label="No. NPWP" inputmode="numeric" required />
            </div>

        </div>


        {{-- =========================
            DATA BANK
        ========================== --}}
        <h6 class="mb-3 mt-4">Data Bank</h6>

        <div class="row">

            <div class="col-md-4">
                <x-form.select name="bank_id" id="edit_bank_id" label="Bank" :options="$banks->pluck('nama', 'id')" nullable
                    required />
            </div>

            <div class="col-md-4">
                <x-form.input name="nama_bank" id="edit_nama_bank" label="Nama Pemilik Rekening" required />
            </div>

            <div class="col-md-4">
                <x-form.input name="no_rekening" id="edit_no_rekening" label="No. Rekening" inputmode="numeric"
                    required />
            </div>

        </div>


        {{-- =========================
            REKRUTMEN
        ========================== --}}
        <h6 class="mb-3 mt-4">Rekrutmen</h6>

        <div class="row">

            <div class="col-md-4">
                <x-form.select name="lowongan_id" id="edit_lowongan_id" label="Lowongan" :options="$lowongans->pluck('judul', 'id')"
                    nullable />
            </div>

        </div>


        {{-- =========================
            STATUS
            Hanya Aktif / Nonaktif. Status "Resign" TIDAK bisa diubah dari sini —
            berubah otomatis saat permintaan resign disetujui & tanggal efektifnya tiba.
            Kalau karyawan sudah resign, switch di-disable (JS) dan status tidak ikut dikirim.
        ========================== --}}
        <div class="mt-4">
            <label class="form-label d-block">Status Aktif</label>

            {{-- penanda: karyawan ini berstatus resign (diisi JS) --}}
            <input type="hidden" name="_is_resign" value="{{ old('_is_resign', '0') }}" data-is-resign>

            <input type="hidden" name="status" value="nonaktif" data-status-hidden>

            <label class="app-form-switch">
                <input type="checkbox" name="status" value="aktif" id="edit_status" data-status-switch
                    @checked(old('status', 'aktif') === 'aktif')>
                <span class="app-form-switch__slider"></span>
            </label>

            <div class="form-text" data-status-note>
                Status Resign tidak diatur di sini, tetapi otomatis dari permintaan resign yang sudah disetujui.
            </div>
        </div>

    </form>
</x-offcanvas.form>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('offcanvas-karyawan-edit-form');
        if (!form) return;

        // Select2 hanya memicu event "change" lewat jQuery (bukan event native), jadi listener
        // WAJIB dipasang pakai jQuery. Kalau jQuery belum ada, jatuh ke event native.
        const $ = window.jQuery;

        const roleSelect = form.querySelector('#edit_role_id');

        const departemenSelect = form.querySelector('#edit_departemen_id');
        const divisiSelect = form.querySelector('#edit_divisi_id');
        const sectionSelect = form.querySelector('#edit_section_id');
        const jobPositionSelect = form.querySelector('#edit_job_position_id');

        const editIdInput = form.querySelector('[name="_edit_id"]');
        const isResignInput = form.querySelector('[data-is-resign]');
        const statusHidden = form.querySelector('[data-status-hidden]');
        const statusSwitch = form.querySelector('[data-status-switch]');
        const statusNote = form.querySelector('[data-status-note]');
        const defaultStatusNote = statusNote.textContent.trim();

        const routes = {
            getDivisi: "{{ route('karyawan.get-divisi', ['departemen' => '__ID__']) }}",
            getSection: "{{ route('karyawan.get-section', ['divisi' => '__ID__']) }}",
            getJobPosition: "{{ route('karyawan.get-job-position', ['section' => '__ID__']) }}",
        };

        // "Tiket" per select: kalau ada fetch baru / reset, respons fetch lama dibuang
        const tickets = {};
        const newTicket = (select) => (tickets[select.id] = (tickets[select.id] || 0) + 1);

        function onChange(el, handler) {
            if ($) $(el).on('change', handler);
            else el.addEventListener('change', handler);
        }

        // Segarkan tampilan Select2 TANPA memicu handler 'change' milik kita
        // (namespace .select2 hanya didengar Select2) -> tidak ada reset berantai.
        function refreshUi(select) {
            if ($) $(select).trigger('change.select2');
        }

        function resetSelect(select) {
            newTicket(select); // batalkan fetch yang masih jalan
            select.innerHTML = '<option value=""></option>';
            select.disabled = true;
            refreshUi(select);
        }

        function fillSelect(select, data, selectedId = '') {
            select.innerHTML = '<option value=""></option>';
            data.forEach((item) => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.nama;
                select.appendChild(option);
            });
            select.disabled = data.length === 0;
            select.value = selectedId ?? '';
            refreshUi(select);
        }

        function fetchChildren(url, parentId, targetSelect, selectedId = '') {
            const ticket = newTicket(targetSelect);

            return fetch(url.replace('__ID__', parentId), {
                    headers: {
                        Accept: 'application/json'
                    }
                })
                .then((res) => {
                    if (!res.ok) throw new Error(`HTTP ${res.status}`);
                    return res.json();
                })
                .then((data) => {
                    if (ticket !== tickets[targetSelect.id]) return; // respons usang
                    fillSelect(targetSelect, data, selectedId);
                });
        }

        // Status Resign tidak boleh diubah dari form: switch di-disable & tidak ikut dikirim.
        function applyStatus(status) {
            const isResign = status === 'resign';

            isResignInput.value = isResign ? '1' : '0';
            statusSwitch.disabled = isResign;
            statusHidden.disabled = isResign;

            if (isResign) {
                statusSwitch.checked = false;
                statusNote.textContent =
                    'Karyawan ini berstatus Resign. Status tidak dapat diubah dari form ini.';
                statusNote.classList.add('text-danger');
            } else {
                statusNote.textContent = defaultStatusNote;
                statusNote.classList.remove('text-danger');
            }
        }

        // Data edit selesai dimuat (event dikirim oleh offcanvas-edit.js, atau oleh index
        // saat membuka lagi form ini setelah validasi gagal)
        // -> isi divisi / section / job position sesuai data, berurutan.
        form.addEventListener('edit-data:loaded', async function(event) {
            const {
                id,
                status,
                role_id,
                departemen_id,
                divisi_id,
                section_id,
                job_position_id
            } = event.detail || {};

            editIdInput.value = id ?? '';
            applyStatus(status);

            roleSelect.value = role_id ?? '';
            refreshUi(roleSelect);

            try {
                resetSelect(divisiSelect);
                resetSelect(sectionSelect);
                resetSelect(jobPositionSelect);

                if (departemen_id) {
                    await fetchChildren(routes.getDivisi, departemen_id, divisiSelect, divisi_id);
                }
                if (divisi_id) {
                    await fetchChildren(routes.getSection, divisi_id, sectionSelect, section_id);
                }
                if (section_id) {
                    await fetchChildren(routes.getJobPosition, section_id, jobPositionSelect,
                        job_position_id);
                }
            } catch (err) {
                console.error('Gagal mengisi chained dropdown edit karyawan:', err);
            }
        });

        // User ganti Departemen -> Divisi
        onChange(departemenSelect, function() {
            resetSelect(divisiSelect);
            resetSelect(sectionSelect);
            resetSelect(jobPositionSelect);
            if (!this.value) return;

            fetchChildren(routes.getDivisi, this.value, divisiSelect)
                .catch((err) => console.error('Gagal ambil divisi:', err));
        });

        // User ganti Divisi -> Section
        onChange(divisiSelect, function() {
            resetSelect(sectionSelect);
            resetSelect(jobPositionSelect);
            if (!this.value) return;

            fetchChildren(routes.getSection, this.value, sectionSelect)
                .catch((err) => console.error('Gagal ambil section:', err));
        });

        // User ganti Section -> Job Position
        onChange(sectionSelect, function() {
            resetSelect(jobPositionSelect);
            if (!this.value) return;

            fetchChildren(routes.getJobPosition, this.value, jobPositionSelect)
                .catch((err) => console.error('Gagal ambil job position:', err));
        });
    });
</script>
