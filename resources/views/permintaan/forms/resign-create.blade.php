<x-offcanvas.form id="offcanvas-permintaan-resign" title="Tambah Permintaan Resign"
    description="Ajukan pengunduran diri karyawan." size="lg">
    <form id="offcanvas-permintaan-resign-form" action="{{ route('permintaan-resign.store') }}" method="POST"
        data-reset-on-close>
        @csrf
        <input type="hidden" name="_form" value="offcanvas-permintaan-resign">

        <x-form.input name="kode_preview" label="Kode Permintaan" value="{{ $previewKodeResign }}" readonly />

        <x-form.select name="karyawan_id" id="pr_create_karyawan_id" label="Pemohon"
            :options="$karyawans->pluck('nama', 'id')" nullable required />

        <x-form.input-date name="tanggal_efektif" label="Tanggal Efektif Resign" required />

        <x-form.textarea name="alasan" label="Alasan" rows="4" maxlength="255"
            placeholder="Tulis alasan resign (maks. 255 karakter)..." required />
    </form>
</x-offcanvas.form>