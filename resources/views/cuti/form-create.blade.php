<x-offcanvas.form id="offcanvas-cuti" title="Tambah Cuti" description="Tambahkan cuti baru ke sistem." size="md">
    <form id="offcanvas-cuti-form" action="{{ route('cuti.store') }}" method="POST">
        @csrf

        <x-form.input name="kode" label="Kode Cuti" value="{{ $previewKode }}" readonly />

                <x-form.input name="nama" label="Nama Cuti" placeholder="Contoh: Work From Home" required />
   



        <x-form.switch name="status" label="Status Aktif" :checked="true" />
    </form>
</x-offcanvas.form>
