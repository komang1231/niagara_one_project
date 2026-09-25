<x-offcanvas.form id="offcanvas-hari-libur" title="Tambah Hari Libur" description="Tambahkan hari libur baru ke sistem."
    size="lg">
    <form id="offcanvas-hari-libur-form" action="{{ route('hari-libur.store') }}" method="POST">
        @csrf

        <x-form.input name="kode_preview" label="Kode Hari Libur" value="{{ $previewKode }}" readonly />

        <div class="row">
            <div class="col-md-6">
                <x-form.input name="nama" label="Nama Hari Libur" placeholder="Contoh: Tahun Baru" required />
            </div>

            <div class="col-md-6">
                <x-form.input-date name="tanggal" label="Tanggal Hari Libur" required />
            </div>
        </div>

        <x-form.switch name="status" label="Status Aktif" :checked="true" />
    </form>
</x-offcanvas.form>
