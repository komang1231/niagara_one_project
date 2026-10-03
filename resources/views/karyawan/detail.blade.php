{{--
    DETAIL KARYAWAN (offcanvas, 3 tab).
    Isi tiap elemen bertanda data-field="..." diisi JS dari JSON KaryawanController@show
    (nama key JSON = nilai data-field).
--}}
@php
    // Kontak
    $kontak = [
        ['key' => 'email', 'label' => 'Email', 'icon' => 'bi-envelope'],
        ['key' => 'no_tlp', 'label' => 'No. Telepon', 'icon' => 'bi-telephone', 'number' => true],
    ];

    // Identitas & latar belakang
    $identitas = [
        ['key' => 'nik', 'label' => 'NIK', 'icon' => 'bi-person-vcard', 'number' => true, 'copy' => true],
        ['key' => 'jenjang_pendidikan', 'label' => 'Jenjang Pendidikan', 'icon' => 'bi-mortarboard'],
        ['key' => 'status_kawin', 'label' => 'Status Pernikahan', 'icon' => 'bi-heart'],
        ['key' => 'agama', 'label' => 'Agama', 'icon' => 'bi-journal-text'],
    ];

    // Struktur organisasi (urut dari atas ke bawah)
    $struktur = [
        ['key' => 'departemen', 'label' => 'Departemen'],
        ['key' => 'divisi', 'label' => 'Divisi'],
        ['key' => 'section', 'label' => 'Section'],
        ['key' => 'job_position', 'label' => 'Posisi / Job Position'],
    ];

    // Dokumen legal
    $legal = [
        ['key' => 'no_npwp', 'label' => 'NPWP', 'icon' => 'bi-file-earmark-text'],
        ['key' => 'no_bpjs_ketenagakerjaan', 'label' => 'BPJS Ketenagakerjaan', 'icon' => 'bi-shield-check'],
        ['key' => 'no_bpjs_kesehatan', 'label' => 'BPJS Kesehatan', 'icon' => 'bi-heart-pulse'],
    ];
@endphp

<x-offcanvas.detail id="offcanvas-karyawan-detail" size="lg">

    {{-- =========================================================
        HEADER: avatar, nama, status, NIP, posisi, status kepegawaian
    ========================================================== --}}
    <x-slot:header>
        <div class="karyawan-detail__header-content">

            <div class="karyawan-detail__avatar" data-field="inisial">-</div>

            <div class="karyawan-detail__summary">

                <div class="karyawan-detail__name-row">
                    <h4 data-field="nama">-</h4>

                    <span class="karyawan-detail__status" data-status-pill>
                        <span class="karyawan-detail__status-dot"></span>
                        <span data-status-pill-text>-</span>
                    </span>
                </div>

                <div class="karyawan-detail__meta">
                    <span class="karyawan-detail__code" data-field="nip" title="Kode karyawan (NIP)">-</span>

                    <span class="karyawan-detail__meta-divider"></span>

                    <span data-field="job_position">-</span>

                    <span class="karyawan-detail__meta-divider"></span>

                    <span class="karyawan-detail__chip" data-field="status_kepegawaian" title="Status kepegawaian">-</span>
                </div>

            </div>

        </div>
    </x-slot:header>


    {{-- =========================================================
        TAB
    ========================================================== --}}
    <x-slot:tabs>
        <ul class="karyawan-detail__tabs nav" role="tablist">

            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-data-pribadi"
                    type="button">
                    <i class="bi bi-person"></i> Data Pribadi
                </button>
            </li>

            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-penempatan" type="button">
                    <i class="bi bi-diagram-3"></i> Penempatan &amp; Jabatan
                </button>
            </li>

            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-rekening" type="button">
                    <i class="bi bi-wallet2"></i> Rekening &amp; Legal
                </button>
            </li>

        </ul>
    </x-slot:tabs>


    <div class="tab-content">

        {{-- =========================================================
            TAB DATA PRIBADI
        ========================================================== --}}
        <div class="tab-pane fade show active" id="tab-data-pribadi" role="tabpanel">

            <div class="karyawan-detail__section">
                <span class="karyawan-detail__section-title">Kontak</span>

                <div class="karyawan-detail__grid karyawan-detail__grid--single">
                    @foreach ($kontak as $f)
                        <div class="karyawan-detail__item">
                            <span class="karyawan-detail__icon"><i class="bi {{ $f['icon'] }}"></i></span>

                            <div class="karyawan-detail__item-body">
                                <span class="label">{{ $f['label'] }}</span>
                                <span class="value {{ !empty($f['number']) ? 'value-number' : '' }}"
                                    data-field="{{ $f['key'] }}">-</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="karyawan-detail__section">
                <span class="karyawan-detail__section-title">Identitas &amp; latar belakang</span>

                <div class="karyawan-detail__grid">
                    @foreach ($identitas as $f)
                        <div class="karyawan-detail__item">
                            <span class="karyawan-detail__icon"><i class="bi {{ $f['icon'] }}"></i></span>

                            <div class="karyawan-detail__item-body">
                                <span class="label">{{ $f['label'] }}</span>
                                <span class="value {{ !empty($f['number']) ? 'value-number' : '' }}"
                                    data-field="{{ $f['key'] }}">-</span>
                            </div>

                            @if (!empty($f['copy']))
                                <button type="button" class="karyawan-detail__copy" data-copy-field="{{ $f['key'] }}"
                                    title="Salin {{ $f['label'] }}">
                                    <i class="bi bi-copy"></i>
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- created_at = waktu record dibuat di database, BUKAN tanggal mulai bekerja --}}
            <div class="karyawan-detail__foot">
                <i class="bi bi-clock-history"></i>
                <span>Data dibuat pada <strong data-field="data_dibuat">-</strong></span>
            </div>

        </div>


        {{-- =========================================================
            TAB PENEMPATAN & JABATAN
        ========================================================== --}}
        <div class="tab-pane fade" id="tab-penempatan" role="tabpanel">

            <div class="karyawan-detail__section">
                <span class="karyawan-detail__section-title">Struktur organisasi</span>

                <ol class="karyawan-detail__path">
                    @foreach ($struktur as $f)
                        <li>
                            <span class="label">{{ $f['label'] }}</span>
                            <span class="value" data-field="{{ $f['key'] }}">-</span>
                        </li>
                    @endforeach
                </ol>
            </div>

            <div class="karyawan-detail__section">
                <span class="karyawan-detail__section-title">Jabatan &amp; kepegawaian</span>

                <div class="karyawan-detail__grid">

                    <div class="karyawan-detail__item">
                        <span class="karyawan-detail__icon"><i class="bi bi-bar-chart-steps"></i></span>
                        <div class="karyawan-detail__item-body">
                            <span class="label">Job Level</span>
                            <span class="value" data-field="job_level">-</span>
                        </div>
                    </div>

                    <div class="karyawan-detail__item">
                        <span class="karyawan-detail__icon"><i class="bi bi-geo-alt"></i></span>
                        <div class="karyawan-detail__item-body">
                            <span class="label">Cabang Kantor</span>
                            <span class="value" data-field="cabang_kantor">-</span>
                        </div>
                    </div>

                    <div class="karyawan-detail__item">
                        <span class="karyawan-detail__icon"><i class="bi bi-briefcase"></i></span>
                        <div class="karyawan-detail__item-body">
                            <span class="label">Status Kepegawaian</span>
                            <span class="value" data-field="status_kepegawaian_full">-</span>
                        </div>
                    </div>

                    <div class="karyawan-detail__item">
                        <span class="karyawan-detail__icon"><i class="bi bi-activity"></i></span>
                        <div class="karyawan-detail__item-body">
                            <span class="label">Status</span>
                            <span class="karyawan-detail__status-inline" data-status-inline
                                data-field="status_aktif">-</span>
                        </div>
                    </div>

                </div>
            </div>

            <div class="karyawan-detail__section">
                <span class="karyawan-detail__section-title">Kompensasi</span>

                <div class="karyawan-detail__salary">
                    <div>
                        <span class="label">Gaji</span>
                        <span class="value value-number" data-field="gaji">-</span>
                    </div>

                    <i class="bi bi-cash-stack"></i>
                </div>
            </div>

        </div>


        {{-- =========================================================
            TAB REKENING & LEGAL
        ========================================================== --}}
        <div class="tab-pane fade" id="tab-rekening" role="tabpanel">

            <div class="karyawan-detail__section">
                <span class="karyawan-detail__section-title">Rekening bank</span>

                <div class="karyawan-detail__bankcard">
                    <div class="karyawan-detail__bankcard-top">
                        <span class="karyawan-detail__bankcard-bank" data-field="bank">-</span>
                        <i class="bi bi-bank2"></i>
                    </div>

                    <span class="label">Nomor rekening</span>
                    <span class="karyawan-detail__bankcard-number" data-field="no_rekening">-</span>

                    <div class="karyawan-detail__bankcard-bottom">
                        <div>
                            <span class="label">Atas nama</span>
                            <strong data-field="nama_bank">-</strong>
                        </div>

                        <button type="button" class="karyawan-detail__copy" data-copy-field="no_rekening"
                            title="Salin nomor rekening">
                            <i class="bi bi-copy"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="karyawan-detail__section">
                <span class="karyawan-detail__section-title">Dokumen legal</span>

                <div class="karyawan-detail__grid karyawan-detail__grid--single">
                    @foreach ($legal as $f)
                        <div class="karyawan-detail__item">
                            <span class="karyawan-detail__icon"><i class="bi {{ $f['icon'] }}"></i></span>

                            <div class="karyawan-detail__item-body">
                                <span class="label">{{ $f['label'] }}</span>
                                <span class="value value-number" data-field="{{ $f['key'] }}">-</span>
                            </div>

                            <button type="button" class="karyawan-detail__copy" data-copy-field="{{ $f['key'] }}"
                                title="Salin {{ $f['label'] }}">
                                <i class="bi bi-copy"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

    </div>

</x-offcanvas.detail>


<script>
    (function() {

        const root = document.getElementById('offcanvas-karyawan-detail');
        if (!root) return;

        const fields = root.querySelectorAll('[data-field]');
        const statusPill = root.querySelector('[data-status-pill]');
        const statusPillText = root.querySelector('[data-status-pill-text]');
        const statusInline = root.querySelector('[data-status-inline]');

        // Hanya respons terbaru yang boleh mengisi (kalau user klik detail baris lain dengan cepat)
        let requestId = 0;

        /* ---------- Isi / reset ---------- */

        function setStatus(status, label) {
            const stateClass = status === 'aktif' ? '' : (status === 'resign' ? 'is-resign' : 'is-inactive');

            [statusPill, statusInline].forEach(function(el) {
                if (!el) return;
                el.classList.remove('is-inactive', 'is-resign');
                if (stateClass) el.classList.add(stateClass);
            });

            if (statusPillText) statusPillText.textContent = label;
            if (statusInline) statusInline.textContent = label;
        }

        function resetContent() {
            fields.forEach((el) => el.textContent = '-');
            setStatus('aktif', '-');
        }

        function fillContent(data) {
            fields.forEach(function(el) {
                const value = data[el.dataset.field];
                el.textContent = (value === null || value === undefined || value === '') ? '-' : value;
            });

            setStatus(data.status, data.status_aktif || '-');
        }

        /* ---------- Load detail saat offcanvas dibuka ---------- */

        root.addEventListener('show.bs.offcanvas', function(event) {
            const trigger = event.relatedTarget;
            const url = trigger?.getAttribute('data-detail-url');

            if (!url) return;

            const thisRequest = ++requestId;

            resetContent();
            root.classList.add('is-loading');

            fetch(url, {
                    headers: {
                        Accept: 'application/json'
                    }
                })
                .then(function(res) {
                    if (!res.ok) throw new Error('Gagal mengambil data karyawan.');
                    return res.json();
                })
                .then(function(data) {
                    if (thisRequest !== requestId) return;
                    fillContent(data);
                })
                .catch(function(err) {
                    console.error('Gagal ambil detail karyawan:', err);

                    if (thisRequest !== requestId) return;

                    Swal.fire({
                        title: 'Terjadi Kesalahan',
                        text: 'Detail karyawan gagal dimuat. Silakan coba lagi.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        buttonsStyling: false,
                        customClass: {
                            confirmButton: 'btn btn-success popup-ok'
                        },
                    });
                })
                .finally(function() {
                    if (thisRequest === requestId) root.classList.remove('is-loading');
                });
        });

        /* ---------- Salin nomor (NIK, rekening, NPWP, BPJS) ---------- */

        root.addEventListener('click', function(e) {
            const btn = e.target.closest('[data-copy-field]');
            if (!btn) return;

            const source = root.querySelector('[data-field="' + btn.dataset.copyField + '"]');
            const text = (source?.textContent || '').replace(/\s+/g, '');

            if (!text || text === '-') return;

            navigator.clipboard?.writeText(text).then(function() {
                const icon = btn.querySelector('i');

                btn.classList.add('is-copied');
                icon?.classList.replace('bi-copy', 'bi-check2');

                setTimeout(function() {
                    btn.classList.remove('is-copied');
                    icon?.classList.replace('bi-check2', 'bi-copy');
                }, 1200);
            });
        });

        /* ---------- Reset ke tab pertama saat ditutup ---------- */

        root.addEventListener('hidden.bs.offcanvas', function() {
            root.querySelectorAll('.karyawan-detail__tabs .nav-link')
                .forEach((el) => el.classList.remove('active'));

            root.querySelectorAll('.tab-pane')
                .forEach((el) => el.classList.remove('show', 'active'));

            root.querySelector('.karyawan-detail__tabs .nav-link')?.classList.add('active');
            root.querySelector('.tab-pane')?.classList.add('show', 'active');
        });

    })();
</script>
