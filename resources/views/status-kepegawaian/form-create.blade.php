<x-offcanvas.form id="offcanvas-status-kepegawaian" title="Tambah Status Kepegawaian" description="Tambahkan status kepegawaian baru ke sistem." size="md">
    <form id="offcanvas-status-kepegawaian-form" action="{{ route('status-kepegawaian.store') }}" method="POST">
        @csrf

        <x-form.input name="kode_preview" label="Kode Status" value="{{ $previewKode }}" readonly />

        <x-form.input name="nama" label="Nama Status" placeholder="Contoh: Aktif" required />

        <x-form.switch name="status" label="Status Aktif" :checked="true" />
    </form>
</x-offcanvas.form>