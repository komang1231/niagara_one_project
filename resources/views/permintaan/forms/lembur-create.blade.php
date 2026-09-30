<x-offcanvas.form id="offcanvas-permintaan-lembur" title="Tambah Permintaan Lembur"
    description="Ajukan lembur karyawan." size="lg">
    <form id="offcanvas-permintaan-lembur-form" action="{{ route('permintaan-lembur.store') }}" method="POST"
        data-reset-on-close>
        @csrf
        <input type="hidden" name="_form" value="offcanvas-permintaan-lembur">

        <x-form.input name="kode_preview" label="Kode Permintaan" value="{{ $previewKodeLembur }}" readonly />

        <x-form.select name="karyawan_id" id="pl_create_karyawan_id" label="Pemohon"
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

        <x-form.input name="pengali" label="Pengali" type="number" step="0.01" min="0" placeholder="Contoh: 1.5" required />

        <x-form.textarea name="alasan" label="Alasan" rows="3" placeholder="Tulis alasan lembur..." required />
    </form>
</x-offcanvas.form>