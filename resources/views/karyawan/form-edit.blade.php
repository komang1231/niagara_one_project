<x-offcanvas.form id="offcanvas-karyawan-edit" title="Edit Karyawan" description="Perbarui data karyawan." size="xl">
    <form id="offcanvas-karyawan-edit-form" method="POST" data-edit-form>
        @csrf
        @method('PUT')

        <x-form.input name="nip" label="Kode Karyawan" readonly />

        <h6 class="mb-3 mt-4">Informasi Karyawan</h6>

        <div class="row">
            <div class="col-md-4">
                <x-form.input name="nama" label="Nama Karyawan" required />
            </div>
            <div class="col-md-4">
                <x-form.input name="email" label="Email" type="email" required />
            </div>
            <div class="col-md-4">
                <x-form.input name="no_tlp" label="No. Telepon" required />
            </div>
            <div class="col-md-4">
                <x-form.input name="nik" label="NIK" required />
            </div>
        </div>

        <h6 class="mb-3 mt-4">Penempatan & Jabatan</h6>

        <div class="row">
            <div class="col-md-4">
                <x-form.select name="departemen_id" label="Departemen" :options="$departemens->pluck('nama', 'id')" nullable required />
            </div>
            <div class="col-md-4">
                <x-form.select name="divisi_id" label="Divisi" :options="[]" nullable required />
            </div>
            <div class="col-md-4">
                <x-form.select name="section_id" label="Section" :options="[]" nullable required />
            </div>
            <div class="col-md-4">
                <x-form.select name="job_position_id" label="Job Position" :options="[]" nullable required />
            </div>
            <div class="col-md-4">
                <x-form.select name="job_level_id" label="Job Level" :options="$jobLevels->pluck('nama', 'id')" nullable required />
            </div>
            <div class="col-md-4">
                <x-form.select name="cabang_kantor_id" label="Cabang Kantor" :options="$cabangKantors->pluck('nama', 'id')" nullable required />
            </div>
        </div>

        <h6 class="mb-3 mt-4">Kepegawaian</h6>

        <div class="row">
            <div class="col-md-4">
                <x-form.select name="status_kepegawaian_id" label="Status Kepegawaian" :options="$statusKepegawaians->pluck('nama', 'id')" nullable
                    required />
            </div>
            <div class="col-md-4">
                <x-form.input name="gaji" label="Gaji" type="number" required />
            </div>
        </div>

        <h6 class="mb-3 mt-4">Data Pribadi</h6>

        <div class="row">
            <div class="col-md-4">
                <x-form.select name="jenjang_pendidikan_id" label="Jenjang Pendidikan" :options="$jenjangPendidikans->pluck('nama', 'id')" nullable
                    required />
            </div>
            <div class="col-md-4">
                <x-form.select name="status_kawin_id" label="Status Kawin" :options="$statusKawins->pluck('nama', 'id')" nullable required />
            </div>
            <div class="col-md-4">
                <x-form.select name="agama_id" label="Agama" :options="$agamas->pluck('nama', 'id')" nullable required />
            </div>
        </div>

        <h6 class="mb-3 mt-4">Administrasi</h6>

        <div class="row">
            <div class="col-md-4">
                <x-form.input name="no_bpjs_ketenagakerjaan" label="No. BPJS Ketenagakerjaan" required />
            </div>
            <div class="col-md-4">
                <x-form.input name="no_bpjs_kesehatan" label="No. BPJS Kesehatan" required />
            </div>
            <div class="col-md-4">
                <x-form.input name="no_npwp" label="No. NPWP" required />
            </div>
        </div>

        <h6 class="mb-3 mt-4">Data Bank</h6>

        <div class="row">
            <div class="col-md-4">
                <x-form.select name="bank_id" label="Bank" :options="$banks->pluck('nama', 'id')" nullable required />
            </div>
            <div class="col-md-4">
                <x-form.input name="nama_bank" label="Nama Pemilik Rekening" required />
            </div>
            <div class="col-md-4">
                <x-form.input name="no_rekening" label="No. Rekening" required />
            </div>
        </div>

        <h6 class="mb-3 mt-4">Rekrutmen</h6>

        <div class="row">
            <div class="col-md-4">
                <x-form.select name="lowongan_id" label="Lowongan" :options="$lowongans->pluck('nama', 'id')" nullable />
            </div>
        </div>

        <div class="mt-4">
            <label class="form-label d-block">Status Aktif</label>

            <input type="hidden" name="status" value="nonaktif">

            <label class="app-form-switch">
                <input type="checkbox" name="status" value="aktif" id="status" checked>
                <span class="app-form-switch__slider"></span>
            </label>
        </div>
    </form>
</x-offcanvas.form>

<script>
    (function() {
        const editOffcanvasId = 'offcanvas-karyawan-edit';
        const offcanvasEl = document.getElementById(editOffcanvasId);
        if (!offcanvasEl) return;

        const form = offcanvasEl.querySelector('[data-edit-form]');
        const departemenSelect = form.querySelector('#departemen_id');
        const divisiSelect = form.querySelector('#divisi_id');
        const sectionSelect = form.querySelector('#section_id');
        const jobPositionSelect = form.querySelector('#job_position_id');

        const routes = {
            getDivisi: "{{ route('karyawan.get-divisi', ['departemen' => '__ID__']) }}",
            getSection: "{{ route('karyawan.get-section', ['divisi' => '__ID__']) }}",
            getJobPosition: "{{ route('karyawan.get-job-position', ['section' => '__ID__']) }}",
        };

        function resetSelect(select, placeholder = '-- Pilih --') {
            select.innerHTML = `<option value="">${placeholder}</option>`;
        }

        function fillSelect(select, data) {
            select.innerHTML = '<option value="">-- Pilih --</option>';
            data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.nama;
                select.appendChild(option);
            });
        }

        function fetchAndFill(url, parentId, targetSelect) {
            return fetch(url.replace('__ID__', parentId))
                .then(res => res.json())
                .then(data => fillSelect(targetSelect, data));
        }

        // ============================================
        // BARU: listener change — reset dropdown anak
        // begitu user GANTI MANUAL parent-nya
        // ============================================

        departemenSelect.addEventListener('change', function() {
            resetSelect(divisiSelect);
            resetSelect(sectionSelect);
            resetSelect(jobPositionSelect);

            const departemenId = this.value;
            if (!departemenId) return;

            fetchAndFill(routes.getDivisi, departemenId, divisiSelect);
        });

        divisiSelect.addEventListener('change', function() {
            resetSelect(sectionSelect);
            resetSelect(jobPositionSelect);

            const divisiId = this.value;
            if (!divisiId) return;

            fetchAndFill(routes.getSection, divisiId, sectionSelect);
        });

        sectionSelect.addEventListener('change', function() {
            resetSelect(jobPositionSelect);

            const sectionId = this.value;
            if (!sectionId) return;

            fetchAndFill(routes.getJobPosition, sectionId, jobPositionSelect);
        });

        // ============================================
        // Logic lama: isi awal pas offcanvas dibuka
        // (SAMA persis kayak sebelumnya, gak berubah)
        // ============================================

        document.addEventListener('click', function(e) {
            const btn = e.target.closest('[data-edit-url]');
            if (!btn) return;

            const targetOffcanvas = document.querySelector(btn.dataset.bsTarget);
            if (!targetOffcanvas || targetOffcanvas.id !== editOffcanvasId) return;

            fetch(btn.dataset.editUrl, {
                    headers: {
                        Accept: 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    fillSelect(divisiSelect, []);
                    fillSelect(sectionSelect, []);
                    fillSelect(jobPositionSelect, []);

                    if (!data.departemen_id) return;

                    return fetchAndFill(routes.getDivisi, data.departemen_id, divisiSelect)
                        .then(() => {
                            divisiSelect.value = data.divisi_id ?? '';
                            if (!data.divisi_id) return;
                            return fetchAndFill(routes.getSection, data.divisi_id, sectionSelect);
                        })
                        .then(() => {
                            sectionSelect.value = data.section_id ?? '';
                            if (!data.section_id) return;
                            return fetchAndFill(routes.getJobPosition, data.section_id,
                                jobPositionSelect);
                        })
                        .then(() => {
                            jobPositionSelect.value = data.job_position_id ?? '';
                        });
                })
                .catch(err => console.error('Gagal isi chained dropdown edit:', err));
        });
    })();
</script>
