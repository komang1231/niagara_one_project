<x-offcanvas.form id="offcanvas-job-level-edit" title="Edit Job Level" description="Perbarui data job level." size="md">
    <form id="offcanvas-job-level-edit-form" method="POST" data-edit-form>
        @csrf
        @method('PUT')

        <x-form.input name="kode" label="Kode Job Level" readonly />
        <x-form.input name="nama" label="Nama Job Level" required />
        <x-form.switch name="status" label="Status Aktif" />
    </form>
</x-offcanvas.form>