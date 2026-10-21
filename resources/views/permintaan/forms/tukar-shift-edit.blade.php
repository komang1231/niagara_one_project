<x-offcanvas.form id="offcanvas-permintaan-tukar-shift-edit" title="Edit Permintaan Tukar Shift"
    description="Perbarui permintaan tukar shift." size="lg">

    <form id="offcanvas-permintaan-tukar-shift-edit-form"
        method="POST"
        data-edit-form
        data-chained>

        @csrf
        @method('PUT')

        <input type="hidden" name="_form" value="offcanvas-permintaan-tukar-shift-edit">

        <x-form.input-code name="kode" label="Kode Permintaan" readonly />

        <x-form.input-date
            name="tanggal_tujuan"
            id="pts_edit_tanggal_tujuan"
            label="Tanggal Tukar Shift"
            required
        />

        <h6 class="mb-3 mt-4">Pengaju</h6>

        <x-form.input
            name="shift_pengaju_display"
            id="pts_edit_shift_pengaju_display"
            label="Shift Pengaju"
            value=""
            placeholder="Pilih tanggal dan karyawan pengganti"
            readonly
        />

        <input type="hidden" name="shift_pengaju" id="pts_edit_shift_pengaju">

        <h6 class="mb-3 mt-4">Pengganti</h6>

        <div class="row">
            <div class="col-md-6">
                <x-form.select
                    name="karyawan_pengganti"
                    id="pts_edit_karyawan_pengganti"
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
                    id="pts_edit_shift_pengganti_display"
                    label="Shift Pengganti"
                    value=""
                    placeholder="Pilih tanggal dan karyawan pengganti"
                    readonly
                />

                <input type="hidden" name="shift_pengganti" id="pts_edit_shift_pengganti">
            </div>
        </div>

    </form>
</x-offcanvas.form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tanggalInput = document.getElementById('pts_edit_tanggal_tujuan');
    const karyawanPenggantiInput = document.getElementById('pts_edit_karyawan_pengganti');

    const shiftPengajuDisplay = document.getElementById('pts_edit_shift_pengaju_display');
    const shiftPenggantiDisplay = document.getElementById('pts_edit_shift_pengganti_display');

    const shiftPengajuInput = document.getElementById('pts_edit_shift_pengaju');
    const shiftPenggantiInput = document.getElementById('pts_edit_shift_pengganti');

    const karyawanPengajuId = {{ auth()->user()->karyawan_id ?? 'null' }};

    const jadwalKaryawans = {!! $jadwalKaryawansJson !!};
    const shifts = {!! $shiftsJson !!};

    function resetShift() {
        shiftPengajuDisplay.value = '';
        shiftPenggantiDisplay.value = '';
        shiftPengajuInput.value = '';
        shiftPenggantiInput.value = '';
    }

    function formatTanggal(tanggal) {
        if (!tanggal) return null;

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

        if (bagian.length !== 3 || !bulan[bagian[1]]) {
            return null;
        }

        return `${bagian[2]}-${bulan[bagian[1]]}-${bagian[0].padStart(2, '0')}`;
    }

    function updateShift() {
        resetShift();

        const tanggal = formatTanggal(tanggalInput?.value);
        const karyawanPengganti = karyawanPenggantiInput?.value;

        if (!tanggal || !karyawanPengganti || !karyawanPengajuId) {
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

        // Shift Pengaju mengikuti shift awal karyawan pengganti.
        shiftPengajuInput.value = jadwalPengganti.shift_id;
        shiftPengajuDisplay.value = shifts[jadwalPengganti.shift_id] ?? '';

        // Shift Pengganti mengikuti shift awal pengaju.
        shiftPenggantiInput.value = jadwalPengaju.shift_id;
        shiftPenggantiDisplay.value = shifts[jadwalPengaju.shift_id] ?? '';
    }

    function updateShiftSetelahTanggal() {
        setTimeout(updateShift, 0);
    }

    karyawanPenggantiInput?.addEventListener('change', updateShift);
    tanggalInput?.addEventListener('date:change', updateShiftSetelahTanggal);

    // Mendukung pemuatan data edit secara dinamis oleh offcanvas.
    const form = document.getElementById('offcanvas-permintaan-tukar-shift-edit-form');

    form?.addEventListener('change', function (event) {
        if (event.target === tanggalInput || event.target === karyawanPenggantiInput) {
            updateShift();
        }
    });

    // Saat form edit diisi oleh mekanisme edit-form, hitung shift setelah nilainya tersedia.
    form?.addEventListener('edit:loaded', updateShiftSetelahTanggal);
});
</script>