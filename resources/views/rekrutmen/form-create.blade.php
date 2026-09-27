<x-offcanvas.form id="offcanvas-tambah-rekrutmen" title="Tambah Rekrutmen"
    description="Tambahkan kandidat baru ke proses rekrutmen." size="lg">
    <form id="offcanvas-tambah-rekrutmen-form" action="{{ route('rekrutmen.store') }}" method="POST"
        enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <h6 class="fw-semibold mb-1">Informasi Kandidat</h6>
            <p class="text-muted small mb-0">
                Masukkan informasi dasar kandidat.
            </p>
        </div>

        <x-form.input name="kode" label="Kode Rekrutmen" value="{{ $previewKode }}" readonly inline />

        <x-form.input name="nama" label="Nama Kandidat" placeholder="Contoh: Komang Ayu" required inline />

        <x-form.input name="email" label="Email" type="email" placeholder="nama@email.com" required inline />

        <x-form.input name="no_tlp" label="No. Telepon" placeholder="08xxxxxxxxxx" required inline />

        <hr class="my-4">

        <div class="mb-3">
            <h6 class="fw-semibold mb-1">Posisi yang Dilamar</h6>
            <p class="text-muted small mb-0">
                Tentukan posisi dan penempatan kandidat.
            </p>
        </div>

        <x-form.select name="lowongan_id" label="Lowongan" :options="$lowongans->pluck('nama', 'id')" nullable required inline />

        <x-form.select name="departemen_id" label="Departemen" :options="$departemens->pluck('nama', 'id')" required inline />

        <x-form.select name="divisi_id" label="Divisi" :options="$divisis->pluck('nama', 'id')" nullable inline />

        <x-form.select name="section_id" label="Section" :options="$sections->pluck('nama', 'id')" nullable inline />

        <x-form.select name="job_position_id" label="Posisi" :options="$jobPositions->pluck('nama', 'id')" nullable inline />

        <x-form.select name="job_level_id" label="Job Level" :options="$jobLevels->pluck('nama', 'id')" required inline />

        <x-form.select name="cabang_kantor_id" label="Cabang Kantor" :options="$cabangKantors->pluck('nama', 'id')" required inline />

        <x-form.select name="jenjang_pendidikan_id" label="Pendidikan" :options="$jenjangPendidikans->pluck('nama', 'id')" required inline />

        <x-form.select name="sumber_pelamar_id" label="Sumber Pelamar" :options="$sumberPelamars->pluck('nama', 'id')" required inline />

        <hr class="my-4">

        <div class="mb-3">
            <h6 class="fw-semibold mb-1">Dokumen Kandidat</h6>
            <p class="text-muted small mb-0">
                Upload CV kandidat dalam format PDF atau Word.
            </p>
        </div>

        <x-form.file-upload name="file_cv" label="CV" accept=".pdf,.doc,.docx" max-files="1" max-size="20"
            required />

        <hr class="my-4">

        <div class="mb-3">
            <h6 class="fw-semibold mb-1">Status Rekrutmen</h6>
        </div>

        <x-form.select name="status_rekrutmen" label="Tahap Rekrutmen" :options="[
            'pelamar' => 'Pelamar',
            'screening' => 'Screening',
            'interview' => 'Interview',
            'offering' => 'Offering',
            'diterima' => 'Diterima',
            'ditolak' => 'Ditolak',
        ]" selected="pelamar" required
            inline />

        <x-form.select name="pool_talent" label="Talent Pool" :options="[
            'rehire' => 'Rehire',
            'blacklist' => 'Blacklist',
        ]" nullable inline />

        <x-form.switch name="status" label="Status Aktif" :checked="true" inline />
    </form>
</x-offcanvas.form>
