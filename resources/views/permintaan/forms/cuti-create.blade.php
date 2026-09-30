<x-offcanvas.form id="offcanvas-permintaan-cuti" title="Tambah Permintaan Cuti"
    description="Ajukan cuti karyawan." size="xl">
    <form id="offcanvas-permintaan-cuti-form" action="{{ route('permintaan-cuti.store') }}" method="POST"
        enctype="multipart/form-data" data-cuti-form data-reset-on-close>
        @csrf
        <input type="hidden" name="_form" value="offcanvas-permintaan-cuti">

        <x-form.input name="kode_preview" label="Kode Permintaan" value="{{ $previewKodeCuti }}" readonly />

        <h6 class="mb-3 mt-4">Informasi Cuti</h6>
        <div class="row">
            <div class="col-md-6">
                <x-form.select name="karyawan_id" id="pc_create_karyawan_id" label="Pemohon"
                    :options="$karyawans->pluck('nama', 'id')" nullable required />
            </div>
            <div class="col-md-6">
                <x-form.select name="cuti_id" id="pc_create_cuti_id" label="Jenis Cuti"
                    :options="$cutis->pluck('nama', 'id')" nullable required />
            </div>
            <div class="col-md-6">
                <x-form.input-date name="tanggal_mulai" label="Tanggal Mulai" required />
            </div>
            <div class="col-md-6">
                <x-form.input-date name="tanggal_selesai" label="Tanggal Selesai" required />
            </div>
        </div>

        {{-- Daftar tanggal dibuat otomatis oleh JS dari rentang tanggal di atas --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Rincian Tanggal Cuti <span class="text-danger">*</span></label>
            <div class="permintaan-detail-list" data-cuti-details></div>
        </div>

        <x-form.select name="pengganti_karyawan_id" id="pc_create_pengganti_id" label="Karyawan Pengganti"
            :options="$karyawans->pluck('nama', 'id')" nullable />

        <x-form.textarea name="alasan" label="Alasan" rows="3" placeholder="Tulis alasan cuti..." />

        <x-form.file-upload name="lampiran" label="Lampiran" />
    </form>
</x-offcanvas.form>