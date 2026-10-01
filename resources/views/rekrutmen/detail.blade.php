<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas-rekrutmen-detail"
    aria-labelledby="offcanvas-rekrutmen-detail-label" style="width: 650px;">
    <div class="offcanvas-header border-bottom">
        <div>
            <h5 class="offcanvas-title mb-1" id="offcanvas-rekrutmen-detail-label">
                Detail Rekrutmen
            </h5>

            <p class="text-muted small mb-0" id="detail-kode">-</p>
        </div>

        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Tutup"></button>
    </div>

    <div class="offcanvas-body">

        {{-- Header kandidat --}}
        <div class="d-flex align-items-start gap-3 mb-4">
            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                style="width: 48px; height: 48px; background: var(--primary-50); color: var(--primary); font-size: 20px;">
                <i class="bi bi-person"></i>
            </div>

            <div class="min-w-0">
                <h5 class="mb-1 fw-semibold" id="detail-nama">-</h5>
                <div class="text-muted small" id="detail-email">-</div>
                <div class="text-muted small" id="detail-no-tlp">-</div>
            </div>
        </div>

        {{-- Status --}}
        <div class="mb-4">
            <div class="text-muted small mb-2">Status Rekrutmen</div>

            <div class="d-flex align-items-center gap-2">
                <x-badge id="detail-status-rekrutmen" variant="neutral">-</x-badge>
                <x-badge id="detail-status" variant="success">-</x-badge>
            </div>
        </div>

        <hr class="my-4">

        {{-- Posisi --}}
        <div class="mb-4">
            <h6 class="fw-semibold mb-3">Posisi & Penempatan</h6>

            <div class="detail-list">
                <div class="detail-list__row"><span>Lowongan</span><strong id="detail-lowongan">-</strong></div>
                <div class="detail-list__row"><span>Departemen</span><strong id="detail-departemen">-</strong></div>
                <div class="detail-list__row"><span>Divisi</span><strong id="detail-divisi">-</strong></div>
                <div class="detail-list__row"><span>Section</span><strong id="detail-section">-</strong></div>
                <div class="detail-list__row"><span>Posisi</span><strong id="detail-job-position">-</strong></div>
                <div class="detail-list__row"><span>Job Level</span><strong id="detail-job-level">-</strong></div>
                <div class="detail-list__row"><span>Cabang Kantor</span><strong id="detail-cabang">-</strong></div>
                <div class="detail-list__row"><span>Pendidikan</span><strong id="detail-pendidikan">-</strong></div>
                <div class="detail-list__row"><span>Sumber Pelamar</span><strong id="detail-sumber">-</strong></div>
                <div class="detail-list__row"><span>Talent Pool</span><strong id="detail-pool">-</strong></div>
            </div>
        </div>

        <hr class="my-4">

        {{-- Dokumen --}}
        <div>
            <h6 class="fw-semibold mb-3">Dokumen</h6>

            <div class="detail-document">
                <div class="detail-document__icon">
                    <i class="bi bi-file-earmark-pdf"></i>
                </div>

                <div class="detail-document__info">
                    <div class="detail-document__name">CV Kandidat</div>
                    <div class="detail-document__file" id="detail-cv-name">-</div>
                </div>

                <a href="#" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary d-none"
                    id="detail-cv-link">
                    <i class="bi bi-eye me-1"></i>
                    Lihat
                </a>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('click', function(e) {
        const button = e.target.closest('[data-detail-url]');
        if (!button) return;

        const setText = (id, value) => {
            const el = document.getElementById(id);
            if (el) el.textContent = value ?? '-';
        };

        const capitalize = (value) =>
            value ? value.charAt(0).toUpperCase() + value.slice(1) : '-';

        [
            'detail-kode', 'detail-nama', 'detail-email', 'detail-no-tlp',
            'detail-lowongan', 'detail-departemen', 'detail-divisi',
            'detail-section', 'detail-job-position', 'detail-job-level',
            'detail-cabang', 'detail-pendidikan', 'detail-sumber',
            'detail-pool', 'detail-status-rekrutmen', 'detail-status',
            'detail-cv-name',
        ].forEach((id) => setText(id, '-'));

        const cvLink = document.getElementById('detail-cv-link');
        cvLink?.classList.add('d-none');

        fetch(button.dataset.detailUrl, {
                headers: {
                    Accept: 'application/json'
                },
            })
            .then((response) => {
                if (!response.ok) {
                    throw new Error('Gagal mengambil detail rekrutmen.');
                }
                return response.json();
            })
            .then((data) => {
                setText('detail-kode', data.kode);
                setText('detail-nama', data.nama);
                setText('detail-email', data.email);
                setText('detail-no-tlp', data.no_tlp);

                setText('detail-lowongan', data.lowongan);
                setText('detail-departemen', data.departemen);
                setText('detail-divisi', data.divisi);
                setText('detail-section', data.section);
                setText('detail-job-position', data.job_position);
                setText('detail-job-level', data.job_level);
                setText('detail-cabang', data.cabang_kantor);
                setText('detail-pendidikan', data.jenjang_pendidikan);
                setText('detail-sumber', data.sumber_pelamar);

                setText(
                    'detail-pool',
                    data.pool_talent === null || data.pool_talent === '' ?
                    '-' :
                    data.pool_talent
                );

                setText(
                    'detail-status-rekrutmen',
                    capitalize(data.status_rekrutmen)
                );

                setText(
                    'detail-status',
                    data.status === 'aktif' ? 'Aktif' : 'Nonaktif'
                );

                if (data.file_cv) {
                    setText(
                        'detail-cv-name',
                        data.file_cv.split('/').pop()
                    );

                    // URL ini perlu disesuaikan dengan penyimpanan CV.
                    cvLink.href = `/storage/${data.file_cv}`;
                    cvLink.classList.remove('d-none');
                } else {
                    setText('detail-cv-name', 'Belum ada CV');
                }
            })
            .catch((error) => console.error(error));
    });
</script>
