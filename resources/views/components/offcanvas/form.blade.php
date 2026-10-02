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
        'xl' => '950px',
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
        {{-- Slot opsional "extra": tombol tambahan di kiri footer (contoh: Tambah Shift di Jadwal Karyawan).
             Kalau menu lain tidak mengisi slot ini, footer tampil seperti biasa. --}}
        @if (isset($extra) && !$extra->isEmpty())
            <div class="me-auto">
                {{ $extra }}
            </div>
        @endif

        {{-- Batal & Simpan pakai <x-button> (pill) biar sama dengan tombol di dashboard.
             form="{{ $formId }}" -> menyambungkan tombol Simpan (yang posisinya di luar <form>)
             ke <form id="{{ $formId }}"> di body. --}}
        <x-button variant="outline" data-bs-dismiss="offcanvas">
            Batal
        </x-button>

        <x-button variant="primary" type="submit" icon="bi-check-lg" form="{{ $formId }}">
            Simpan
        </x-button>
    </div> 
</div>