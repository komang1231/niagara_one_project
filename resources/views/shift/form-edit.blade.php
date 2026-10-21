<x-offcanvas.form id="offcanvas-shift-edit" title="Edit Shift" description="Perbarui jam shift." size="md">
    <form id="offcanvas-shift-edit-form" method="POST">
        @csrf
        @method('PUT')

        <x-form.input-code name="kode" label="Kode Shift" readonly inline />

        <x-form.input name="nama" label="Nama Shift" required inline />

        <x-form.color-picker name="warna" label="Warna" required inline />

        <x-form.time-picker name="jam_masuk" label="Jam Masuk" id="edit_jam_masuk" required inline />

        {{-- <x-form.time-picker name="jam_pulang" label="Jam Pulang" id="edit_jam_pulang" required inline /> --}}
        <x-form.time-picker name="jam_pulang" label="Jam Pulang" id="edit_jam_pulang" required inline disabled />

        <small class="text-muted d-block mb-3" id="edit-lintas-hari-hint" style="display:none;">
            ⓘ Shift ini akan tercatat lintas hari (jam pulang lebih kecil dari jam masuk).
        </small>

        <x-form.input name="istirahat_menit" label="Istirahat (menit)" type="number" min="0" required inline />

        <x-form.input name="toleransi_keterlambatan" label="Toleransi (menit)" type="number" min="0" required
            inline />

        <x-form.switch name="status" label="Status Aktif" inline />
    </form>
</x-offcanvas.form>


<script>
    (function () {
        const offcanvasEl = document.getElementById('offcanvas-shift-edit');
        const form = document.getElementById('offcanvas-shift-edit-form');
        const jamMasukInput = form.querySelector('#edit_jam_masuk');
        const jamPulangInput = form.querySelector('#edit_jam_pulang');
        const hint = document.getElementById('edit-lintas-hari-hint');

        const waktuKerja = {{ (float) (\App\Models\GeneralSetting::where('key', 'waktu_kerja')->value('value') ?? 8) }};

        jamPulangInput.disabled = true;

        function hitungJamPulang() {
            if (!jamMasukInput.value) {
                jamPulangInput.value = '';
                hint.style.display = 'none';
                return;
            }

            const [jam, menit] = jamMasukInput.value.split(':').map(Number);
            const totalMenit = (jam * 60) + menit + (waktuKerja * 60);
            const menitDalamHari = totalMenit % (24 * 60);

            const jamPulang = Math.floor(menitDalamHari / 60);
            const menitPulang = menitDalamHari % 60;

            jamPulangInput.value =
                String(jamPulang).padStart(2, '0') + ':' +
                String(menitPulang).padStart(2, '0');

            hint.style.display = totalMenit >= (24 * 60) ? 'block' : 'none';
        }

        offcanvasEl.addEventListener('show.bs.offcanvas', function (e) {
            const trigger = e.relatedTarget;
            form.action = trigger.dataset.updateUrl;

            fetch(trigger.dataset.editUrl)
                .then(res => {
                    if (!res.ok) throw new Error('Gagal mengambil data shift.');
                    return res.json();
                })
                .then(data => {
                    form.querySelector('[name="kode"]').value = data.kode;
                    form.querySelector('[name="nama"]').value = data.nama;
                    jamMasukInput.value = data.jam_masuk?.substring(0, 5) ?? '';

                    form.querySelector('[name="istirahat_menit"]').value = data.istirahat_menit;
                    form.querySelector('[name="toleransi_keterlambatan"]').value =
                        data.toleransi_keterlambatan;

                    const warnaInput = form.querySelector('[name="warna"]');
                    warnaInput.value = data.warna;
                    warnaInput.dispatchEvent(new Event('change', { bubbles: true }));

                    const statusCheckbox = form.querySelector('[name="status"][type="checkbox"]');
                    statusCheckbox.checked = data.status === 'aktif';

                    hitungJamPulang();
                })
                .catch(error => console.error(error));
        });

        jamMasukInput.addEventListener('input', hitungJamPulang);
        jamMasukInput.addEventListener('change', hitungJamPulang);
    })();
</script>