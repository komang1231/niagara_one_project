{{--
    Offcanvas lihat/edit Jadwal Karyawan.
    Alur sama dengan Surat Peringatan (logic-nya ada di resources/js/jadwal-edit.js):
      1. Dibuka dengan klik shift di kalender -> semua field TERKUNCI (mode lihat), Simpan nonaktif.
      2. Footer kiri: [Edit] [Hapus]. Klik Edit -> field terbuka, tombol Edit & Hapus hilang, Simpan aktif.
      3. Hapus = soft delete (masuk Trash), konfirmasi lewat popup global.
--}}
@php
    // Samakan bentuk data shift (bisa berupa model Shift atau array) -> id, nama, masuk, pulang, lintas, warna
    $shiftEdit = collect($legend)
        ->map(fn($s) => [
            'id' => data_get($s, 'id'),
            'nama' => data_get($s, 'nama'),
            'masuk' => substr((string) (data_get($s, 'masuk') ?? data_get($s, 'jam_masuk')), 0, 5),
            'pulang' => substr((string) (data_get($s, 'pulang') ?? data_get($s, 'jam_pulang')), 0, 5),
            'lintas' => (bool) (data_get($s, 'lintas') ?? data_get($s, 'lintas_hari')),
            'warna' => data_get($s, 'warna'),
        ])
        ->all();
@endphp

<x-offcanvas.form id="offcanvas-jadwal-edit" title="Detail Jadwal"
    description="Lihat jadwal, atau klik Edit untuk mengubahnya." size="md">
    {{-- action diisi JS dari data-update-url chip yang diklik --}}
    <form id="offcanvas-jadwal-edit-form" method="POST" data-edit-form data-jadwal-edit-form novalidate>
        @csrf
        @method('PUT')

        {{-- Penanda form mana yang disubmit --}}
        <input type="hidden" name="_form" value="offcanvas-jadwal-edit">

        <x-form.select name="karyawan_id" id="jadwal_edit_karyawan_id" label="Karyawan" :options="$karyawanOptions" nullable
            required />

        {{-- Shift: jam shift tampil di dropdown (diinisialisasi jadwal-edit.js) --}}
        <div class="mb-3">
            <label for="jadwal_edit_shift_id" class="form-label fw-semibold">
                Shift <span class="text-danger">*</span>
            </label>

            <select name="shift_id" id="jadwal_edit_shift_id" class="form-select jadwal-shift-select"
                data-placeholder="Pilih shift">
                <option value=""></option>
                @foreach ($shiftEdit as $s)
                    <option value="{{ $s['id'] }}" data-nama="{{ $s['nama'] }}" data-masuk="{{ $s['masuk'] }}"
                        data-pulang="{{ $s['pulang'] }}" data-lintas="{{ $s['lintas'] ? 1 : 0 }}"
                        data-warna="{{ $s['warna'] }}">
                        {{ $s['nama'] }} ({{ $s['masuk'] }} - {{ $s['pulang'] }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Data lama yang sudah lewat tetap tampil (ditangani input-date.js), hanya pilihan baru dibatasi min today --}}
        <x-form.input-date name="tanggal" id="jadwal_edit_tanggal" label="Tanggal" required />
    </form>

    {{-- Tombol di kiri footer, berseberangan dengan Batal & Simpan --}}
    <x-slot:extra>
        <div class="d-flex align-items-center gap-2" data-jadwal-view-actions>
            <x-button variant="outline" icon="bi-pencil" data-jadwal-action="edit">Edit</x-button>

            {{-- Hapus (soft delete). action diisi JS dari data-update-url chip yang diklik (URL sama, method DELETE).
                 Konfirmasi "Apakah Anda yakin?" ditangani popup global karena ada @method('DELETE'). --}}
            <form id="offcanvas-jadwal-delete-form" method="POST" class="m-0" data-jadwal-delete-form>
                @csrf
                @method('DELETE')
                <x-button type="submit" variant="danger" icon="bi-trash" data-jadwal-action="delete">Hapus</x-button>
            </form>
        </div>
    </x-slot:extra>
</x-offcanvas.form>
