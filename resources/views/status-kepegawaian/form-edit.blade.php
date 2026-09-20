<x-offcanvas.form id="offcanvas-status-kepegawaian-edit" title="Edit Status Kepegawaian" description="Perbarui data status kepegawaian." size="md">
    <form id="offcanvas-status-kepegawaian-edit-form" method="POST" data-edit-form>
        @csrf
        @method('PUT')

        <x-form.input name="kode" label="Kode Status" readonly />
        <x-form.input name="nama" label="Nama Status" required />
        <x-form.switch name="status" label="Status Aktif" />
    </form>
</x-offcanvas.form>