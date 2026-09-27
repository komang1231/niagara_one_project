<x-offcanvas.detail id="offcanvas-lowongan-detail" size="lg">
    <x-slot:header>
        <div class="lowongan-detail__title-group">
            <h5 class="mb-1" data-field="judul">-</h5>
            <div class="d-flex align-items-center gap-2">
                <span class="text-muted small" data-field="kode">-</span>
                <span class="badge" data-field="status-badge">-</span>
            </div>
        </div>
    </x-slot:header>

    <div class="lowongan-detail">

        <section class="mb-4">
            <h6 class="lowongan-detail__section-title">Informasi Lowongan</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="lowongan-detail__label">Permintaan Karyawan</div>
                    <div class="lowongan-detail__value" data-field="permintaan_karyawan">-</div>
                </div>
                <div class="col-md-6">
                    <div class="lowongan-detail__label">Job Level</div>
                    <div class="lowongan-detail__value" data-field="job_level">-</div>
                </div>
            </div>
        </section>

        <section class="mb-4">
            <h6 class="lowongan-detail__section-title">Penempatan</h6>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="lowongan-detail__label">Cabang Kantor</div>
                    <div class="lowongan-detail__value" data-field="cabang_kantor">-</div>
                </div>
                <div class="col-md-4">
                    <div class="lowongan-detail__label">Departemen</div>
                    <div class="lowongan-detail__value" data-field="departemen">-</div>
                </div>
                <div class="col-md-4">
                    <div class="lowongan-detail__label">Divisi</div>
                    <div class="lowongan-detail__value" data-field="divisi">-</div>
                </div>
                <div class="col-md-4">
                    <div class="lowongan-detail__label">Section</div>
                    <div class="lowongan-detail__value" data-field="section">-</div>
                </div>
                <div class="col-md-4">
                    <div class="lowongan-detail__label">Job Position</div>
                    <div class="lowongan-detail__value" data-field="job_position">-</div>
                </div>
            </div>
        </section>

        <section class="mb-4">
            <h6 class="lowongan-detail__section-title">Detail Lowongan</h6>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="lowongan-detail__label">Kuota</div>
                    <div class="lowongan-detail__value" data-field="kuota">-</div>
                </div>
                <div class="col-md-4">
                    <div class="lowongan-detail__label">Periode</div>
                    <div class="lowongan-detail__value" data-field="periode">-</div>
                </div>
                <div class="col-md-4">
                    <div class="lowongan-detail__label">Rentang Gaji</div>
                    <div class="lowongan-detail__value" data-field="gaji">-</div>
                </div>
            </div>
        </section>

        <section class="mb-4">
            <h6 class="lowongan-detail__section-title">Deskripsi Pekerjaan</h6>
            <div class="lowongan-detail__text" data-field="deskripsi">-</div>
        </section>

        <section class="mb-2">
            <h6 class="lowongan-detail__section-title">Kualifikasi</h6>
            {{-- reuse styling .rte-container biar kontennya (hasil rich text editor) tampil konsisten sama form --}}
            <div class="rte-container lowongan-detail__rich" data-field="kualifikasi">-</div>
        </section>

    </div>
</x-offcanvas.detail>


    .lowongan-detail__title-group h5 {
        font-weight: 700;
        color: var(--color-text-900, #212529);
    }

    .lowongan-detail__section-title {
        text-transform: uppercase;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .04em;
        color: var(--neutral-500, #8a939c);
        margin-bottom: 12px;
    }

    .lowongan-detail__label {
        font-size: 12.5px;
        color: var(--neutral-500, #8a939c);
        margin-bottom: 2px;
    }

    .lowongan-detail__value {
        font-size: 14.5px;
        font-weight: 600;
        color: var(--color-text-900, #212529);
    }

    .lowongan-detail__text {
        font-size: 14px;
        line-height: 1.65;
        color: var(--color-text-800, #343a40);
        white-space: pre-line;
    }

    .lowongan-detail__rich {
        min-height: auto;
        max-height: none;
    }

    .lowongan-detail__rich.is-empty {
        color: var(--neutral-500, #98a2ad);
        font-style: italic;
    }
