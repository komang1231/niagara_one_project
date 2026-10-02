<x-offcanvas.form id="offcanvas-permintaan-karyawan" title="Tambah Permintaan Karyawan"
    description="Ajukan kebutuhan karyawan baru." size="xl">
    <form id="offcanvas-permintaan-karyawan-form" action="{{ route('permintaan-karyawan.store') }}" method="POST"
        data-chained data-reset-on-close
        data-url-divisi="{{ route('permintaan-karyawan.get-divisi', ['departemen' => '__ID__']) }}"
        data-url-section="{{ route('permintaan-karyawan.get-section', ['divisi' => '__ID__']) }}"
        data-url-position="{{ route('permintaan-karyawan.get-job-position', ['section' => '__ID__']) }}">
        @csrf
        <input type="hidden" name="_form" value="offcanvas-permintaan-karyawan">

        <x-form.input name="kode_preview" label="Kode Permintaan" value="{{ $previewKodeKaryawan }}" readonly />

        <h6 class="mb-3 mt-4">Nama Pemohon</h6>
        <x-form.input name="nama" label="nama" type="text" placeholder="Contoh: andrew" required />

        <h6 class="mb-3 mt-4">Penempatan</h6>
        <div class="row">
            <div class="col-md-4">
                <x-form.select name="cabang_kantor_id" id="pk_create_cabang_kantor_id" label="Cabang Kantor"
                    :options="$cabangKantors->pluck('nama', 'id')" nullable required />
            </div>
            <div class="col-md-4">
                <x-form.select name="departemen_id" id="pk_create_departemen_id" label="Departemen"
                    :options="$departemens->pluck('nama', 'id')" nullable required />
            </div>
            <div class="col-md-4">
                <x-form.select name="job_level_id" id="pk_create_job_level_id" label="Job Level"
                    :options="$jobLevels->pluck('nama', 'id')" nullable required />
            </div>
            <div class="col-md-4">
                <x-form.select name="divisi_id" id="pk_create_divisi_id" label="Divisi" :options="[]"
                    nullable disabled data-chained-child />
            </div>
            <div class="col-md-4">
                <x-form.select name="section_id" id="pk_create_section_id" label="Section" :options="[]"
                    nullable disabled data-chained-child />
            </div>
            <div class="col-md-4">
                <x-form.select name="job_position_id" id="pk_create_job_position_id" label="Job Position"
                    :options="[]" nullable disabled data-chained-child />
            </div>
        </div>

        <h6 class="mb-3 mt-4">Kebutuhan</h6>
        <div class="row">
            <div class="col-md-4">
                <x-form.input name="jumlah" label="Jumlah Karyawan" type="number" min="1" placeholder="Contoh: 2" required />
            </div>
        </div>
    </form>
</x-offcanvas.form>