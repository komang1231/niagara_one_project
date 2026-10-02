<x-offcanvas.form id="offcanvas-permintaan-resign-edit" title="Edit Permintaan Resign"
    description="Perbarui permintaan resign." size="lg">
    <form id="offcanvas-permintaan-resign-edit-form" method="POST" data-edit-form data-chained>
        @csrf
        @method('PUT')
        <input type="hidden" name="_form" value="offcanvas-permintaan-resign-edit">

        <x-form.input name="kode" label="Kode Permintaan" readonly />

        <x-form.select name="karyawan_id" id="pr_edit_karyawan_id" label="Pemohon"
            :options="$karyawans->pluck('nama', 'id')" nullable required />

        <x-form.input-date name="tanggal_efektif" label="Tanggal Efektif Resign" required />

        <x-form.textarea name="alasan" label="Alasan" rows="4" maxlength="255" required />
    </form>
</x-offcanvas.form>