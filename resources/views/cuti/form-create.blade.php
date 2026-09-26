<x-offcanvas.form id="offcanvas-cuti" title="Tambah Cuti" description="Tambahkan cuti baru ke sistem." size="md">
    <form id="offcanvas-cuti-form" action="{{ route('cuti.store') }}" method="POST">
        @csrf

        <x-form.input name="kode" label="Kode Cuti" value="{{ $previewKode }}" readonly />

        <div class="row">
            <div class="col-md-6">
                <x-form.input name="nama" label="Nama Cuti" placeholder="Contoh: Work From Home" required />
            </div>

            <div class="col-md-6">
                <x-form.input-stepper name="kuota_hari_default" label="Jumlah Hari Cuti" value="0" :min="0"
                    :max="30" required />
            </div>
        </div>


        <x-form.switch name="status" label="Status Aktif" :checked="true" />
    </form>
</x-offcanvas.form>
