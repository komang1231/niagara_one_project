<x-offcanvas.form id="offcanvas-karyawan" title="Tambah Karyawan" description="Tambahkan karyawan baru ke sistem."
    size="xl">
    <form id="offcanvas-karyawan-form" action="{{ route('karyawan.store') }}" method="POST">
        @csrf

        {{-- =========================
            KODE KARYAWAN
        ========================== --}}
        <x-form.input name="nip" label="Kode Karyawan" value="{{ $previewNip }}" readonly />


        {{-- =========================
            INFORMASI KARYAWAN
        ========================== --}}
        <h6 class="mb-3 mt-4">Informasi Karyawan</h6>

        <div class="row">

            <div class="col-md-4">
                <x-form.input name="nama" label="Nama Karyawan" placeholder="Contoh: Andrew" required />
            </div>

            <div class="col-md-4">
                <x-form.input name="email" label="Email" type="email" placeholder="Contoh: andrew@email.com"
                    required />
            </div>

            <div class="col-md-4">
                <x-form.input name="no_tlp" label="No. Telepon" placeholder="Contoh: 081234567890" required />
            </div>

            <div class="col-md-4">
                <x-form.input name="nik" label="NIK" placeholder="Masukkan NIK" required />
            </div>

        </div>


        {{-- =========================
            PENEMPATAN & JABATAN
        ========================== --}}
        <h6 class="mb-3 mt-4">Penempatan & Jabatan</h6>

        <div class="row">

            <div class="col-md-4">
                <x-form.select name="departemen_id" label="Departemen" :options="$departemens->pluck('nama', 'id')" nullable required />
            </div>

            <div class="col-md-4">
                <x-form.select name="divisi_id" label="Divisi" :options="[]" nullable disabled required />
            </div>

            <div class="col-md-4">
                <x-form.select name="section_id" label="Section" :options="[]" nullable disabled required />
            </div>

            <div class="col-md-4">
                <x-form.select name="job_position_id" label="Job Position" :options="[]" nullable disabled
                    required />
            </div>

            <div class="col-md-4">
                <x-form.select name="job_level_id" label="Job Level" :options="$jobLevels->pluck('nama', 'id')" nullable required />
            </div>

            <div class="col-md-4">
                <x-form.select name="cabang_kantor_id" label="Cabang Kantor" :options="$cabangKantors->pluck('nama', 'id')" nullable required />
            </div>

        </div>


        {{-- =========================
            KEPEGAWAIAN
        ========================== --}}
        <h6 class="mb-3 mt-4">Kepegawaian</h6>

        <div class="row">

            <div class="col-md-4">
                <x-form.select name="status_kepegawaian_id" label="Status Kepegawaian" :options="$statusKepegawaians->pluck('nama', 'id')" nullable
                    required />
            </div>

            <div class="col-md-4">
                <x-form.input name="gaji" label="Gaji" type="number" placeholder="Contoh: 5000000" required />
            </div>

        </div>


        {{-- =========================
            DATA PRIBADI
        ========================== --}}
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


        {{-- =========================
            ADMINISTRASI
        ========================== --}}
        <h6 class="mb-3 mt-4">Administrasi</h6>

        <div class="row">

            <div class="col-md-4">
                <x-form.input name="no_bpjs_ketenagakerjaan" label="No. BPJS Ketenagakerjaan"
                    placeholder="Masukkan nomor BPJS" required />
            </div>

            <div class="col-md-4">
                <x-form.input name="no_bpjs_kesehatan" label="No. BPJS Kesehatan" placeholder="Masukkan nomor BPJS"
                    required />
            </div>

            <div class="col-md-4">
                <x-form.input name="no_npwp" label="No. NPWP" placeholder="Masukkan nomor NPWP" required />
            </div>

        </div>


        {{-- =========================
            DATA BANK
        ========================== --}}
        <h6 class="mb-3 mt-4">Data Bank</h6>

        <div class="row">

            <div class="col-md-4">
                <x-form.select name="bank_id" label="Bank" :options="$banks->pluck('nama', 'id')" nullable required />
            </div>

            <div class="col-md-4">
                <x-form.input name="nama_bank" label="Nama Pemilik Rekening"
                    placeholder="Masukkan nama pemilik rekening" required />
            </div>

            <div class="col-md-4">
                <x-form.input name="no_rekening" label="No. Rekening" placeholder="Masukkan nomor rekening" required />
            </div>

        </div>


        {{-- =========================
            REKRUTMEN
        ========================== --}}
        <h6 class="mb-3 mt-4">Rekrutmen</h6>

        <div class="row">

            <div class="col-md-4">
                <x-form.select name="lowongan_id" label="Lowongan" :options="$lowongans->pluck('nama', 'id')" nullable />
            </div>

        </div>


        {{-- =========================
            STATUS
        ========================== --}}
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
        const form = document.getElementById('offcanvas-karyawan-form');

        const departemenSelect = form.querySelector('#departemen_id');
        const divisiSelect = form.querySelector('#divisi_id');
        const sectionSelect = form.querySelector('#section_id');
        const jobPositionSelect = form.querySelector('#job_position_id');

        const routes = {
            getDivisi: "{{ route('karyawan.get-divisi', ['departemen' => '__ID__']) }}",
            getSection: "{{ route('karyawan.get-section', ['divisi' => '__ID__']) }}",
            getJobPosition: "{{ route('karyawan.get-job-position', ['section' => '__ID__']) }}",
        };

        // Reset select ke kondisi kosong & disabled
        function resetSelect(select, placeholder = '-- Pilih --') {
            select.innerHTML = `<option value="">${placeholder}</option>`;
            select.disabled = true;
        }

        // Isi select dengan data baru dari response
        function fillSelect(select, data) {
            select.innerHTML = '<option value="">-- Pilih --</option>';
            data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.nama;
                select.appendChild(option);
            });
            select.disabled = data.length === 0;
        }

        function fetchChildren(url, parentId, targetSelect) {
            const finalUrl = url.replace('__ID__', parentId);

            fetch(finalUrl)
                .then(res => {
                    if (!res.ok) throw new Error('Response tidak OK');
                    return res.json();
                })
                .then(data => fillSelect(targetSelect, data))
                .catch(err => console.error('Gagal ambil data chained dropdown:', err));
        }

        // Departemen -> Divisi
        departemenSelect.addEventListener('change', function() {
            resetSelect(divisiSelect);
            resetSelect(sectionSelect);
            resetSelect(jobPositionSelect);

            const departemenId = this.value;
            if (!departemenId) return;

            fetchChildren(routes.getDivisi, departemenId, divisiSelect);
        });

        // Divisi -> Section
        divisiSelect.addEventListener('change', function() {
            resetSelect(sectionSelect);
            resetSelect(jobPositionSelect);

            const divisiId = this.value;
            if (!divisiId) return;

            fetchChildren(routes.getSection, divisiId, sectionSelect);
        });

        // Section -> Job Position
        sectionSelect.addEventListener('change', function() {
            resetSelect(jobPositionSelect);

            const sectionId = this.value;
            if (!sectionId) return;

            fetchChildren(routes.getJobPosition, sectionId, jobPositionSelect);
        });
    })();
</script>
