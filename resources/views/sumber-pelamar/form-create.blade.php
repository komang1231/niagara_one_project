<x-offcanvas.form id="offcanvas-sumber-pelamar" title="Tambah Sumber Pelamar" description="Tambahkan sumber pelamar baru ke sistem." size="md">
    <form id="offcanvas-sumber-pelamar-form" action="{{ route('sumber-pelamar.store') }}" method="POST">
        @csrf

        <x-form.input name="kode_preview" label="Kode Sumber Pelamar" value="{{ $previewKode }}" readonly />

        <x-form.input name="nama" label="Nama Sumber Pelamar" placeholder="Contoh: LinkedIn" required />

        <x-form.switch name="status" label="Status Aktif" :checked="true" />
    </form>
</x-offcanvas.form>