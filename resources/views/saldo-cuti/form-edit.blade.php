<x-offcanvas.form id="offcanvas-saldo-cuti-edit" title="Edit Saldo Cuti"
    description="Karyawan, jenis cuti, dan tahun tidak dapat diubah." size="md">
    <form id="offcanvas-saldo-cuti-edit-form" method="POST" data-edit-form novalidate>
        @csrf
        @method('PUT')

        {{-- Penanda form mana yang disubmit (dipakai untuk membuka lagi offcanvas kalau validasi gagal) --}}
        <input type="hidden" name="_form" value="offcanvas-saldo-cuti-edit">

        {{-- Identitas saldo: hanya ditampilkan --}}
        <x-form.select name="karyawan_id" id="sc_edit_karyawan_id" label="Karyawan" :options="$karyawanOptions" nullable
            disabled />

        <x-form.select name="cuti_id" id="sc_edit_cuti_id" label="Jenis Cuti" :options="$cutiOptions" nullable />

        <x-form.input name="tahun" id="sc_edit_tahun" label="Tahun" readonly />

        <div class="row">
            <div class="col-md-6">
                <x-form.input-stepper name="saldo" label="Hak Cuti (hari)" :value="0" :min="0" :max="365"
                    required />
            </div>

            <div class="col-md-6">
                <x-form.input-stepper name="terpakai" label="Terpakai (hari)" :value="0" :min="0" :max="365"
                    required />
            </div>
        </div>

        <p class="text-muted small mb-0">Sisa cuti = Hak Cuti − Terpakai.</p>
    </form>
</x-offcanvas.form>
