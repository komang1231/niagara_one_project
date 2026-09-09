@props([
    'id' => 'offcanvas-form', // id unik, wajib beda tiap menu (contoh: 'offcanvas-departemen')
    'title' => 'Tambah Data',
    'description' => null,
    'size' => 'md', // pilihan: sm, md, lg, xl
    'position' => 'end', // posisi munculnya offcanvas: start, end, top, bottom
])

@php
    // Mapping nama size ke lebar pixel asli.
    // Kalau nanti mau ubah lebar, cukup ubah di sini, tidak perlu cari-cari ke tiap halaman.
    $widthMap = [
        'sm' => '400px',
        'md' => '500px',
        'lg' => '650px',
        'xl' => '800px',
    ];

    $width = $widthMap[$size] ?? $widthMap['md'];

    // Konvensi: form yang ada di dalam slot WAJIB diberi id "{id-offcanvas}-form".
    // Kenapa? karena tombol Simpan ada di footer, di LUAR <form> itu (lihat penjelasan
    // di bawah), jadi dia butuh atribut HTML "form" untuk tahu form mana yang harus
    // disubmit. Ini bikin komponen ini tetap generic — dia tidak perlu tahu field
    // apa saja yang ada di dalamnya.
    $formId = $id . '-form';
@endphp

<div
    class="offcanvas offcanvas-{{ $position }}"
    tabindex="-1"
    id="{{ $id }}"
    aria-labelledby="{{ $id }}-label"
    style="width: {{ $width }};"
>
    {{-- HEADER --}}
    <div class="offcanvas-header border-bottom">
        <div>
            <h5 class="offcanvas-title mb-0" id="{{ $id }}-label">
                {{ $title }}
            </h5>

            @if ($description)
                <p class="text-muted small mb-0">{{ $description }}</p>
            @endif
        </div>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="offcanvas"
            aria-label="Tutup"
        ></button>
    </div>

    {{--
        BODY: di sinilah field form tiap menu disisipkan (lewat $slot).
        <form> dibuat DI DALAM masing-masing file _form-create.blade.php,
        bukan di sini, supaya component ini tidak ikut campur soal field.
    --}}
    <div class="offcanvas-body">
        {{ $slot }}
    </div>

    {{-- FOOTER --}}
    <div class="offcanvas-footer border-top p-3 d-flex justify-content-end gap-2">
        <button
            type="button"
            class="btn btn-outline-secondary"
            data-bs-dismiss="offcanvas"
        >
            Batal
        </button>

        {{-- form="{{ $formId }}" -> ini yang menyambungkan tombol ini (walau
             posisinya di luar <form>) ke <form id="{{ $formId }}"> di body --}}
        <button type="submit" form="{{ $formId }}" class="btn btn-success">
            Simpan
        </button>
    </div>
</div>