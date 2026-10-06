{{-- Opsi shift untuk dropdown = shift aktif dari tabel shift ($legend dari controller) --}}
@php
    // Samakan bentuk data shift (bisa berupa model Shift atau array) -> id, nama, masuk, pulang, lintas, warna
    $shifts = collect($legend)
        ->map(fn($s) => [
            'id' => data_get($s, 'id'),
            'nama' => data_get($s, 'nama'),
            'masuk' => substr((string) (data_get($s, 'masuk') ?? data_get($s, 'jam_masuk')), 0, 5),
            'pulang' => substr((string) (data_get($s, 'pulang') ?? data_get($s, 'jam_pulang')), 0, 5),
            'lintas' => (bool) (data_get($s, 'lintas') ?? data_get($s, 'lintas_hari')),
            'warna' => data_get($s, 'warna'),
        ])
        ->all();

    // Baris awal: dari old() kalau validasi gagal (khusus form ini), kalau tidak 1 baris kosong
    $barisLama = old('_form') === 'offcanvas-tambah-jadwal' ? old('jadwal') : null;
    $baris = is_array($barisLama) && count($barisLama)
        ? $barisLama
        : [['karyawan_id' => [], 'shift_id' => null, 'tanggal' => null]];
    $indexBerikutnya = max(array_keys($baris)) + 1;

    // Error validasi dari form ini? (buat buka lagi offcanvas)
    $adaErrorTambah = $errors->any() && old('_form') === 'offcanvas-tambah-jadwal';
@endphp

<x-offcanvas.form id="offcanvas-tambah-jadwal" title="Tambah Jadwal"
    description="Tiap baris: pilih karyawan, shift, dan tanggalnya. Bisa lebih dari satu baris sekaligus." size="xl">

    {{-- Tombol di footer offcanvas (sejajar Batal & Simpan) - cuma ada di form ini --}}
    <x-slot:extra>
        {{-- Pakai <x-button> (pill) biar sama dengan tombol "Trash" / "Tambah Jadwal" di dashboard --}}
        <x-button variant="outline" icon="bi-plus-lg" data-add-row>
            Tambah Shift
        </x-button>
    </x-slot:extra>

    <form id="offcanvas-tambah-jadwal-form" action="{{ route('jadwal-karyawan.store') }}" method="POST"
        data-jadwal-form novalidate>
        @csrf

        {{-- Penanda form mana yang disubmit (dipakai untuk membuka lagi offcanvas kalau validasi gagal) --}}
        <input type="hidden" name="_form" value="offcanvas-tambah-jadwal">

        @if ($adaErrorTambah)
            <div class="alert alert-danger small py-2">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="jadwal-form-section">
            <h6 class="jadwal-form-section__title">Karyawan, Shift &amp; Tanggal</h6>
            <p class="jadwal-form-section__hint">Satu baris = satu shift di satu tanggal, untuk satu atau banyak karyawan.</p>
        </div>

        {{-- Judul kolom: cukup sekali di atas, biar tiap baris tidak perlu label sendiri --}}
        <div class="jadwal-rows-head" aria-hidden="true">
            <span></span>
            <span>Karyawan <span class="text-danger">*</span></span>
            <span>Shift <span class="text-danger">*</span></span>
            <span>Tanggal <span class="text-danger">*</span></span>
            <span></span>
        </div>

        <div class="jadwal-rows" data-jadwal-rows data-max="31" data-next-index="{{ $indexBerikutnya }}">
            @foreach ($baris as $i => $b)
                @include('jadwal-karyawan._row-jadwal', [
                    'index' => $i,
                    'shifts' => $shifts,
                    'karyawanOptions' => $karyawanOptions,
                    'karyawanIds' => $b['karyawan_id'] ?? [],
                    'shiftId' => $b['shift_id'] ?? null,
                    'tanggal' => $b['tanggal'] ?? null,
                ])
            @endforeach

            {{-- Tombol tipis di bawah baris terakhir, klik = tambah shift --}}
            <button type="button" class="jadwal-add-card" data-add-row data-add-card>
                <i class="bi bi-plus-lg"></i>
                <span>Tambah Shift</span>
            </button>
        </div>

        {{-- Template baris baru (JS ganti __INDEX__ dengan nomor urut) --}}
        <template data-jadwal-template>
            @include('jadwal-karyawan._row-jadwal', [
                'index' => '__INDEX__',
                'shifts' => $shifts,
                'karyawanOptions' => $karyawanOptions,
                'karyawanIds' => [],
                'shiftId' => null,
                'tanggal' => null,
            ])
        </template>

        {{-- Ringkasan: berapa entri jadwal yang akan dibuat (diisi JS) --}}
        <div class="jadwal-summary" data-jadwal-summary hidden></div>

        <small class="text-muted d-block mt-3">
            ⓘ Karyawan yang sudah punya jadwal di tanggal yang sama tidak bisa ditambah lagi. Ubah jadwalnya dengan klik shift di kalender.
        </small>
    </form>
</x-offcanvas.form>

@if ($adaErrorTambah)
    <script>
        // Ada error validasi dari store() -> buka lagi offcanvas supaya pesannya kebaca.
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('btn-tambah-jadwal')?.click();
        });
    </script>
@endif
