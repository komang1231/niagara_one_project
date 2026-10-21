<x-offcanvas.form id="offcanvas-divisi-edit" title="Edit Divisi" description="Perbarui data divisi." size="lg">
    <form id="offcanvas-divisi-edit-form" method="POST" data-edit-form>
        @csrf
        @method('PUT')

        <x-form.input-code name="kode" label="Kode Divisi" readonly />

        <div class="row">
            <div class="col-md-6">
                <x-form.input name="nama" label="Nama Divisi" required />
            </div>
            <div class="col-md-6">
                <x-form.select name="departemen_id" id="divisi_edit_departemen_id" label="Departemen" :options="$departemens"
                    nullable />
            </div>
        </div>

        <x-form.switch name="status" label="Status Aktif" />
    </form>
</x-offcanvas.form>
