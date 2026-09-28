<x-offcanvas.form id="offcanvas-rekrutmen-edit" title="Edit Rekrutmen"
    description="Perbarui informasi kandidat dan proses rekrutmen." size="xl">
    <form id="offcanvas-rekrutmen-edit-form" method="POST" enctype="multipart/form-data" data-edit-form>
        @csrf
        @method('PUT')

        {{-- ================= INFORMASI KANDIDAT ================= --}}
        <div class="mb-3">
            <h6 class="fw-semibold mb-1">Informasi Kandidat</h6>
            <p class="text-muted small mb-0">Informasi dasar kandidat.</p>
        </div>

        <div class="row">
            <div class="col-md-6">
                <x-form.input name="kode" label="Kode Rekrutmen" readonly />
            </div>
            <div class="col-md-6">
                <x-form.input name="nama" label="Nama Kandidat" placeholder="Nama kandidat" required />
            </div>
            <div class="col-md-6">
                <x-form.input name="email" label="Email" type="email" placeholder="nama@email.com" required />
            </div>
            <div class="col-md-6">
                <x-form.input name="no_tlp" label="No. Telepon" placeholder="08xxxxxxxxxx" required />
            </div>
        </div>

        <hr class="my-4">

        {{-- ================= POSISI YANG DILAMAR ================= --}}
        <div class="mb-3">
            <h6 class="fw-semibold mb-1">Posisi yang Dilamar</h6>
        </div>

        <div class="row">
            <div class="col-md-6">
                <x-form.select name="lowongan_id" id="edit_lowongan_id" label="Lowongan"
                    :options="$lowongans->pluck('judul', 'id')" nullable />
            </div>
            <div class="col-md-6">
                <x-form.select name="cabang_kantor_id" id="edit_cabang_kantor_id" label="Cabang Kantor"
                    :options="$cabangKantors->pluck('nama', 'id')" nullable required />
            </div>
            <div class="col-md-6">
                <x-form.select name="departemen_id" id="edit_departemen_id" label="Departemen"
                    :options="$departemens->pluck('nama', 'id')" nullable required />
            </div>
            <div class="col-md-6">
                <x-form.select name="divisi_id" id="edit_divisi_id" label="Divisi" :options="[]" nullable
                    disabled />
            </div>
            <div class="col-md-6">
                <x-form.select name="section_id" id="edit_section_id" label="Section" :options="[]" nullable
                    disabled />
            </div>
            <div class="col-md-6">
                <x-form.select name="job_position_id" id="edit_job_position_id" label="Posisi" :options="[]"
                    nullable disabled />
            </div>
            <div class="col-md-6">
                <x-form.select name="job_level_id" id="edit_job_level_id" label="Job Level"
                    :options="$jobLevels->pluck('nama', 'id')" nullable required />
            </div>
            <div class="col-md-6">
                <x-form.select name="jenjang_pendidikan_id" id="edit_jenjang_pendidikan_id" label="Pendidikan"
                    :options="$jenjangPendidikans->pluck('nama', 'id')" nullable required />
            </div>
            <div class="col-md-6">
                <x-form.select name="sumber_pelamar_id" id="edit_sumber_pelamar_id" label="Sumber Pelamar"
                    :options="$sumberPelamars->pluck('nama', 'id')" nullable required />
            </div>
        </div>

        <hr class="my-4">

        {{-- ================= DOKUMEN ================= --}}
        <div class="mb-3">
            <h6 class="fw-semibold mb-1">Dokumen Kandidat</h6>
        </div>

        <x-form.file-upload name="file_cv" id="edit_file_cv" label="CV" accept=".pdf,.doc,.docx"
            :max-files="1" :max-size="20" />

        <div class="small text-muted mb-3" id="edit-current-cv">
            CV saat ini akan tetap digunakan jika tidak mengunggah file baru.
        </div>

        <hr class="my-4">

        {{-- ================= STATUS ================= --}}
        <div class="mb-3">
            <h6 class="fw-semibold mb-1">Status Rekrutmen</h6>
        </div>

        <div class="row">
            <div class="col-md-6">
                <x-form.select name="status_rekrutmen" id="edit_status_rekrutmen" label="Tahap Rekrutmen"
                    :options="[
                        'pelamar' => 'Pelamar',
                        'screening' => 'Screening',
                        'interview' => 'Interview',
                        'offering' => 'Offering',
                        'diterima' => 'Diterima',
                        'ditolak' => 'Ditolak',
                    ]" required />
            </div>
            <div class="col-md-6">
                <x-form.select name="pool_talent" id="edit_pool_talent" label="Talent Pool"
                    :options="['rehire' => 'Rehire', 'blacklist' => 'Blacklist']" nullable />
            </div>
        </div>

        <x-form.switch name="status" label="Status Aktif" inline />
    </form>
</x-offcanvas.form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('offcanvas-rekrutmen-edit-form');
        if (!form) return;

        const $ = window.jQuery;

        const departemenSelect = form.querySelector('#edit_departemen_id');
        const divisiSelect = form.querySelector('#edit_divisi_id');
        const sectionSelect = form.querySelector('#edit_section_id');
        const jobPositionSelect = form.querySelector('#edit_job_position_id');
        const currentCv = document.getElementById('edit-current-cv');

        const routes = {
            getDivisi: "{{ route('rekrutmen.get-divisi', ['departemen' => '__ID__']) }}",
            getSection: "{{ route('rekrutmen.get-section', ['divisi' => '__ID__']) }}",
            getJobPosition: "{{ route('rekrutmen.get-job-position', ['section' => '__ID__']) }}",
        };

        // true selama prefill data lama, supaya event change dari prefill
        // tidak dianggap "user ganti pilihan"
        let isPrefilling = false;

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
            newTicket(select);
            select.innerHTML = '<option value="">-- Pilih --</option>';
            select.disabled = true;
            setValue(select, '');
        }

        function fillSelect(select, data, selectedId = '') {
            select.innerHTML = '<option value="">-- Pilih --</option>';
            data.forEach((item) => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.nama;
                select.appendChild(option);
            });
            select.disabled = data.length === 0;
            setValue(select, selectedId ?? '');
        }

        function fetchChildren(url, parentId, targetSelect, selectedId = '') {
            const ticket = newTicket(targetSelect);

            return fetch(url.replace('__ID__', parentId), { headers: { Accept: 'application/json' } })
                .then((res) => {
                    if (!res.ok) throw new Error(`HTTP ${res.status}`);
                    return res.json();
                })
                .then((data) => {
                    if (ticket !== tickets[targetSelect.id]) return; // respons usang
                    fillSelect(targetSelect, data, selectedId);
                });
        }

        // Data edit selesai dimuat (event dikirim oleh offcanvas-edit.js)
        form.addEventListener('edit-data:loaded', async function (event) {
            const {
                departemen_id,
                divisi_id,
                section_id,
                job_position_id,
                cv_name,
            } = event.detail || {};

            // Info CV saat ini
            if (currentCv) {
                currentCv.textContent = cv_name
                    ? `CV saat ini: ${cv_name}. Akan tetap digunakan jika tidak mengunggah file baru.`
                    : 'Belum ada CV.';
            }

            isPrefilling = true;

            try {
                resetSelect(divisiSelect);
                resetSelect(sectionSelect);
                resetSelect(jobPositionSelect);

                if (departemen_id) {
                    await fetchChildren(routes.getDivisi, departemen_id, divisiSelect, divisi_id);
                }
                if (divisi_id) {
                    await fetchChildren(routes.getSection, divisi_id, sectionSelect, section_id);
                }
                if (section_id) {
                    await fetchChildren(routes.getJobPosition, section_id, jobPositionSelect, job_position_id);
                }
            } catch (err) {
                console.error('Gagal mengisi chained dropdown edit rekrutmen:', err);
            } finally {
                isPrefilling = false;
            }
        });

        // User ganti Departemen -> Divisi
        onChange(departemenSelect, function () {
            if (isPrefilling) return;

            resetSelect(divisiSelect);
            resetSelect(sectionSelect);
            resetSelect(jobPositionSelect);
            if (!this.value) return;

            fetchChildren(routes.getDivisi, this.value, divisiSelect)
                .catch((err) => console.error('Gagal ambil divisi:', err));
        });

        // User ganti Divisi -> Section
        onChange(divisiSelect, function () {
            if (isPrefilling) return;

            resetSelect(sectionSelect);
            resetSelect(jobPositionSelect);
            if (!this.value) return;

            fetchChildren(routes.getSection, this.value, sectionSelect)
                .catch((err) => console.error('Gagal ambil section:', err));
        });

        // User ganti Section -> Job Position
        onChange(sectionSelect, function () {
            if (isPrefilling) return;

            resetSelect(jobPositionSelect);
            if (!this.value) return;

            fetchChildren(routes.getJobPosition, this.value, jobPositionSelect)
                .catch((err) => console.error('Gagal ambil job position:', err));
        });
    });
</script>