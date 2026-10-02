<x-offcanvas.form id="offcanvas-permintaan-cuti-edit" title="Edit Permintaan Cuti"
    description="Perbarui permintaan cuti." size="xl">
    <form id="offcanvas-permintaan-cuti-edit-form" method="POST" enctype="multipart/form-data" data-edit-form data-cuti-form data-chained>
        @csrf
        @method('PUT')
        <input type="hidden" name="_form" value="offcanvas-permintaan-cuti-edit">

        <x-form.input name="kode" label="Kode Permintaan" readonly />

        <h6 class="mb-3 mt-4">Informasi Cuti</h6>
        <div class="row">
            <div class="col-md-6">
                <x-form.select name="karyawan_id" id="pc_edit_karyawan_id" label="Pemohon"
                    :options="$karyawans->pluck('nama', 'id')" nullable required />
            </div>
            <div class="col-md-6">
                <x-form.select name="cuti_id" id="pc_edit_cuti_id" label="Jenis Cuti"
                    :options="$cutis->pluck('nama', 'id')" nullable required />
            </div>
            <div class="col-md-6">
                <x-form.input-date name="tanggal_mulai" label="Tanggal Mulai" required />
            </div>
            <div class="col-md-6">
                <x-form.input-date name="tanggal_selesai" label="Tanggal Selesai" required />
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Rincian Tanggal Cuti <span class="text-danger">*</span></label>
            <div class="permintaan-detail-list" data-cuti-details></div>
        </div>

        <x-form.select name="pengganti_karyawan_id" id="pc_edit_pengganti_id" label="Karyawan Pengganti"
            :options="$karyawans->pluck('nama', 'id')" nullable />

        <x-form.textarea name="alasan" label="Alasan" rows="3" />

        <x-form.file-upload name="lampiran" label="Lampiran" />
        {{-- Diisi JS: nama lampiran yang sudah tersimpan --}}
        <div class="form-text mb-3" data-lampiran-info></div>
    </form>
</x-offcanvas.form>