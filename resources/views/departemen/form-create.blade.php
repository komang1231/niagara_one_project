<x-offcanvas.form id="offcanvas-departemen" title="Tambah Departemen" description="Tambahkan departemen baru ke sistem." size="md">
    <form id="offcanvas-departemen-form" action="{{ route('departemen.store') }}" method="POST">
        @csrf

        <x-form.input name="kode_preview" label="Kode Departemen" value="{{ $previewKode }}" readonly />

        <x-form.input name="nama" label="Nama Departemen" placeholder="Contoh: Human Resources" required />

        <x-form.switch name="status" label="Status Aktif" :checked="true" />
    </form>
</x-offcanvas.form>