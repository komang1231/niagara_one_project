<x-offcanvas.form id="offcanvas-sumber-pelamar-edit" title="Edit Sumber Pelamar" description="Perbarui data sumber pelamar." size="md">
    <form id="offcanvas-sumber-pelamar-edit-form" method="POST" data-edit-form>
        @csrf
        @method('PUT')

        <x-form.input name="kode" label="Kode Sumber Pelamar" readonly />
        <x-form.input name="nama" label="Nama Sumber Pelamar" required />
        <x-form.switch name="status" label="Status Aktif" />
    </form>
</x-offcanvas.form>