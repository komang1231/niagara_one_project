@props([
    'id' => 'offcanvas-detail', // id unik, wajib beda tiap menu (contoh: 'offcanvas-karyawan-detail')
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
@endphp

<div
    class="offcanvas offcanvas-{{ $position }} app-offcanvas-detail"
    tabindex="-1"
    id="{{ $id }}"
    aria-labelledby="{{ $id }}-label"
    style="width: {{ $width }};"
>
    {{-- HEADER: fleksibel, isi avatar + ringkasan beda-beda tiap menu --}}
    <div class="app-offcanvas-detail__header">
        <button
            type="button"
            class="btn-close app-offcanvas-detail__close"
            data-bs-dismiss="offcanvas"
            aria-label="Tutup"
        ></button>

        {{ $header }}
    </div>

    {{-- Tabs, kalau menu ini pakai tab --}}
    @isset($tabs)
        <div class="app-offcanvas-detail__tabs">
            {{ $tabs }}
        </div>
    @endisset

    <div class="offcanvas-body">
        {{ $slot }}
    </div>
</div>