{{-- <x-offcanvas.form id="offcanvas-permintaan-tukar-shift" title="Tambah Permintaan Tukar Shift"
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
</x-offcanvas.form> --}}

<x-offcanvas.form id="offcanvas-permintaan-tukar-shift" title="Tambah Permintaan Tukar Shift"
    description="Ajukan pertukaran shift antar karyawan." size="lg">

    <form id="offcanvas-permintaan-tukar-shift-form"
        action="{{ route('permintaan-tukar-shift.store') }}"
        method="POST"
        data-reset-on-close>

        @csrf

        <input type="hidden" name="_form" value="offcanvas-permintaan-tukar-shift">

        <x-form.input
            name="kode_preview"
            label="Kode Permintaan"
            value="{{ $previewKodeTukarShift }}"
            readonly
        />

        <x-form.input-date
            name="tanggal_tujuan"
            id="pts_create_tanggal_tujuan"
            label="Tanggal Tukar Shift"
            required
        />

        <h6 class="mb-3 mt-4">Pengaju</h6>

        <x-form.input
            name="shift_pengaju_display"
            id="pts_create_shift_pengaju_display"
            label="Shift Pengaju"
            value=""
            placeholder="Pilih tanggal dan karyawan pengganti"
            readonly
        />

        <input type="hidden" name="shift_pengaju" id="pts_create_shift_pengaju">

        <h6 class="mb-3 mt-4">Pengganti</h6>

        <div class="row">
            <div class="col-md-6">
                <x-form.select
                    name="karyawan_pengganti"
                    id="pts_create_karyawan_pengganti"
                    label="Karyawan Pengganti"
                    :options="$karyawans
                        ->where('id', '!=', auth()->user()->karyawan_id)
                        ->pluck('nama', 'id')"
                    nullable
                    required
                />
            </div>

            <div class="col-md-6">
                <x-form.input
                    name="shift_pengganti_display"
                    id="pts_create_shift_pengganti_display"
                    label="Shift Pengganti"
                    value=""
                    placeholder="Pilih tanggal dan karyawan pengganti"
                    readonly
                />

                <input type="hidden" name="shift_pengganti" id="pts_create_shift_pengganti">
            </div>
        </div>

    </form>

</x-offcanvas.form>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tanggalInput = document.getElementById('pts_create_tanggal_tujuan');
        const karyawanPenggantiInput = document.getElementById('pts_create_karyawan_pengganti');

        const shiftPengajuDisplay = document.getElementById('pts_create_shift_pengaju_display');
        const shiftPenggantiDisplay = document.getElementById('pts_create_shift_pengganti_display');

        const shiftPengajuInput = document.getElementById('pts_create_shift_pengaju');
        const shiftPenggantiInput = document.getElementById('pts_create_shift_pengganti');

        const karyawanPengajuId = {{ auth()->user()->karyawan_id }};

        const jadwalKaryawans = {!! $jadwalKaryawansJson !!};
        const shifts = {!! $shiftsJson !!};

        function resetShift() {
            shiftPengajuDisplay.value = '';
            shiftPenggantiDisplay.value = '';

            shiftPengajuInput.value = '';
            shiftPenggantiInput.value = '';
        }

        function formatTanggal(tanggal) {
            if (!tanggal) {
                return null;
            }

            tanggal = tanggal.trim();

            if (/^\d{4}-\d{2}-\d{2}$/.test(tanggal)) {
                return tanggal;
            }

            const bulan = {
                Januari: '01',
                Februari: '02',
                Maret: '03',
                April: '04',
                Mei: '05',
                Juni: '06',
                Juli: '07',
                Agustus: '08',
                September: '09',
                Oktober: '10',
                November: '11',
                Desember: '12'
            };

            const bagian = tanggal.split(/\s+/);

            if (bagian.length !== 3) {
                return null;
            }

            const hari = bagian[0].padStart(2, '0');
            const namaBulan = bagian[1];
            const tahun = bagian[2];

            if (!bulan[namaBulan]) {
                return null;
            }

            return `${tahun}-${bulan[namaBulan]}-${hari}`;
        }

        function updateShift() {
            resetShift();

            const tanggal = formatTanggal(tanggalInput?.value);
            const karyawanPengganti = karyawanPenggantiInput?.value;

            if (!tanggal || !karyawanPengganti) {
                return;
            }

            const jadwalPengaju = jadwalKaryawans.find(jadwal =>
                String(jadwal.karyawan_id) === String(karyawanPengajuId) &&
                String(jadwal.tanggal).substring(0, 10) === tanggal
            );

            const jadwalPengganti = jadwalKaryawans.find(jadwal =>
                String(jadwal.karyawan_id) === String(karyawanPengganti) &&
                String(jadwal.tanggal).substring(0, 10) === tanggal
            );

            if (!jadwalPengaju || !jadwalPengganti) {
                return;
            }

            // Shift Pengaju = shift milik karyawan pengganti
            shiftPengajuInput.value = jadwalPengganti.shift_id;
            shiftPengajuDisplay.value = shifts[jadwalPengganti.shift_id] ?? '';

            // Shift Pengganti = shift milik pengaju
            shiftPenggantiInput.value = jadwalPengaju.shift_id;
            shiftPenggantiDisplay.value = shifts[jadwalPengaju.shift_id] ?? '';
        }

        function updateShiftSetelahTanggal() {
            setTimeout(updateShift, 0);
        }

        // Saat karyawan pengganti berubah
        karyawanPenggantiInput?.addEventListener('change', updateShift);

        // Saat tanggal dipilih dari Air Datepicker
        tanggalInput?.addEventListener('date:change', updateShiftSetelahTanggal);

        // Reset form
        const form = document.getElementById('offcanvas-permintaan-tukar-shift-form');

        form?.addEventListener('reset', function () {
            setTimeout(resetShift, 0);
        });
    });
</script>