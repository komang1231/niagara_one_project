<x-offcanvas.form id="offcanvas-rekrutmen-edit" title="Edit Rekrutmen"
    description="Perbarui informasi kandidat dan proses rekrutmen." size="lg">
    <form id="offcanvas-rekrutmen-edit-form" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <h6 class="fw-semibold mb-1">Informasi Kandidat</h6>
            <p class="text-muted small mb-0">
                Informasi dasar kandidat.
            </p>
        </div>

        <x-form.input name="kode" label="Kode Rekrutmen" readonly inline />

        <x-form.input name="nama" label="Nama Kandidat" placeholder="Nama kandidat" required inline />

        <x-form.input name="email" label="Email" type="email" placeholder="nama@email.com" required inline />

        <x-form.input name="no_tlp" label="No. Telepon" placeholder="08xxxxxxxxxx" required inline />

        <hr class="my-4">

        <div class="mb-3">
            <h6 class="fw-semibold mb-1">Posisi yang Dilamar</h6>
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
        </div>

        <x-form.file-upload name="file_cv" label="CV" accept=".pdf,.doc,.docx" max-files="1" max-size="20" />

        <div class="small text-muted mb-3" id="current-cv">
            CV saat ini akan tetap digunakan jika tidak mengunggah file baru.
        </div>

        <hr class="my-4">

        <x-form.select name="status_rekrutmen" label="Tahap Rekrutmen" :options="[
            'pelamar' => 'Pelamar',
            'screening' => 'Screening',
            'interview' => 'Interview',
            'offering' => 'Offering',
            'diterima' => 'Diterima',
            'ditolak' => 'Ditolak',
        ]" required inline />

        <x-form.select name="pool_talent" label="Talent Pool" :options="[
            'rehire' => 'Rehire',
            'blacklist' => 'Blacklist',
        ]" nullable inline />

        <x-form.switch name="status" label="Status Aktif" :checked="true" inline />
    </form>
</x-offcanvas.form>
