<x-offcanvas.form id="offcanvas-role" title="Tambah Role" description="Tambahkan role baru ke sistem." size="md">
    <form id="offcanvas-role-form" action="{{ route('role.store') }}" method="POST">
        @csrf

        <x-form.input name="kode_preview" label="Kode Role" value="{{ $previewKode }}" readonly />

        <x-form.input name="nama" label="Nama Role" placeholder="Contoh: Administrator" required />

        <x-form.switch name="status" label="Status Aktif" :checked="true" />
    </form>
</x-offcanvas.form>