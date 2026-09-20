<x-offcanvas.form id="offcanvas-section" title="Tambah Section" description="Tambahkan section baru ke sistem." size="lg">
    <form id="offcanvas-section-form" action="{{ route('section.store') }}" method="POST">
        @csrf

        {{-- <x-form.input name="kode_preview" label="Kode Section" value="{{ $previewKode }}" readonly /> --}}

        <div class="row">
            <div class="col-md-6">
                <x-form.input name="nama" label="Nama Section" placeholder="Contoh: Human Resources" required />
            </div>

            <div class="col-md-6">
                <x-form.select name="divisi_id" label="Divisi" :options="$divisis" :selected="$section->divisi_id ?? null" nullable />
            </div>
        </div>

        <x-form.switch name="status" label="Status Aktif" :checked="true" />
    </form>
</x-offcanvas.form>