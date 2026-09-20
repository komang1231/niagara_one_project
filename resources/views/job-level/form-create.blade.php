<x-offcanvas.form id="offcanvas-job-level" title="Tambah Job Level" description="Tambahkan job level baru ke sistem." size="md">
    <form id="offcanvas-job-level-form" action="{{ route('job-level.store') }}" method="POST">
        @csrf

        <x-form.input name="kode_preview" label="Kode Job Level" value="{{ $previewKode }}" readonly />

        <x-form.input name="nama" label="Nama Job Level" placeholder="Contoh: Human Resources" required />

        <x-form.switch name="status" label="Status Aktif" :checked="true" />
    </form>
</x-offcanvas.form>