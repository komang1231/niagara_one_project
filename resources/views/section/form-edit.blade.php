<x-offcanvas.form id="offcanvas-section-edit" title="Edit Section" description="Perbarui data section." size="lg">
    <form id="offcanvas-section-edit-form" method="POST" data-edit-form>
        @csrf
        @method('PUT')

        <x-form.input name="kode" label="Kode Section" readonly />
        <div class="row">
            <div class="col-md-6">
                <x-form.input name="nama" label="Nama Section" required />
            </div>
            <div class="col-md-6">
                <x-form.select name="divisi_id" label="Divisi" :options="$divisis" required />
            </div>
        </div>


        <x-form.switch name="status" label="Status Aktif" />
    </form>
</x-offcanvas.form>
