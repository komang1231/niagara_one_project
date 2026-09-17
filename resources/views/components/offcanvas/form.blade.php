@props([
    'id' => 'offcanvas-form', // id unik, wajib beda tiap menu (contoh: 'offcanvas-departemen')
    'title' => 'Tambah Data',
    'description' => null,
    'size' => 'md', // pilihan: sm, md, lg, xl
    'position' => 'end', // posisi munculnya offcanvas: start, end, top, bottom
])

@php
    $widthMap = [
        'sm' => '400px',
        'md' => '500px',
        'lg' => '650px',
        'xl' => '800px',
    ];

    $width = $widthMap[$size] ?? $widthMap['md'];
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