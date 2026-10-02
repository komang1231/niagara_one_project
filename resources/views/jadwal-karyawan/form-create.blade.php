{{-- Opsi shift untuk dropdown = shift aktif dari tabel shift ($legend dari controller) --}}
@php
    $shifts = $legend;

    // Baris awal: dari old() kalau validasi gagal, kalau tidak 1 baris kosong
    $barisLama = old('jadwal');
    $baris = is_array($barisLama) && count($barisLama) ? $barisLama : [['shift_id' => null, 'tanggal' => null]];
    $indexBerikutnya = max(array_keys($baris)) + 1;
@endphp

<x-offcanvas.form id="offcanvas-tambah-jadwal" title="Tambah Jadwal"
    description="Pilih karyawan, lalu tentukan shift dan tanggalnya. Bisa lebih dari satu shift sekaligus." size="xl">

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

        @if ($errors->any())
            <div class="alert alert-danger small py-2">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Karyawan (boleh banyak) --}}
        <div class="mb-4">
            <label for="jadwal_karyawan_id" class="form-label fw-semibold">
                Karyawan <span class="text-danger">*</span>
            </label>

            <select name="karyawan_id[]" id="jadwal_karyawan_id" class="form-select select2" multiple
                data-placeholder="Pilih karyawan" data-search-placeholder="Cari karyawan...">
                @foreach ($karyawanOptions as $id => $label)
                    <option value="{{ $id }}" @selected(in_array((string) $id, array_map('strval', (array) old('karyawan_id', []))))>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            <div class="invalid-feedback" data-error="karyawan"></div>
        </div>

        {{-- Daftar baris shift + tanggal --}}
        <div class="jadwal-form-section">
            <h6 class="jadwal-form-section__title">Shift &amp; Tanggal</h6>
            <p class="jadwal-form-section__hint">Satu baris = satu shift di satu tanggal.</p>
        </div>

        {{-- Grid kartu: 2-3 kartu per baris (otomatis ngikut lebar offcanvas) --}}
        <div class="jadwal-rows" data-jadwal-rows data-max="31" data-next-index="{{ $indexBerikutnya }}">
            @foreach ($baris as $i => $b)
                @include('jadwal-karyawan._row-jadwal', [
                    'index' => $i,
                    'shifts' => $shifts,
                    'shiftId' => $b['shift_id'] ?? null,
                    'tanggal' => $b['tanggal'] ?? null,
                ])
            @endforeach

            {{-- Kartu placeholder: selalu di sebelah kartu terakhir, klik = tambah shift --}}
            <button type="button" class="jadwal-add-card" data-add-row data-add-card>
                <span class="jadwal-add-card__icon"><i class="bi bi-plus-lg"></i></span>
                <span class="jadwal-add-card__title">Tambah Shift</span>
                <span class="jadwal-add-card__hint">Tambahkan shift &amp; tanggal lain</span>
            </button>
        </div>

        {{-- Template baris baru (JS ganti __INDEX__ dengan nomor urut) --}}
        <template data-jadwal-template>
            @include('jadwal-karyawan._row-jadwal', [
                'index' => '__INDEX__',
                'shifts' => $shifts,
                'shiftId' => null,
                'tanggal' => null,
            ])
        </template>


        {{-- Ringkasan: berapa entri jadwal yang akan dibuat (diisi JS) --}}
        <div class="jadwal-summary" data-jadwal-summary hidden></div>

        <small class="text-muted d-block mt-3">
            ⓘ Jika karyawan sudah punya jadwal di tanggal yang sama, shift-nya akan diganti.
        </small>
    </form>
</x-offcanvas.form>

@if ($errors->any())
    <script>
        // Ada error validasi dari store() -> buka lagi offcanvas supaya pesannya kebaca.
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('btn-tambah-jadwal')?.click();
        });
    </script>
@endif