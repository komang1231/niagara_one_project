@props([
    'variant' => 'outline',
    'icon' => null,
    'href' => null,
    'type' => 'button',
])

@php
    // Class warna berdasarkan variant - tinggal nambah 1 blok elseif
    // kalau nanti butuh variant baru.
    if ($variant === 'primary') {
        $variantClass = 'btn-app-primary';
    } elseif ($variant === 'search') {
        $variantClass = 'btn-app-search';
    } elseif ($variant === 'danger') {
        $variantClass = 'btn-app-danger';
    } elseif ($variant === 'icon-edit') {
        $variantClass = 'btn-app-icon btn-app-icon-edit';
    } elseif ($variant === 'icon-view') {//untuk icon mata
        $variantClass = 'btn-app-icon btn-app-icon-view';
    } elseif ($variant === 'icon-danger') {
        $variantClass = 'btn-app-icon btn-app-icon-danger';
    } else {
        $variantClass = 'btn-app-outline';
    }

    // Icon-only button (buat aksi tabel) gak butuh teks di slot.
    $isIconOnly = str_starts_with($variant, 'icon-');
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class(['btn-app', $variantClass]) }}>
        @if ($icon)
            <i class="bi {{ $icon }}"></i>
        @endif
        @if (!$isIconOnly)
            <span>{{ $slot }}</span>
        @endif
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->class(['btn-app', $variantClass]) }}>
        @if ($icon)
            <i class="bi {{ $icon }}"></i>
        @endif
        @if (!$isIconOnly)
            <span>{{ $slot }}</span>
        @endif
    </button>
@endif
