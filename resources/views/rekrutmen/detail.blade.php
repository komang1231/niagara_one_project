<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas-rekrutmen-detail"
    aria-labelledby="offcanvas-rekrutmen-detail-label" style="width: 650px;">
    <div class="offcanvas-header border-bottom">
        <div>
            <h5 class="offcanvas-title mb-1" id="offcanvas-rekrutmen-detail-label">
                Detail Rekrutmen
            </h5>

            <p class="text-muted small mb-0" id="detail-kode">
                -
            </p>
        </div>

        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Tutup"></button>
    </div>

    <div class="offcanvas-body">

        {{-- Header kandidat --}}
        <div class="d-flex align-items-start gap-3 mb-4">
            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                style="
                    width: 48px;
                    height: 48px;
                    background: var(--primary-50);
                    color: var(--primary);
                    font-size: 20px;
                ">
                <i class="bi bi-person"></i>
            </div>

            <div class="min-w-0">
                <h5 class="mb-1 fw-semibold" id="detail-nama">
                    -
                </h5>

                <div class="text-muted small" id="detail-email">
                    -
                </div>

                <div class="text-muted small" id="detail-no-tlp">
                    -
                </div>
            </div>
        </div>

        {{-- Status --}}
        <div class="mb-4">
            <div class="text-muted small mb-2">
                Status Rekrutmen
            </div>

            <div class="d-flex align-items-center gap-2">
                <x-badge id="detail-status-rekrutmen" variant="neutral">
                    -
                </x-badge>

                <x-badge id="detail-status" variant="success">
                    -
                </x-badge>
            </div>
        </div>

        <hr class="my-4">

        {{-- Posisi --}}
        <div class="mb-4">
            <h6 class="fw-semibold mb-3">
                Posisi & Penempatan
            </h6>

            <div class="detail-list">

                <div class="detail-list__row">
                    <span>Lowongan</span>
                    <strong id="detail-lowongan">-</strong>
                </div>

                <div class="detail-list__row">
                    <span>Departemen</span>
                    <strong id="detail-departemen">-</strong>
                </div>

                <div class="detail-list__row">
                    <span>Divisi</span>
                    <strong id="detail-divisi">-</strong>
                </div>

                <div class="detail-list__row">
                    <span>Section</span>
                    <strong id="detail-section">-</strong>
                </div>

                <div class="detail-list__row">
                    <span>Posisi</span>
                    <strong id="detail-job-position">-</strong>
                </div>

                <div class="detail-list__row">
                    <span>Job Level</span>
                    <strong id="detail-job-level">-</strong>
                </div>

                <div class="detail-list__row">
                    <span>Cabang Kantor</span>
                    <strong id="detail-cabang">-</strong>
                </div>

                <div class="detail-list__row">
                    <span>Pendidikan</span>
                    <strong id="detail-pendidikan">-</strong>
                </div>

                <div class="detail-list__row">
                    <span>Sumber Pelamar</span>
                    <strong id="detail-sumber">-</strong>
                </div>

                <div class="detail-list__row">
                    <span>Talent Pool</span>
                    <strong id="detail-pool">-</strong>
                </div>

            </div>
        </div>

        <hr class="my-4">

        {{-- Dokumen --}}
        <div>
            <h6 class="fw-semibold mb-3">
                Dokumen
            </h6>

            <div class="detail-document" id="detail-cv">
                <div class="detail-document__icon">
                    <i class="bi bi-file-earmark-pdf"></i>
                </div>

                <div class="detail-document__info">
                    <div class="detail-document__name">
                        CV Kandidat
                    </div>

                    <div class="detail-document__file" id="detail-cv-name">
                        -
                    </div>
                </div>

                <a href="#" target="_blank" class="btn btn-sm btn-outline-secondary" id="detail-cv-link">
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

        const url = button.dataset.detailUrl;

        fetch(url, {
                headers: {
                    'Accept': 'application/json',
                },
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(
                        'Gagal mengambil detail rekrutmen.'
                    );
                }

                return response.json();
            })
            .then(data => {

                document.getElementById('detail-kode').textContent =
                    data.kode ?? '-';

                document.getElementById('detail-nama').textContent =
                    data.nama ?? '-';

                document.getElementById('detail-email').textContent =
                    data.email ?? '-';

                document.getElementById('detail-no-tlp').textContent =
                    data.no_tlp ?? '-';

                document.getElementById('detail-lowongan').textContent =
                    data.lowongan ?? '-';

                document.getElementById('detail-departemen').textContent =
                    data.departemen ?? '-';

                document.getElementById('detail-divisi').textContent =
                    data.divisi ?? '-';

                document.getElementById('detail-section').textContent =
                    data.section ?? '-';

                document.getElementById('detail-job-position').textContent =
                    data.job_position ?? '-';

                document.getElementById('detail-job-level').textContent =
                    data.job_level ?? '-';

                document.getElementById('detail-cabang').textContent =
                    data.cabang ?? '-';

                document.getElementById('detail-pendidikan').textContent =
                    data.pendidikan ?? '-';

                document.getElementById('detail-sumber').textContent =
                    data.sumber ?? '-';

                document.getElementById('detail-pool').textContent =
                    data.pool_talent
                        ? data.pool_talent === 'rehire'
                            ? 'Rehire'
                            : 'Blacklist'
                        : '-';

                document.getElementById(
                    'detail-status-rekrutmen'
                ).textContent =
                    data.status_rekrutmen
                        ? data.status_rekrutmen.charAt(0).toUpperCase() +
                          data.status_rekrutmen.slice(1)
                        : '-';

                document.getElementById(
                    'detail-status'
                ).textContent =
                    data.status === 'aktif'
                        ? 'Aktif'
                        : 'Nonaktif';

                const cvName =
                    document.getElementById('detail-cv-name');

                const cvLink =
                    document.getElementById('detail-cv-link');

                if (data.file_cv) {
                    cvName.textContent = data.file_cv;
                    cvLink.href = data.file_cv;
                    cvLink.classList.remove('d-none');
                } else {
                    cvName.textContent = 'Belum ada CV';
                    cvLink.classList.add('d-none');
                }

            })
            .catch(error => {
                console.error(error);
            });

    });
</script>