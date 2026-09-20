<x-offcanvas.form id="offcanvas-divisi" title="Tambah Divisi" description="Tambahkan divisi baru ke sistem." size="lg">
    <form id="offcanvas-divisi-form" action="{{ route('divisi.store') }}" method="POST">
        @csrf

          <x-form.input name="kode_preview" label="Kode Divisi" value="{{ $previewKode }}" readonly />

        <div class="row">
            <div class="col-md-6">
                <x-form.input name="nama" label="Nama Divisi" placeholder="Contoh: Human Resources" required />
            </div>

            <div class="col-md-6">
                <x-form.select 
                    name="departemen_id" 
                    label="Departemen" 
                    :options="$departemens" 
                    :selected="old('departemen_id', $divisi->departemen_id ?? null)" 
                    nullable 
                />
            </div>
        </div>

        <x-form.switch name="status" label="Status Aktif" :checked="true" />
    </form>
</x-offcanvas.form>