@php
    $karyawanSelect = $karyawanTanpaKontrak->mapWithKeys(
        fn($k) => [$k->id => trim($k->nama . ($k->nip ? ' (' . $k->nip . ')' : ''))],
    );

    // id karyawan => nama status kepegawaian (hanya untuk ditampilkan, tidak dikirim)
    $statusPerKaryawan = $karyawanTanpaKontrak->mapWithKeys(fn($k) => [$k->id => $k->statusKepegawaian?->nama]);
@endphp

<x-offcanvas.form id="offcanvas-kontrak" title="Kontrak Pertama"
    description="Buat kontrak pertama untuk karyawan yang belum memiliki kontrak." size="md">
    <form id="offcanvas-kontrak-form" action="{{ route('kontrak-karyawan.store') }}" method="POST" novalidate
        data-kontrak-form data-durasi-bulan="{{ $durasiBulan }}">
        @csrf

        <x-form.input name="nomor_kontrak_preview" id="kt_create_nomor" label="Nomor Kontrak" value="{{ $previewNomor }}"
            readonly />

        <x-form.select name="karyawan_id" id="kt_create_karyawan_id" label="Karyawan" :options="$karyawanSelect" nullable
            required data-kontrak-karyawan />

        {{-- Mengikuti data karyawan, hanya tampilan --}}
        <x-form.input name="status_kepegawaian_label" id="kt_create_status" label="Status Kepegawaian"
            placeholder="Terisi otomatis setelah karyawan dipilih" readonly data-kontrak-status />
        <script type="application/json" data-kontrak-status-map>@json($statusPerKaryawan)</script>

        <x-form.input-date name="tanggal_mulai" id="kt_create_tanggal_mulai" label="Tanggal Mulai" required />

        <x-form.input name="tanggal_berakhir_preview" id="kt_create_tanggal_berakhir" label="Tanggal Berakhir"
            placeholder="Terisi otomatis setelah tanggal mulai dipilih" readonly data-kontrak-akhir />

        <p class="text-muted small mb-0">
            Tanggal berakhir ditentukan otomatis oleh sistem. Untuk memperpanjang kontrak, gunakan aksi Perpanjang di
            tabel.
        </p>

    </form>
</x-offcanvas.form>
