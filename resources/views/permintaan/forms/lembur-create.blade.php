<x-offcanvas.form id="offcanvas-permintaan-lembur" title="Tambah Permintaan Lembur"
    description="Ajukan lembur karyawan." size="lg">
    <form id="offcanvas-permintaan-lembur-form" action="{{ route('permintaan-lembur.store') }}" method="POST"
        data-lembur-form data-reset-on-close>
        @csrf
        <input type="hidden" name="_form" value="offcanvas-permintaan-lembur">

        <x-form.input name="kode_preview" label="Kode Permintaan" value="{{ $previewKodeLembur }}" readonly />

        <x-form.input-date name="tanggal_tujuan" id="pl_create_tanggal_tujuan" label="Tanggal Lembur" required />

        <div class="row lembur-time">
            <div class="col-md-6">
                <x-form.time-picker name="jam_mulai" id="pl_create_jam_mulai" label="Jam Mulai" :step="15" required />
            </div>
            <div class="col-md-6">
                <x-form.time-picker name="jam_selesai" id="pl_create_jam_selesai" label="Jam Selesai" :step="15" required />
            </div>
        </div>
        <p class="lembur-durasi" data-lembur-info>Durasi lembur 4 sampai 10 jam. Jam selesai lebih kecil dari jam mulai = selesai di hari berikutnya.</p>

        <x-form.input name="pengali" label="Pengali" type="number" step="0.01" min="0" placeholder="Contoh: 1.5" required />

        <x-form.textarea name="alasan" label="Alasan" rows="3" placeholder="Tulis alasan lembur..." required />
    </form>
</x-offcanvas.form>