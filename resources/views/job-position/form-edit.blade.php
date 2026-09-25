<x-offcanvas.form id="offcanvas-job-position-edit" title="Edit Job Position" description="Perbarui data job position."
    size="md">
    <form id="offcanvas-job-position-edit-form" method="POST" data-edit-form>
        @csrf
        @method('PUT')

        {{-- Kode Job Position --}}
        <x-form.input name="kode" label="Kode Job Position" readonly />

        <div class="row">
            {{-- Nama Job Position --}}
            <div class="col-md-6">
                <x-form.input name="nama" label="Nama Job Position" placeholder="Contoh: Human Resources" required />
            </div>

            {{-- Section --}}
            <div class="col-md-6">
                <x-form.select name="section_id" label="Section" :options="$sections" required />
            </div>
        </div>

        {{-- Status --}}
        <x-form.switch name="status" label="Status Aktif" />
    </form>
</x-offcanvas.form>
