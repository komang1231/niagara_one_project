<x-offcanvas.form id="offcanvas-permintaan-tukar-shift-edit" title="Edit Permintaan Tukar Shift"
    description="Perbarui permintaan tukar shift." size="lg">
    <form id="offcanvas-permintaan-tukar-shift-edit-form" method="POST" data-edit-form data-chained>
        @csrf
        @method('PUT')
        <input type="hidden" name="_form" value="offcanvas-permintaan-tukar-shift-edit">

        <x-form.input name="kode" label="Kode Permintaan" readonly />

        <x-form.input-date name="tanggal_tujuan" label="Tanggal Tukar Shift" required />

        <h6 class="mb-3 mt-4">Pengaju</h6>
        <div class="row">
            <div class="col-md-6">
                <x-form.select name="karyawan_pengaju" id="pts_edit_karyawan_pengaju" label="Karyawan Pengaju"
                    :options="$karyawans->pluck('nama', 'id')" nullable required />
            </div>
            <div class="col-md-6">
                <x-form.select name="shift_pengaju" id="pts_edit_shift_pengaju" label="Shift Pengaju"
                    :options="$shifts->pluck('nama', 'id')" nullable required />
            </div>
        </div>

        <h6 class="mb-3 mt-4">Pengganti</h6>
        <div class="row">
            <div class="col-md-6">
                <x-form.select name="karyawan_pengganti" id="pts_edit_karyawan_pengganti" label="Karyawan Pengganti"
                    :options="$karyawans->pluck('nama', 'id')" nullable required />
            </div>
            <div class="col-md-6">
                <x-form.select name="shift_pengganti" id="pts_edit_shift_pengganti" label="Shift Pengganti"
                    :options="$shifts->pluck('nama', 'id')" nullable required />
            </div>
        </div>
    </form>
</x-offcanvas.form>