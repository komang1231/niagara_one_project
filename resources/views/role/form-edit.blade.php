<x-offcanvas.form id="offcanvas-role-edit" title="Edit Role" description="Perbarui data role." size="md">
    <form id="offcanvas-role-edit-form" method="POST" data-edit-form>
        @csrf
        @method('PUT')

        <x-form.input name="kode" label="Kode Role" readonly />
        <x-form.input name="nama" label="Nama Role" required />
        <x-form.switch name="status" label="Status Aktif" />
    </form>
</x-offcanvas.form>