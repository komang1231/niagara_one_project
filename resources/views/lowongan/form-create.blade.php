<x-offcanvas.form id="offcanvas-lowongan" title="Tambah Lowongan"
    description="Tambahkan lowongan pekerjaan baru ke sistem." size="xl">
    <form id="offcanvas-lowongan-form" action="{{ route('lowongan.store') }}" method="POST">
        @csrf

        <x-form.input name="kode_preview" label="Kode Lowongan" value="{{ $previewKode }}" readonly />

        <h6 class="mb-3 mt-4">Informasi Lowongan</h6>
        <div class="row">
            <div class="col-md-6">
                <x-form.input name="judul" label="Judul Lowongan" placeholder="Contoh: Staff Finance" required />
            </div>
            <div class="col-md-6">
                <x-form.select name="permintaan_karyawan_id" id="create_permintaan_karyawan_id"
                    label="Permintaan Karyawan" :options="$permintaanKaryawans->pluck('nama', 'id')" nullable />
            </div>
        </div>

        <h6 class="mb-3 mt-4">Penempatan</h6>
        <div class="row">
            <div class="col-md-4">
                <x-form.select name="cabang_kantor_id" id="create_cabang_kantor_id" label="Cabang Kantor"
                    :options="$cabangKantors->pluck('nama', 'id')" nullable required />
            </div>
            <div class="col-md-4">
                <x-form.select name="departemen_id" id="create_departemen_id" label="Departemen"
                    :options="$departemens->pluck('nama', 'id')" nullable required />
            </div>
            <div class="col-md-4">
                <x-form.select name="divisi_id" id="create_divisi_id" label="Divisi" :options="[]" nullable
                    disabled />
            </div>
            <div class="col-md-4">
                <x-form.select name="section_id" id="create_section_id" label="Section" :options="[]" nullable
                    disabled />
            </div>
            <div class="col-md-4">
                <x-form.select name="job_position_id" id="create_job_position_id" label="Job Position"
                    :options="[]" nullable disabled />
            </div>
            <div class="col-md-4">
                <x-form.select name="job_level_id" id="create_job_level_id" label="Job Level"
                    :options="$jobLevels->pluck('nama', 'id')" nullable required />
            </div>
        </div>

        <h6 class="mb-3 mt-4">Detail Lowongan</h6>
        <div class="row">
            <div class="col-md-4">
                <x-form.input name="kuota" label="Kuota" type="number" min="1" placeholder="Contoh: 3"
                    required />
            </div>
            <div class="col-md-4">
                <x-form.input-date name="tanggal_buka" label="Tanggal Buka" required />
            </div>
            <div class="col-md-4">
                <x-form.input-date name="tanggal_tutup" label="Tanggal Tutup" after="tanggal_buka" required />
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <x-form.currency name="min_gaji" label="Gaji Minimum" required />
            </div>
            <div class="col-md-6">
                <x-form.currency name="max_gaji" label="Gaji Maksimum" required />
            </div>
        </div>

        <x-form.rich-text-editor name="kualifikasi" label="Kualifikasi" placeholder="Tulis kualifikasi kandidat..." />
        
        <x-form.rich-text-editor name="deskripsxi" label="Deskripsi Pekerjaan" placeholder="Tulis deskripsi pekerjaan..." />

        <x-form.switch name="status" label="Status Aktif" :checked="true" />
    </form>
</x-offcanvas.form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('offcanvas-lowongan-form');
        if (!form) return;

        // jQuery opsional: kalau ada dipakai supaya select2 ikut ke-update,
        // kalau tidak ada, jatuh ke event native (script gak mati).
        const $ = window.jQuery;

        const departemenSelect = form.querySelector('#create_departemen_id');
        const divisiSelect = form.querySelector('#create_divisi_id');
        const sectionSelect = form.querySelector('#create_section_id');
        const jobPositionSelect = form.querySelector('#create_job_position_id');

        const routes = {
            getDivisi: "{{ route('lowongan.get-divisi', ['departemen' => '__ID__']) }}",
            getSection: "{{ route('lowongan.get-section', ['divisi' => '__ID__']) }}",
            getJobPosition: "{{ route('lowongan.get-job-position', ['section' => '__ID__']) }}",
        };

        // "Tiket" per select: kalau ada fetch baru / reset, respons fetch lama dibuang
        const tickets = {};
        const newTicket = (select) => (tickets[select.id] = (tickets[select.id] || 0) + 1);

        function onChange(el, handler) {
            if ($) $(el).on('change', handler);
            else el.addEventListener('change', handler);
        }

        function setValue(el, value) {
            if ($) {
                $(el).val(value).trigger('change');
            } else {
                el.value = value;
                el.dispatchEvent(new Event('change'));
            }
        }

        function resetSelect(select) {
            newTicket(select); // batalkan fetch yang masih jalan
            select.innerHTML = '<option value="">-- Pilih --</option>';
            select.disabled = true;
            setValue(select, '');
        }

            function fillSelect(select, data) {
                select.innerHTML = '<option value="">-- Pilih --</option>';
                data.forEach((item) => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.nama;
                    select.appendChild(option);
                });
                select.disabled = data.length === 0;
                setValue(select, '');
            }

        function fetchChildren(url, parentId, targetSelect) {
            const ticket = newTicket(targetSelect);

            fetch(url.replace('__ID__', parentId), { headers: { Accept: 'application/json' } })
                .then((res) => {
                    if (!res.ok) throw new Error(`HTTP ${res.status}`);
                    return res.json();
                })
                .then((data) => {
                    if (ticket !== tickets[targetSelect.id]) return; 
                    fillSelect(targetSelect, data);
                })
                .catch((err) => console.error('Gagal ambil data chained dropdown:', err));
        }

        // Departemen -> Divisi
        onChange(departemenSelect, function () {
            resetSelect(divisiSelect);
            resetSelect(sectionSelect);
            resetSelect(jobPositionSelect);
            if (!this.value) return;
            fetchChildren(routes.getDivisi, this.value, divisiSelect);
        });

        // Divisi -> Section
        onChange(divisiSelect, function () {
            resetSelect(sectionSelect);
            resetSelect(jobPositionSelect);
            if (!this.value) return;
            fetchChildren(routes.getSection, this.value, sectionSelect);
        });

        // Section -> Job Position
        onChange(sectionSelect, function () {
            resetSelect(jobPositionSelect);
            if (!this.value) return;
            fetchChildren(routes.getJobPosition, this.value, jobPositionSelect);
        });
    });
</script>