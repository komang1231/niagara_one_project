{{--
    1 baris "Karyawan + Shift + Tanggal" di form Tambah Jadwal (sejajar: No | Karyawan | Shift | Tanggal | Hapus).
    Dipakai 2x oleh form-create.blade.php:
      - sebagai <template> (index = __INDEX__, diganti JS saat klik "Tambah Shift")
      - sebagai baris awal / baris dari old() kalau validasi gagal

    Judul kolom ada sekali di form-create, jadi di sini tanpa label.

    Variabel: $index, $shifts (daftar shift aktif), $karyawanOptions (id => nama),
              $karyawanIds (karyawan terpilih di baris ini), $shiftId, $tanggal
--}}
@php
    $idBase = 'jadwal_' . $index;
    $karyawanIds = array_map('strval', (array) ($karyawanIds ?? []));
@endphp

<div class="jadwal-form-row" data-jadwal-row>
    {{-- Nomor urut (diisi JS) --}}
    <span class="jadwal-form-row__no" data-row-number>1</span>

    {{-- Karyawan: boleh banyak. Opsi "Pilih semua" ditambahkan oleh JS (jadwal-form.js) --}}
    <div class="jadwal-form-row__field">
        <select name="jadwal[{{ $index }}][karyawan_id][]" id="{{ $idBase }}_karyawan"
            class="form-select jadwal-karyawan-select" multiple data-placeholder="Pilih karyawan"
            aria-label="Karyawan">
            @foreach ($karyawanOptions as $id => $label)
                <option value="{{ $id }}" @selected(in_array((string) $id, $karyawanIds, true))>
                    {{ $label }}
                </option>
            @endforeach
        </select>

        @error('jadwal.' . $index . '.karyawan_id')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
        <div class="invalid-feedback" data-error="karyawan"></div>
    </div>

    {{-- Shift: opsi diambil dari tabel shift (hasil CRUD Shift), jam shift tampil di dropdown --}}
    <div class="jadwal-form-row__field">
        <select name="jadwal[{{ $index }}][shift_id]" id="{{ $idBase }}_shift" class="form-select jadwal-shift-select"
            data-placeholder="Pilih shift" aria-label="Shift">
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
    <div class="jadwal-form-row__field">
        <x-form.input-date :name="'jadwal[' . $index . '][tanggal]'" :id="$idBase . '_tanggal'" :value="$tanggal" class="mb-0"
            required />
        <div class="invalid-feedback" data-error="tanggal"></div>
    </div>

    <button type="button" class="jadwal-form-row__remove" data-remove-row aria-label="Hapus jadwal ini">
        <i class="bi bi-trash3"></i>
    </button>

    {{-- Info khusus shift LINTAS HARI (diisi JS), 1 baris tipis di bawah Karyawan / Shift / Tanggal --}}
    <div class="jadwal-info" data-row-info hidden></div>
</div>
