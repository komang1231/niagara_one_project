<x-offcanvas.form id="offcanvas-shift-edit" title="Edit Shift" description="Perbarui jam shift." size="md">
    <form id="offcanvas-shift-edit-form" method="POST">
        @csrf
        @method('PUT')

        <x-form.input name="kode" label="Kode Shift" readonly inline />

        <x-form.input name="nama" label="Nama Shift" required inline />

        <x-form.color-picker name="warna" label="Warna" required inline />

        <x-form.time-picker name="jam_masuk" label="Jam Masuk" id="edit_jam_masuk" required inline />

        <x-form.time-picker name="jam_pulang" label="Jam Pulang" id="edit_jam_pulang" required inline />

        <small class="text-muted d-block mb-3" id="edit-lintas-hari-hint" style="display:none;">
            ⓘ Shift ini akan tercatat lintas hari (jam pulang lebih kecil dari jam masuk).
        </small>

        <x-form.input name="istirahat_menit" label="Istirahat (menit)" type="number" min="0" required inline />

        <x-form.input name="toleransi_keterlambatan" label="Toleransi (menit)" type="number" min="0" required inline />

        <x-form.switch name="status" label="Status Aktif" inline />
    </form>
</x-offcanvas.form>

<script>
    (function () {
        const offcanvasEl = document.getElementById('offcanvas-shift-edit');
        const form = document.getElementById('offcanvas-shift-edit-form');

        offcanvasEl.addEventListener('show.bs.offcanvas', function (e) {
            const trigger = e.relatedTarget;
            form.action = trigger.dataset.updateUrl;

            fetch(trigger.dataset.editUrl)
                .then(res => res.json())
                .then(data => {
                    form.querySelector('[name="kode"]').value = data.kode;
                    form.querySelector('[name="nama"]').value = data.nama;
                    form.querySelector('#edit_jam_masuk').value = data.jam_masuk?.substring(0, 5) ?? '';
                    form.querySelector('#edit_jam_pulang').value = data.jam_pulang?.substring(0, 5) ?? '';
                    form.querySelector('[name="istirahat_menit"]').value = data.istirahat_menit;
                    form.querySelector('[name="toleransi_keterlambatan"]').value = data.toleransi_keterlambatan;

                    const warnaInput = form.querySelector('[name="warna"]');
                    warnaInput.value = data.warna;
                    warnaInput.dispatchEvent(new Event('change', { bubbles: true }));

                    const statusCheckbox = form.querySelector('[name="status"][type="checkbox"]');
                    statusCheckbox.checked = data.status === 'aktif';

                    cekLintasHari();
                });
        });

        function cekLintasHari() {
            const jamMasuk = form.querySelector('#edit_jam_masuk').value;
            const jamPulang = form.querySelector('#edit_jam_pulang').value;
            const hint = document.getElementById('edit-lintas-hari-hint');
            if (!jamMasuk || !jamPulang) return;
            hint.style.display = jamPulang < jamMasuk ? 'block' : 'none';
        }

        form.querySelector('#edit_jam_masuk').addEventListener('change', cekLintasHari);
        form.querySelector('#edit_jam_pulang').addEventListener('change', cekLintasHari);
    })();
</script>