<x-offcanvas.form id="offcanvas-lowongan-edit" title="Edit Lowongan" description="Perbarui data lowongan." size="xl">
    <form id="offcanvas-lowongan-edit-form" method="POST" data-edit-form>
        @csrf
        @method('PUT')

        <x-form.input name="kode" label="Kode Lowongan" readonly />

        <h6 class="mb-3 mt-4">Informasi Lowongan</h6>
        <div class="row">
            <div class="col-md-6">
                <x-form.input name="judul" label="Judul Lowongan" placeholder="Contoh: Staff Finance" required />
            </div>
            <div class="col-md-6">
                <x-form.select name="permintaan_karyawan_id" id="edit_permintaan_karyawan_id"
                    label="Permintaan Karyawan" :options="$permintaanKaryawans->pluck('nama', 'id')" nullable />
            </div>
        </div>

        <h6 class="mb-3 mt-4">Penempatan</h6>
        <div class="row">
            <div class="col-md-4">
                <x-form.select name="cabang_kantor_id" id="edit_cabang_kantor_id" label="Cabang Kantor"
                    :options="$cabangKantors->pluck('nama', 'id')" nullable required />
            </div>
            <div class="col-md-4">
                <x-form.select name="departemen_id" id="edit_departemen_id" label="Departemen"
                    :options="$departemens->pluck('nama', 'id')" nullable required />
            </div>
            <div class="col-md-4">
                <x-form.select name="divisi_id" id="edit_divisi_id" label="Divisi" :options="[]" nullable disabled />
            </div>
            <div class="col-md-4">
                <x-form.select name="section_id" id="edit_section_id" label="Section" :options="[]" nullable disabled />
            </div>
            <div class="col-md-4">
                <x-form.select name="job_position_id" id="edit_job_position_id" label="Job Position" :options="[]" nullable disabled />
            </div>
            <div class="col-md-4">
                <x-form.select name="job_level_id" id="edit_job_level_id" label="Job Level"
                    :options="$jobLevels->pluck('nama', 'id')" nullable required />
            </div>
        </div>

        <h6 class="mb-3 mt-4">Detail Lowongan</h6>
        <div class="row">
            <div class="col-md-4">
                <x-form.input name="kuota" label="Kuota" type="number" min="1" required />
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

        <x-form.rich-text-editor name="kualifikasi" label="Kualifikasi" />

        <x-form.textarea name="deskripsi" label="Deskripsi Pekerjaan" rows="4" required />

        <x-form.switch name="status" label="Status Aktif" />
    </form>
</x-offcanvas.form>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('offcanvas-lowongan-edit-form');

    if (!form) return;

    const $ = window.jQuery;

    if (!$) {
        console.error('jQuery belum tersedia.');
        return;
    }

    const departemenSelect  = form.querySelector('#edit_departemen_id');
    const divisiSelect      = form.querySelector('#edit_divisi_id');
    const sectionSelect     = form.querySelector('#edit_section_id');
    const jobPositionSelect = form.querySelector('#edit_job_position_id');

    const routes = {
        getDivisi: "{{ route('lowongan.get-divisi', ['departemen' => '__ID__']) }}",
        getSection: "{{ route('lowongan.get-section', ['divisi' => '__ID__']) }}",
        getJobPosition: "{{ route('lowongan.get-job-position', ['section' => '__ID__']) }}",
    };

    let isPrefilling = false;


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
    // ISI SELECT DARI HASIL API
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
    // FETCH DATA CHILD
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

                throw error;
            });
    }


    // =========================================================
    // DATA EDIT SELESAI DIMUAT
    // =========================================================
    //
    // Event ini dikirim oleh offcanvas-edit.js.
    // Jangan diganti menjadi $(form).on(...)
    // karena ini adalah custom event native.
    //

    form.addEventListener(
        'edit-data:loaded',
        async function (event) {

            const data = event.detail;

            if (!data) {
                console.warn(
                    'Data edit lowongan tidak ditemukan.'
                );

                return;
            }

            const {
                departemen_id,
                divisi_id,
                section_id,
                job_position_id
            } = data;

            isPrefilling = true;

            try {

                // ---------------------------------------------
                // RESET CHILD
                // ---------------------------------------------

                resetSelect(divisiSelect);
                resetSelect(sectionSelect);
                resetSelect(jobPositionSelect);


                // ---------------------------------------------
                // DEPARTEMEN → DIVISI
                // ---------------------------------------------

                if (departemen_id) {

                    await fetchChildren(
                        routes.getDivisi,
                        departemen_id,
                        divisiSelect,
                        divisi_id
                    );
                }


                // ---------------------------------------------
                // DIVISI → SECTION
                // ---------------------------------------------

                if (divisi_id) {

                    await fetchChildren(
                        routes.getSection,
                        divisi_id,
                        sectionSelect,
                        section_id
                    );
                }


                // ---------------------------------------------
                // SECTION → JOB POSITION
                // ---------------------------------------------

                if (section_id) {

                    await fetchChildren(
                        routes.getJobPosition,
                        section_id,
                        jobPositionSelect,
                        job_position_id
                    );
                }

            } catch (error) {

                console.error(
                    'Gagal mengisi chained dropdown edit lowongan:',
                    error
                );

            } finally {

                isPrefilling = false;
            }
        }
    );


    // =========================================================
    // USER GANTI DEPARTEMEN
    // DEPARTEMEN → DIVISI
    // =========================================================

    $(departemenSelect).on('change', function () {

        // Jangan jalankan ini ketika sedang
        // mengisi data edit lama.
        if (isPrefilling) {
            return;
        }

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
    // USER GANTI DIVISI
    // DIVISI → SECTION
    // =========================================================

    $(divisiSelect).on('change', function () {

        if (isPrefilling) {
            return;
        }

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
    // USER GANTI SECTION
    // SECTION → JOB POSITION
    // =========================================================

    $(sectionSelect).on('change', function () {

        if (isPrefilling) {
            return;
        }

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