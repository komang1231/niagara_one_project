<x-offcanvas.form id="offcanvas-permintaan-lembur-edit" title="Edit Permintaan Lembur"
    description="Perbarui permintaan lembur." size="lg">
    <form id="offcanvas-permintaan-lembur-edit-form" method="POST" data-edit-form data-lembur-form>
        @csrf
        @method('PUT')
        <input type="hidden" name="_form" value="offcanvas-permintaan-lembur-edit">

        <x-form.input name="kode" label="Kode Permintaan" readonly />

        <x-form.input-date name="tanggal_tujuan" id="pl_edit_tanggal_tujuan" label="Tanggal Lembur" required />

        <div class="row lembur-time">
            <div class="col-md-6">
                <x-form.time-picker name="jam_mulai" id="pl_edit_jam_mulai" label="Jam Mulai" :step="15" required />
            </div>
            <div class="col-md-6">
                <x-form.time-picker name="jam_selesai" id="pl_edit_jam_selesai" label="Jam Selesai" :step="15" required />
            </div>
        </div>
        <p class="lembur-durasi" data-lembur-info>Durasi lembur 4 sampai 10 jam. Jam selesai lebih kecil dari jam mulai = selesai di hari berikutnya.</p>

        <x-form.input name="pengali" label="Pengali" type="number" step="0.01" min="0" required />

        <x-form.textarea name="alasan" label="Alasan" rows="3" required />
    </form>
</x-offcanvas.form>