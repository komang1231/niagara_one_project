<x-offcanvas.form id="offcanvas-job-position" title="Tambah Job Position"
    description="Tambahkan job position baru ke sistem." size="md">
    <form id="offcanvas-job-position-form" action="{{ route('job-position.store') }}" method="POST">
        @csrf

        <x-form.input name="kode_preview" label="Kode Job Position" value="{{ $previewKode }}" readonly />

        <div class="row">
            <div class="col-md-6">
                <x-form.input name="nama" label="Nama Job Position" placeholder="Contoh: Human Resources" required />
            </div>

            <div class="col-md-6">
                <x-form.select name="section_id" label="Section" :options="$sections" :selected="old('section_id', $jobPosition->section_id ?? null)" nullable/>
            </div>
        </div>

        <x-form.switch name="status" label="Status Aktif" :checked="true" />
    </form>
</x-offcanvas.form>
