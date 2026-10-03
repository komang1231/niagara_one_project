<x-offcanvas.form id="offcanvas-permintaan-cuti-edit" title="Edit Permintaan Cuti"
    description="Perbarui permintaan cuti." size="xl">
    <form id="offcanvas-permintaan-cuti-edit-form" method="POST" enctype="multipart/form-data" data-edit-form data-cuti-form>
        @csrf
        @method('PUT')
        <input type="hidden" name="_form" value="offcanvas-permintaan-cuti-edit">

        <x-form.input name="kode" label="Kode Permintaan" readonly />

        <h6 class="mb-3 mt-4">Informasi Cuti</h6>
        <div class="row">
            <div class="col-md-4">
                <x-form.select name="cuti_id" id="pc_edit_cuti_id" label="Jenis Cuti"
                    :options="$cutis->pluck('nama', 'id')" nullable required />
            </div>
            <div class="col-md-4">
                <x-form.input-date name="tanggal_mulai" id="pc_edit_tanggal_mulai" label="Tanggal Mulai" required />
            </div>
            <div class="col-md-4">
                <x-form.input-date name="tanggal_selesai" id="pc_edit_tanggal_selesai" label="Tanggal Selesai" after="tanggal_mulai" required />
            </div>
        </div>

        {{-- Pesan error tanggal (diisi JS) --}}
        <div class="form-inline-error" data-cuti-error hidden></div>

        {{-- Daftar tanggal dibuat otomatis oleh JS dari rentang tanggal di atas --}}
        <div class="mb-3">
            <div class="cuti-detail-head">
                <label class="form-label fw-semibold mb-0">Rincian Tanggal Cuti <span class="text-danger">*</span></label>
                <div class="cuti-detail-bulk" data-cuti-bulk hidden>
                    <button type="button" data-bulk="0">Semua sehari penuh</button>
                    <button type="button" data-bulk="1">Semua setengah hari</button>
                </div>
            </div>
            <p class="cuti-detail-hint mb-2">Pilih lama cuti untuk tiap tanggal: sehari penuh atau setengah hari.</p>
            <div class="cuti-detail-list" data-cuti-details></div>
            <div class="cuti-detail-summary" data-cuti-summary></div>
        </div>

        <x-form.select name="pengganti_karyawan_id" id="pc_edit_pengganti_id" label="Karyawan Pengganti"
            :options="$karyawans->pluck('nama', 'id')" nullable />

        <x-form.textarea name="alasan" label="Alasan" rows="3" />

        <x-form.file-upload name="lampiran" label="Lampiran" />
        {{-- Diisi JS: nama lampiran yang sudah tersimpan --}}
        <div class="form-text mb-3" data-lampiran-info></div>
    </form>
</x-offcanvas.form>