<x-offcanvas.form id="offcanvas-karyawan" title="Tambah Karyawan" description="Tambahkan karyawan baru ke sistem."
    size="xl">
    <form id="offcanvas-karyawan-form" action="{{ route('karyawan.store') }}" method="POST" novalidate>
        @csrf

        {{-- Penanda form mana yang disubmit (dipakai untuk membuka lagi offcanvas kalau validasi gagal) --}}
        <input type="hidden" name="_form" value="offcanvas-karyawan">

        {{-- =========================
            KODE KARYAWAN
        ========================== --}}
        <x-form.input name="nip" id="create_nip" label="Kode Karyawan" value="{{ $previewNip }}" readonly />


        {{-- =========================
            INFORMASI KARYAWAN
        ========================== --}}
        <h6 class="mb-3 mt-4">Informasi Karyawan</h6>

        <div class="row">

            <div class="col-md-4">
                <x-form.input name="nama" id="create_nama" label="Nama Karyawan" placeholder="Contoh: Andrew" required />
            </div>

            <div class="col-md-4">
                <x-form.input name="email" id="create_email" label="Email" type="email"
                    placeholder="Contoh: andrew@email.com" required />
            </div>

            <div class="col-md-4">
                <x-form.input name="no_tlp" id="create_no_tlp" label="No. Telepon" inputmode="numeric"
                    placeholder="Contoh: 081234567890" required />
            </div>

            <div class="col-md-4">
                <x-form.input name="nik" id="create_nik" label="NIK" inputmode="numeric"
                    placeholder="16 digit angka" required />
            </div>

        </div>


        {{-- =========================
            PENEMPATAN & JABATAN
            Departemen -> Divisi -> Section -> Job Position (chained dropdown).
            Divisi, Section, dan Job Position boleh kosong (nullable di request & migration).
        ========================== --}}
        <h6 class="mb-3 mt-4">Penempatan & Jabatan</h6>

        <div class="row">

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

            <div class="col-md-4">
                <x-form.select name="cabang_kantor_id" id="create_cabang_kantor_id" label="Cabang Kantor"
                    :options="$cabangKantors->pluck('nama', 'id')" nullable required />
            </div>

        </div>


        {{-- =========================
            KEPEGAWAIAN
        ========================== --}}
        <h6 class="mb-3 mt-4">Kepegawaian</h6>

        <div class="row">

            <div class="col-md-4">
                <x-form.select name="status_kepegawaian_id" id="create_status_kepegawaian_id"
                    label="Status Kepegawaian" :options="$statusKepegawaians->pluck('nama', 'id')" nullable required />
            </div>

            <div class="col-md-4">
                <x-form.currency name="gaji" label="Gaji" required />
            </div>
        </div>


        {{-- =========================
            DATA PRIBADI
        ========================== --}}
        <h6 class="mb-3 mt-4">Data Pribadi</h6>

        <div class="row">

            <div class="col-md-4">
                <x-form.select name="jenjang_pendidikan_id" id="create_jenjang_pendidikan_id"
                    label="Jenjang Pendidikan" :options="$jenjangPendidikans->pluck('nama', 'id')" nullable required />
            </div>

            <div class="col-md-4">
                <x-form.select name="status_kawin_id" id="create_status_kawin_id" label="Status Kawin"
                    :options="$statusKawins->pluck('nama', 'id')" nullable required />
            </div>

            <div class="col-md-4">
                <x-form.select name="agama_id" id="create_agama_id" label="Agama" :options="$agamas->pluck('nama', 'id')"
                    nullable required />
            </div>

        </div>


        {{-- =========================
            ADMINISTRASI
        ========================== --}}
        <h6 class="mb-3 mt-4">Administrasi</h6>

        <div class="row">

            <div class="col-md-4">
                <x-form.input name="no_bpjs_ketenagakerjaan" id="create_no_bpjs_ketenagakerjaan"
                    label="No. BPJS Ketenagakerjaan" inputmode="numeric" placeholder="11 digit angka" required />
            </div>

            <div class="col-md-4">
                <x-form.input name="no_bpjs_kesehatan" id="create_no_bpjs_kesehatan" label="No. BPJS Kesehatan"
                    inputmode="numeric" placeholder="13 digit angka" required />
            </div>

            <div class="col-md-4">
                <x-form.input name="no_npwp" id="create_no_npwp" label="No. NPWP" inputmode="numeric"
                    placeholder="16 digit angka" required />
            </div>

        </div>


        {{-- =========================
            DATA BANK
        ========================== --}}
        <h6 class="mb-3 mt-4">Data Bank</h6>

        <div class="row">

            <div class="col-md-4">
                <x-form.select name="bank_id" id="create_bank_id" label="Bank" :options="$banks->pluck('nama', 'id')" nullable
                    required />
            </div>

            <div class="col-md-4">
                <x-form.input name="nama_bank" id="create_nama_bank" label="Nama Pemilik Rekening"
                    placeholder="Masukkan nama pemilik rekening" required />
            </div>

            <div class="col-md-4">
                <x-form.input name="no_rekening" id="create_no_rekening" label="No. Rekening" inputmode="numeric"
                    placeholder="Masukkan nomor rekening" required />
            </div>

        </div>


        {{-- =========================
            REKRUTMEN
        ========================== --}}
        <h6 class="mb-3 mt-4">Rekrutmen</h6>

        <div class="row">

            <div class="col-md-4">
                <x-form.select name="lowongan_id" id="create_lowongan_id" label="Lowongan" :options="$lowongans->pluck('judul', 'id')"
                    nullable />
            </div>

        </div>


        {{-- =========================
            STATUS
            Hanya Aktif / Nonaktif. Status "Resign" TIDAK bisa diatur dari sini —
            berubah otomatis saat permintaan resign disetujui & tanggal efektifnya tiba.
        ========================== --}}
        <div class="mt-4">
            <label class="form-label d-block">Status Aktif</label>

            <input type="hidden" name="status" value="nonaktif">

            <label class="app-form-switch">
                <input type="checkbox" name="status" value="aktif" id="create_status" @checked(old('status', 'aktif') === 'aktif')>
                <span class="app-form-switch__slider"></span>
            </label>

            <div class="form-text">
                Status Resign tidak diatur di sini, tetapi otomatis dari permintaan resign yang sudah disetujui.
            </div>
        </div>

    </form>
</x-offcanvas.form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('offcanvas-karyawan-form');
        if (!form) return;

        // Select2 hanya memicu event "change" lewat jQuery (bukan event native), jadi listener
        // WAJIB dipasang pakai jQuery. Kalau jQuery belum ada, jatuh ke event native.
        const $ = window.jQuery;

        const departemenSelect = form.querySelector('#create_departemen_id');
        const divisiSelect = form.querySelector('#create_divisi_id');
        const sectionSelect = form.querySelector('#create_section_id');
        const jobPositionSelect = form.querySelector('#create_job_position_id');

        const routes = {
            getDivisi: "{{ route('karyawan.get-divisi', ['departemen' => '__ID__']) }}",
            getSection: "{{ route('karyawan.get-section', ['divisi' => '__ID__']) }}",
            getJobPosition: "{{ route('karyawan.get-job-position', ['section' => '__ID__']) }}",
        };

        // "Tiket" per select: kalau ada fetch baru / reset, respons fetch lama dibuang
        const tickets = {};
        const newTicket = (select) => (tickets[select.id] = (tickets[select.id] || 0) + 1);

        function onChange(el, handler) {
            if ($) $(el).on('change', handler);
            else el.addEventListener('change', handler);
        }

        // Segarkan tampilan Select2 TANPA memicu handler 'change' milik kita
        // (namespace .select2 hanya didengar Select2) -> tidak ada reset berantai.
        function refreshUi(select) {
            if ($) $(select).trigger('change.select2');
        }

        function resetSelect(select) {
            newTicket(select); // batalkan fetch yang masih jalan
            select.innerHTML = '<option value=""></option>';
            select.disabled = true;
            refreshUi(select);
        }

        function fillSelect(select, data, selectedId = '') {
            select.innerHTML = '<option value=""></option>';
            data.forEach((item) => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.nama;
                select.appendChild(option);
            });
            select.disabled = data.length === 0;
            select.value = selectedId ?? '';
            refreshUi(select);
        }

        function fetchChildren(url, parentId, targetSelect, selectedId = '') {
            const ticket = newTicket(targetSelect);

            return fetch(url.replace('__ID__', parentId), {
                    headers: {
                        Accept: 'application/json'
                    }
                })
                .then((res) => {
                    if (!res.ok) throw new Error(`HTTP ${res.status}`);
                    return res.json();
                })
                .then((data) => {
                    if (ticket !== tickets[targetSelect.id]) return; // respons usang
                    fillSelect(targetSelect, data, selectedId);
                });
        }

        // Departemen -> Divisi
        onChange(departemenSelect, function() {
            resetSelect(divisiSelect);
            resetSelect(sectionSelect);
            resetSelect(jobPositionSelect);
            if (!this.value) return;

            fetchChildren(routes.getDivisi, this.value, divisiSelect)
                .catch((err) => console.error('Gagal ambil divisi:', err));
        });

        // Divisi -> Section
        onChange(divisiSelect, function() {
            resetSelect(sectionSelect);
            resetSelect(jobPositionSelect);
            if (!this.value) return;

            fetchChildren(routes.getSection, this.value, sectionSelect)
                .catch((err) => console.error('Gagal ambil section:', err));
        });

        // Section -> Job Position
        onChange(sectionSelect, function() {
            resetSelect(jobPositionSelect);
            if (!this.value) return;

            fetchChildren(routes.getJobPosition, this.value, jobPositionSelect)
                .catch((err) => console.error('Gagal ambil job position:', err));
        });

        // Validasi gagal -> halaman dimuat ulang. Departemen sudah terpilih lewat old(),
        // tapi Divisi / Section / Job Position harus diisi ulang dari server.
        const oldValues = {
            departemen_id: @json(old('_form') === 'offcanvas-karyawan' ? old('departemen_id') : null),
            divisi_id: @json(old('_form') === 'offcanvas-karyawan' ? old('divisi_id') : null),
            section_id: @json(old('_form') === 'offcanvas-karyawan' ? old('section_id') : null),
            job_position_id: @json(old('_form') === 'offcanvas-karyawan' ? old('job_position_id') : null),
        };

        (async function restoreOldValues() {
            try {
                if (oldValues.departemen_id) {
                    await fetchChildren(routes.getDivisi, oldValues.departemen_id, divisiSelect, oldValues.divisi_id);
                }
                if (oldValues.divisi_id) {
                    await fetchChildren(routes.getSection, oldValues.divisi_id, sectionSelect, oldValues.section_id);
                }
                if (oldValues.section_id) {
                    await fetchChildren(routes.getJobPosition, oldValues.section_id, jobPositionSelect, oldValues.job_position_id);
                }
            } catch (err) {
                console.error('Gagal memulihkan chained dropdown create karyawan:', err);
            }
        })();
    });
</script>
