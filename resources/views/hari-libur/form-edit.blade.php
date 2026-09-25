<x-offcanvas.form id="offcanvas-hari-libur-edit" title="Edit Hari Libur" description="Perbarui data hari libur."
    size="lg">
    <form id="offcanvas-hari-libur-edit-form" method="POST" data-edit-form>
        @csrf
         @method('PUT')

        <x-form.input name="kode" label="Kode Hari Libur" readonly />

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
