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
                <x-form.select name="departemen_id" id="create_departemen_id" label="Departemen" :options="$departemens->pluck('nama', 'id')"
                    nullable required />
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
                <x-form.select name="job_position_id" id="create_job_position_id" label="Job Position" :options="[]"
                    nullable disabled />
            </div>
            <div class="col-md-4">
                <x-form.select name="job_level_id" id="create_job_level_id" label="Job Level" :options="$jobLevels->pluck('nama', 'id')" nullable
                    required />
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
                <x-form.input-date name="tanggal_tutup" label="Tanggal Tutup" required />
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

        <x-form.textarea name="deskripsi" label="Deskripsi Pekerjaan" rows="4"
            placeholder="Tulis deskripsi pekerjaan..." required />

        <x-form.switch name="status" label="Status Aktif" :checked="true" />
    </form>
</x-offcanvas.form>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('offcanvas-lowongan-form');

    if (!form) return;

    const $ = window.jQuery;

    if (!$) {
        console.error('jQuery belum tersedia.');
        return;
    }


    // =========================================================
    // SELECT
    // =========================================================

    const departemenSelect  = form.querySelector('#create_departemen_id');
    const divisiSelect      = form.querySelector('#create_divisi_id');
    const sectionSelect     = form.querySelector('#create_section_id');
    const jobPositionSelect = form.querySelector('#create_job_position_id');


    // =========================================================
    // ROUTES
    // =========================================================

    const routes = {
        getDivisi: "{{ route('lowongan.get-divisi', ['departemen' => '__ID__']) }}",
        getSection: "{{ route('lowongan.get-section', ['divisi' => '__ID__']) }}",
        getJobPosition: "{{ route('lowongan.get-job-position', ['section' => '__ID__']) }}",
    };


    // =========================================================
    // RESET SELECT
    // =========================================================

    function resetSelect(select, placeholder = '-- Pilih --') {

        select.innerHTML = `<option value="">${placeholder}</option>`;
        select.disabled = true;

        $(select)
            .val('')
            .trigger('change');
    }


    // =========================================================
    // ISI SELECT
    // =========================================================

    function fillSelect(select, data, selectedId = null) {

        select.innerHTML = '<option value="">-- Pilih --</option>';

        data.forEach(item => {

            const option = document.createElement('option');

            option.value = item.id;
            option.textContent = item.nama;

            select.appendChild(option);
        });

        select.disabled = data.length === 0;

        $(select)
            .val(selectedId ?? '')
            .trigger('change');
    }


    // =========================================================
    // FETCH DATA
    // =========================================================

    function fetchChildren(
        url,
        parentId,
        targetSelect,
        selectedId = null
    ) {

        return fetch(
            url.replace('__ID__', parentId)
        )
            .then(response => {

                if (!response.ok) {
                    throw new Error(
                        `Response tidak OK (${response.status})`
                    );
                }

                return response.json();
            })
            .then(data => {

                fillSelect(
                    targetSelect,
                    data,
                    selectedId
                );

                return data;
            })
            .catch(error => {

                console.error(
                    'Gagal mengambil data chained dropdown:',
                    error
                );
            });
    }


    // =========================================================
    // DEPARTEMEN → DIVISI
    // =========================================================

    $(departemenSelect).on('change', function () {

        resetSelect(divisiSelect);
        resetSelect(sectionSelect);
        resetSelect(jobPositionSelect);

        if (!this.value) {
            return;
        }

        fetchChildren(
            routes.getDivisi,
            this.value,
            divisiSelect
        );
    });


    // =========================================================
    // DIVISI → SECTION
    // =========================================================

    $(divisiSelect).on('change', function () {

        resetSelect(sectionSelect);
        resetSelect(jobPositionSelect);

        if (!this.value) {
            return;
        }

        fetchChildren(
            routes.getSection,
            this.value,
            sectionSelect
        );
    });


    // =========================================================
    // SECTION → JOB POSITION
    // =========================================================

    $(sectionSelect).on('change', function () {

        resetSelect(jobPositionSelect);

        if (!this.value) {
            return;
        }

        fetchChildren(
            routes.getJobPosition,
            this.value,
            jobPositionSelect
        );
    });

});
</script>
