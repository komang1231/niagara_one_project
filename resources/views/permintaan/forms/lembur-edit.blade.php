<x-offcanvas.form id="offcanvas-permintaan-lembur-edit" title="Edit Permintaan Lembur"
    description="Perbarui permintaan lembur." size="lg">
    <form id="offcanvas-permintaan-lembur-edit-form" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="_form" value="offcanvas-permintaan-lembur-edit">

        <x-form.input name="kode" label="Kode Permintaan" readonly />

        <x-form.select name="karyawan_id" id="pl_edit_karyawan_id" label="Pemohon"
            :options="$karyawans->pluck('nama', 'id')" nullable required />

        <x-form.input-date name="tanggal_tujuan" label="Tanggal Lembur" required />

        <div class="row">
            <div class="col-md-6">
                <x-form.input name="jam_mulai" label="Jam Mulai" type="time" required />
            </div>
            <div class="col-md-6">
                <x-form.input name="jam_selesai" label="Jam Selesai" type="time" required />
            </div>
        </div>
        <p class="form-text mt-n2 mb-3">Durasi lembur harus antara 4 sampai 10 jam.</p>

        <x-form.input name="pengali" label="Pengali" type="number" step="0.01" min="0" required />

        <x-form.textarea name="alasan" label="Alasan" rows="3" required />
    </form>
</x-offcanvas.form>