<x-offcanvas.form id="offcanvas-tambah-shift" title="Tambah Shift" description="Tambahkan jam shift baru ke sistem."
    size="md">
    <form id="offcanvas-tambah-shift-form" action="{{ route('shift.store') }}" method="POST">
        @csrf

        <x-form.input name="kode_preview" label="Kode Shift" value="{{ $previewKode }}" readonly inline />

        <x-form.input name="nama" label="Nama Shift" placeholder="Contoh: Shift Pagi" required inline />

        <x-form.color-picker name="warna" label="Warna" value="green" required inline />

        <x-form.time-picker name="jam_masuk" label="Jam Masuk" required inline />

        <x-form.time-picker name="jam_pulang" label="Jam Pulang" required inline />

        {{-- Info pasif — lintas_hari dihitung otomatis di BE --}}
        <small class="text-muted d-block mb-3" id="lintas-hari-hint" style="display:none;">
            ⓘ Shift ini akan tercatat lintas hari (jam pulang lebih kecil dari jam masuk).
        </small>

        <x-form.input name="istirahat_menit" label="Istirahat (menit)" type="number" min="0" value="60" required inline />

        <x-form.input name="toleransi_keterlambatan" label="Toleransi (menit)" type="number" min="0" value="0"
            required inline />

        <x-form.switch name="status" label="Status Aktif" :checked="true" inline />
    </form>
</x-offcanvas.form>

<script>
    (function () {
        const jamMasuk = document.getElementById('jam_masuk');
        const jamPulang = document.getElementById('jam_pulang');
        const hint = document.getElementById('lintas-hari-hint');

        function cekLintasHari() {
            if (!jamMasuk.value || !jamPulang.value) return;
            hint.style.display = jamPulang.value < jamMasuk.value ? 'block' : 'none';
        }

        jamMasuk.addEventListener('change', cekLintasHari);
        jamPulang.addEventListener('change', cekLintasHari);
    })();
</script>