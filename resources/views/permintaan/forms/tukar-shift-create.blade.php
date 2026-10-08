<x-offcanvas.form id="offcanvas-permintaan-tukar-shift" title="Tambah Permintaan Tukar Shift"
    description="Ajukan pertukaran shift antar karyawan." size="lg">
    <form id="offcanvas-permintaan-tukar-shift-form" action="{{ route('permintaan-tukar-shift.store') }}"
        method="POST" data-reset-on-close>
        @csrf
        <input type="hidden" name="_form" value="offcanvas-permintaan-tukar-shift">

        <x-form.input name="kode_preview" label="Kode Permintaan" value="{{ $previewKodeTukarShift }}" readonly />

        <x-form.input-date name="tanggal_tujuan" label="Tanggal Tukar Shift" required />

        <h6 class="mb-3 mt-4">Pengaju</h6>
                <x-form.select name="shift_pengaju" id="pts_create_shift_pengaju" label="Shift Pengaju"
                    :options="$shifts->pluck('nama', 'id')" nullable required />

        <h6 class="mb-3 mt-4">Pengganti</h6>
        <div class="row">
            <div class="col-md-6">
                <x-form.select name="karyawan_pengganti" id="pts_create_karyawan_pengganti" label="Karyawan Pengganti"
                    :options="$karyawans->pluck('nama', 'id')" nullable required />
            </div>
            <div class="col-md-6">
                <x-form.select name="shift_pengganti" id="pts_create_shift_pengganti" label="Shift Pengganti"
                    :options="$shifts->pluck('nama', 'id')" nullable required />
            </div>
        </div>
    </form>
</x-offcanvas.form>