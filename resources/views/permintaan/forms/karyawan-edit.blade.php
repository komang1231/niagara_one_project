<x-offcanvas.form id="offcanvas-permintaan-karyawan-edit" title="Edit Permintaan Karyawan"
    description="Perbarui permintaan karyawan." size="xl">
    <form id="offcanvas-permintaan-karyawan-edit-form" method="POST" data-edit-form data-chained
        data-url-divisi="{{ route('permintaan-karyawan.get-divisi', ['departemen' => '__ID__']) }}"
        data-url-section="{{ route('permintaan-karyawan.get-section', ['divisi' => '__ID__']) }}"
        data-url-position="{{ route('permintaan-karyawan.get-job-position', ['section' => '__ID__']) }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="_form" value="offcanvas-permintaan-karyawan-edit">

        <x-form.input name="kode" label="Kode Permintaan" readonly />

        <h6 class="mb-3 mt-4">Judul Permintaan</h6>
        <x-form.input name="nama" label="Judul" type="text" placeholder="Contoh: andrew" required />

        <h6 class="mb-3 mt-4">Penempatan</h6>
        <div class="row">
            <div class="col-md-4">
                <x-form.select name="cabang_kantor_id" id="pk_edit_cabang_kantor_id" label="Cabang Kantor"
                    :options="$cabangKantors->pluck('nama', 'id')" nullable required />
            </div>
            <div class="col-md-4">
                <x-form.select name="departemen_id" id="pk_edit_departemen_id" label="Departemen" :options="$departemens->pluck('nama', 'id')"
                    nullable required />
            </div>

            <div class="col-md-4">
                <x-form.select name="divisi_id" id="pk_edit_divisi_id" label="Divisi" :options="[]" nullable
                    disabled data-chained-child />
            </div>

            <div class="col-md-4">
                <x-form.select name="section_id" id="pk_edit_section_id" label="Section" :options="[]" nullable
                    disabled data-chained-child />
            </div>
            
            <div class="col-md-4">
                <x-form.select name="job_position_id" id="pk_edit_job_position_id" label="Job Position"
                    :options="[]" nullable disabled data-chained-child />
            </div>

            <div class="col-md-4">
                <x-form.select name="job_level_id" id="pk_edit_job_level_id" label="Job Level" :options="$jobLevels->pluck('nama', 'id')"
                    nullable required />
            </div>

        </div>

        <h6 class="mb-3 mt-4">Kebutuhan</h6>
        <div class="row">
            <div class="col-md-4">
                <x-form.input-stepper name="jumlah" label="Jumlah Karyawan" min="1" required />
            </div>
        </div>
    </form>
</x-offcanvas.form>
