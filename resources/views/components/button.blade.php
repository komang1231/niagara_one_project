@props([
    'variant' => 'outline',
    'icon' => null,
    'href' => null,
    'type' => 'button',
])

@php
    if ($variant === 'primary') {
        $variantClass = 'btn-app-primary';
    } elseif ($variant === 'search') {
        $variantClass = 'btn-app-search';
    } elseif ($variant === 'danger') {
        $variantClass = 'btn-app-danger';
    } elseif ($variant === 'icon-edit') {
        $variantClass = 'btn-app-icon btn-app-icon-edit';
    } elseif ($variant === 'icon-view') {
        $variantClass = 'btn-app-icon btn-app-icon-view';
    } elseif ($variant === 'icon-danger') {
        $variantClass = 'btn-app-icon btn-app-icon-danger';
    } elseif ($variant === 'icon-success') {
        $variantClass = 'btn-app-icon btn-app-icon-success';
    } else {
        $variantClass = 'btn-app-outline';
    }

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