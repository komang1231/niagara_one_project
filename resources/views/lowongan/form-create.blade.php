<x-offcanvas.form id="offcanvas-lowongan" title="Tambah Lowongan"
    description="Tambahkan lowongan pekerjaan baru ke sistem." size="xl">
    <form id="offcanvas-lowongan-form" action="{{ route('lowongan.store') }}" method="POST">
        @csrf

        <x-form.input name="kode_preview" label="Kode Lowongan" value="{{ $previewKode }}" readonly />

        <h6 class="mb-3 mt-4">Informasi Lowongan</h6>
        <div class="row">
            <div class="col-md-6">
                <x-form.input name="judul" label="Judul Lowongan" placeholder="Contoh: Staff Finance" required />
            </div>
            <div class="col-md-6">
                <x-form.select name="permintaan_karyawan_id" label="Permintaan Karyawan"
                    :options="$permintaanKaryawans->pluck('nama', 'id')" nullable />
            </div>
        </div>

        <h6 class="mb-3 mt-4">Penempatan</h6>
        <div class="row">
            <div class="col-md-4">
                <x-form.select name="cabang_kantor_id" label="Cabang Kantor"
                    :options="$cabangKantors->pluck('nama', 'id')" nullable required />
            </div>
            <div class="col-md-4">
                <x-form.select name="departemen_id" label="Departemen"
                    :options="$departemens->pluck('nama', 'id')" nullable required />
            </div>
            <div class="col-md-4">
                <x-form.select name="divisi_id" label="Divisi" :options="[]" nullable disabled />
            </div>
            <div class="col-md-4">
                <x-form.select name="section_id" label="Section" :options="[]" nullable disabled />
            </div>
            <div class="col-md-4">
                <x-form.select name="job_position_id" label="Job Position" :options="[]" nullable disabled />
            </div>
            <div class="col-md-4">
                <x-form.select name="job_level_id" label="Job Level"
                    :options="$jobLevels->pluck('nama', 'id')" nullable required />
            </div>
        </div>

        <h6 class="mb-3 mt-4">Detail Lowongan</h6>
        <div class="row">
            <div class="col-md-4">
                <x-form.input name="kuota" label="Kuota" type="number" min="1" placeholder="Contoh: 3" required />
            </div>
            <div class="col-md-4">
                <x-form.input-date name="tanggal_buka" label="Tanggal Buka" required />
            </div>
            <div class="col-md-4">
                <x-form.input-date name="tanggal_tutup" label="Tanggal Tutup" required />
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <x-form.currency name="min_gaji" label="Gaji Minimum" required />
            </div>
            <div class="col-md-6">
                <x-form.currency name="max_gaji" label="Gaji Maksimum" required />
            </div>
        </div>

        <x-form.rich-text-editor name="kualifikasi" label="Kualifikasi" placeholder="Tulis kualifikasi kandidat..." />

        <x-form.textarea name="deskripsi" label="Deskripsi Pekerjaan" rows="4"
            placeholder="Tulis deskripsi pekerjaan..." required />

        <x-form.switch name="status" label="Status Aktif" :checked="true" />
    </form>
</x-offcanvas.form>

<script>
(function () {
    const form = document.getElementById('offcanvas-lowongan-form');

    const departemenSelect   = form.querySelector('#departemen_id');
    const divisiSelect       = form.querySelector('#divisi_id');
    const sectionSelect      = form.querySelector('#section_id');
    const jobPositionSelect  = form.querySelector('#job_position_id');

    const routes = {
        getDivisi:      "{{ route('lowongan.get-divisi', ['departemen' => '__ID__']) }}",
        getSection:     "{{ route('lowongan.get-section', ['divisi' => '__ID__']) }}",
        getJobPosition: "{{ route('lowongan.get-job-position', ['section' => '__ID__']) }}",
    };

    function resetSelect(select, placeholder = '-- Pilih --') {
        select.innerHTML = `<option value="">${placeholder}</option>`;
        select.disabled = true;
    }

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
        fetch(url.replace('__ID__', parentId))
            .then(res => { if (!res.ok) throw new Error('Response tidak OK'); return res.json(); })
            .then(data => fillSelect(targetSelect, data))
            .catch(err => console.error('Gagal ambil data chained dropdown:', err));
    }

    departemenSelect.addEventListener('change', function () {
        resetSelect(divisiSelect);
        resetSelect(sectionSelect);
        resetSelect(jobPositionSelect);
        if (this.value) fetchChildren(routes.getDivisi, this.value, divisiSelect);
    });

    divisiSelect.addEventListener('change', function () {
        resetSelect(sectionSelect);
        resetSelect(jobPositionSelect);
        if (this.value) fetchChildren(routes.getSection, this.value, sectionSelect);
    });

    sectionSelect.addEventListener('change', function () {
        resetSelect(jobPositionSelect);
        if (this.value) fetchChildren(routes.getJobPosition, this.value, jobPositionSelect);
    });
})();
</script>