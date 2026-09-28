<x-offcanvas.form id="offcanvas-cuti-edit" title="Edit Cuti" description="Perbarui data cuti." size="md">
    <form id="offcanvas-cuti-edit-form" method="POST" data-edit-form>
        @csrf
        @method('PUT')

        <x-form.input name="kode" label="Kode Cuti" value="{{ $previewKode }}" readonly />

                <x-form.input name="nama" label="Nama Cuti" placeholder="Contoh: Work From Home" required />
    


        <x-form.switch name="status" label="Status Aktif" :checked="true" />
    </form>
</x-offcanvas.form>
