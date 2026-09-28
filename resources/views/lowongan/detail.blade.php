<x-offcanvas.detail id="offcanvas-lowongan-detail" size="xl">
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
                <div class="col-md-6">
                    <div class="lowongan-detail__label">Cabang Kantor</div>
                    <div class="lowongan-detail__value" data-field="cabang_kantor">-</div>
                </div>
                <div class="col-md-6">
                    <div class="lowongan-detail__label">Job Position</div>
                    <div class="lowongan-detail__value" data-field="job_position">-</div>
                </div>
                <div class="col-12">
                    <div class="lowongan-detail__label">Departemen › Divisi › Section</div>
                    <div class="lowongan-detail__value" data-field="penempatan">-</div>
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
            <div class="lowongan-detail__text" style="white-space: pre-line;" data-field="deskripsi">-</div>
        </section>

        <section class="mb-2">
            <h6 class="lowongan-detail__section-title">Kualifikasi</h6>
            {{-- reuse styling .rte-container biar kontennya (hasil rich text editor) tampil konsisten sama form --}}
            <div class="rte-container lowongan-detail__rich" data-field="kualifikasi">-</div>
        </section>

    </div>
</x-offcanvas.detail>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const el = document.getElementById('offcanvas-lowongan-detail');
        if (!el) return;

        // isi teks ke elemen [data-field="..."]; kosong -> "-"
        function setText(field, value) {
            const target = el.querySelector(`[data-field="${field}"]`);
            if (!target) return;
            target.textContent = value !== null && value !== undefined && value !== '' ? value : '-';
        }

        // kosongkan dulu supaya data lowongan sebelumnya gak sempat kelihatan
        function resetDetail() {
            el.querySelectorAll('[data-field]').forEach((t) => {
                t.textContent = '-';
            });
            const badge = el.querySelector('[data-field="status-badge"]');
            if (badge) badge.className = 'badge';
        }

        function fillDetail(d) {
            setText('judul', d.judul);
            setText('kode', d.kode);

            const badge = el.querySelector('[data-field="status-badge"]');
            if (badge) {
                const aktif = d.status === 'aktif';
                badge.textContent = aktif ? 'Aktif' : 'Nonaktif';
                badge.className = 'badge ' + (aktif ? 'bg-success' : 'bg-secondary');
            }

            setText('permintaan_karyawan', d.permintaan_karyawan);
            setText('job_level', d.job_level);
            setText('cabang_kantor', d.cabang_kantor);
            setText('job_position', d.job_position);
            setText('penempatan', d.penempatan);
            setText('kuota', d.kuota ? `${d.kuota} orang` : '-');
            setText('periode', `${d.tanggal_buka} – ${d.tanggal_tutup}`);
            setText('gaji', `${d.gaji_min} – ${d.gaji_max}`);
            setText('deskripsi', d.deskripsi);

            // Kualifikasi = HTML dari rich text editor (sudah di-whitelist di BE),
            // makanya pakai innerHTML, bukan textContent.
            const kualifikasi = el.querySelector('[data-field="kualifikasi"]');
            const html = (d.kualifikasi || '').trim();
            if (kualifikasi) {
                if (html && html !== '<p></p>') kualifikasi.innerHTML = html;
                else kualifikasi.textContent = '-';
            }
        }

        el.addEventListener('show.bs.offcanvas', function (event) {
            const url = event.relatedTarget?.getAttribute('data-detail-url');
            if (!url) return;

            resetDetail();

            fetch(url, { headers: { Accept: 'application/json' } })
                .then((res) => {
                    if (!res.ok) throw new Error(`HTTP ${res.status}`);
                    return res.json();
                })
                .then(fillDetail)
                .catch((err) => {
                    console.error('Gagal ambil detail lowongan:', err);
                    setText('judul', 'Gagal memuat data');
                });
        });
    });
</script>