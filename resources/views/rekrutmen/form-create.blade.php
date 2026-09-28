<x-offcanvas.form id="offcanvas-tambah-rekrutmen" title="Tambah Rekrutmen"
    description="Tambahkan kandidat baru ke proses rekrutmen." size="xl">
    <form id="offcanvas-tambah-rekrutmen-form" action="{{ route('rekrutmen.store') }}" method="POST"
        enctype="multipart/form-data">
        @csrf

        {{-- ================= INFORMASI KANDIDAT ================= --}}
        <div class="mb-3">
            <h6 class="fw-semibold mb-1">Informasi Kandidat</h6>
            <p class="text-muted small mb-0">Masukkan informasi dasar kandidat.</p>
        </div>

        <div class="row">
            <div class="col-md-6">
                <x-form.input name="kode" label="Kode Rekrutmen" value="{{ $previewKode }}" readonly />
            </div>
            <div class="col-md-6">
                <x-form.input name="nama" label="Nama Kandidat" placeholder="Contoh: Komang Ayu" required />
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
            <p class="text-muted small mb-0">Tentukan posisi dan penempatan kandidat.</p>
        </div>

        <div class="row">
            <div class="col-md-6">
                <x-form.select name="lowongan_id" id="create_lowongan_id" label="Lowongan"
                    :options="$lowongans->pluck('judul', 'id')" nullable />
            </div>
            <div class="col-md-6">
                <x-form.select name="cabang_kantor_id" id="create_cabang_kantor_id" label="Cabang Kantor"
                    :options="$cabangKantors->pluck('nama', 'id')" nullable required />
            </div>
            <div class="col-md-6">
                <x-form.select name="departemen_id" id="create_departemen_id" label="Departemen"
                    :options="$departemens->pluck('nama', 'id')" nullable required />
            </div>
            <div class="col-md-6">
                <x-form.select name="divisi_id" id="create_divisi_id" label="Divisi" :options="[]" nullable
                    disabled />
            </div>
            <div class="col-md-6">
                <x-form.select name="section_id" id="create_section_id" label="Section" :options="[]" nullable
                    disabled />
            </div>
            <div class="col-md-6">
                <x-form.select name="job_position_id" id="create_job_position_id" label="Posisi" :options="[]"
                    nullable disabled />
            </div>
            <div class="col-md-6">
                <x-form.select name="job_level_id" id="create_job_level_id" label="Job Level"
                    :options="$jobLevels->pluck('nama', 'id')" nullable required />
            </div>
            <div class="col-md-6">
                <x-form.select name="jenjang_pendidikan_id" id="create_jenjang_pendidikan_id" label="Pendidikan"
                    :options="$jenjangPendidikans->pluck('nama', 'id')" nullable required />
            </div>
            <div class="col-md-6">
                <x-form.select name="sumber_pelamar_id" id="create_sumber_pelamar_id" label="Sumber Pelamar"
                    :options="$sumberPelamars->pluck('nama', 'id')" nullable required />
            </div>
        </div>

        <hr class="my-4">

        {{-- ================= DOKUMEN ================= --}}
        <div class="mb-3">
            <h6 class="fw-semibold mb-1">Dokumen Kandidat</h6>
            <p class="text-muted small mb-0">Upload CV kandidat dalam format PDF atau Word.</p>
        </div>

        <x-form.file-upload name="file_cv" id="create_file_cv" label="CV" accept=".pdf,.doc,.docx"
            :max-files="1" :max-size="20" required />

        <hr class="my-4">

        {{-- ================= STATUS ================= --}}
        <div class="mb-3">
            <h6 class="fw-semibold mb-1">Status Rekrutmen</h6>
        </div>

        <div class="row">
            <div class="col-md-6">
                <x-form.select name="status_rekrutmen" id="create_status_rekrutmen" label="Tahap Rekrutmen"
                    :options="[
                        'pelamar' => 'Pelamar',
                        'screening' => 'Screening',
                        'interview' => 'Interview',
                        'offering' => 'Offering',
                        'diterima' => 'Diterima',
                        'ditolak' => 'Ditolak',
                    ]" selected="pelamar" required />
            </div>
            <div class="col-md-6">
                <x-form.select name="pool_talent" id="create_pool_talent" label="Talent Pool"
                    :options="['rehire' => 'Rehire', 'blacklist' => 'Blacklist']" nullable />
            </div>
        </div>

        <x-form.switch name="status" label="Status Aktif" :checked="true" inline />
    </form>
</x-offcanvas.form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('offcanvas-tambah-rekrutmen-form');
        if (!form) return;

        // jQuery opsional: kalau ada dipakai supaya select2 ikut ke-update,
        // kalau tidak ada, jatuh ke event native.
        const $ = window.jQuery;

        const departemenSelect = form.querySelector('#create_departemen_id');
        const divisiSelect = form.querySelector('#create_divisi_id');
        const sectionSelect = form.querySelector('#create_section_id');
        const jobPositionSelect = form.querySelector('#create_job_position_id');

        const routes = {
            getDivisi: "{{ route('rekrutmen.get-divisi', ['departemen' => '__ID__']) }}",
            getSection: "{{ route('rekrutmen.get-section', ['divisi' => '__ID__']) }}",
            getJobPosition: "{{ route('rekrutmen.get-job-position', ['section' => '__ID__']) }}",
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
            newTicket(select);
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
                    if (ticket !== tickets[targetSelect.id]) return; // respons usang
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