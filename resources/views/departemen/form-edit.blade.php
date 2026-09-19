<x-offcanvas.form id="offcanvas-departemen-edit" title="Edit Departemen" description="Perbarui data departemen." size="md">
    <form id="offcanvas-departemen-edit-form" method="POST" data-edit-form>
        @csrf
        @method('PUT')

        <x-form.input name="kode" label="Kode Departemen" readonly />
        <x-form.input name="nama" label="Nama Departemen" required />
        <x-form.switch name="status" label="Status Aktif" />
    </form>
</x-offcanvas.form>