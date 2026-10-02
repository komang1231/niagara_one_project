{{--
    1 baris "Shift + Tanggal" di form Tambah Jadwal.
    Dipakai 2x oleh form-create.blade.php:
      - sebagai <template> (index = __INDEX__, diganti JS saat klik "Tambah Shift")
      - sebagai baris awal / baris dari old() kalau validasi gagal

    Variabel: $index, $shifts (daftar shift aktif), $shiftId, $tanggal
--}}
@php
    $idBase = 'jadwal_' . $index;
@endphp

<div class="jadwal-form-row" data-jadwal-row>
    <div class="jadwal-form-row__head">
        <span class="jadwal-form-row__title">
            <i class="bi bi-calendar-plus"></i>
            Jadwal <span data-row-number>1</span>
        </span>

        <button type="button" class="jadwal-form-row__remove" data-remove-row aria-label="Hapus jadwal ini">
            <i class="bi bi-trash3"></i>
        </button>
    </div>

    {{-- Shift: opsi diambil dari tabel shift (hasil CRUD Shift), bukan hard code --}}
    <div class="mb-3">
        <label for="{{ $idBase }}_shift" class="form-label fw-semibold">
            Shift <span class="text-danger">*</span>
        </label>

        <select name="jadwal[{{ $index }}][shift_id]" id="{{ $idBase }}_shift" class="form-select jadwal-shift-select"
            data-placeholder="Pilih shift">
            <option value=""></option>
            @foreach ($shifts as $s)
                <option value="{{ $s['id'] }}" data-nama="{{ $s['nama'] }}" data-masuk="{{ $s['masuk'] }}"
                    data-pulang="{{ $s['pulang'] }}" data-lintas="{{ $s['lintas'] ? 1 : 0 }}"
                    data-warna="{{ $s['warna'] }}" @selected((string) $shiftId === (string) $s['id'])>
                    {{ $s['nama'] }} ({{ $s['masuk'] }} - {{ $s['pulang'] }})
                </option>
            @endforeach
        </select>

        @error('jadwal.' . $index . '.shift_id')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
        <div class="invalid-feedback" data-error="shift"></div>
    </div>

    {{-- Tanggal: cuma 1 tanggal per baris, tidak bisa lampau --}}
    <x-form.input-date :name="'jadwal[' . $index . '][tanggal]'" :id="$idBase . '_tanggal'" label="Tanggal" :value="$tanggal"
        class="mb-2" required />
    <div class="invalid-feedback mb-2" data-error="tanggal"></div>

    {{-- Info shift terpilih (diisi JS): jam kerja + penanda lintas hari --}}
    <div class="jadwal-info" data-row-info hidden></div>
</div>