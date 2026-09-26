<x-offcanvas.form id="offcanvas-cuti-edit" title="Edit Cuti" description="Perbarui data cuti." size="md">
    <form id="offcanvas-cuti-edit-form" method="POST" data-edit-form>
        @csrf
        @method('PUT')

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
