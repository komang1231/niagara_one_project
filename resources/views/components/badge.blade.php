@props([
    'variant' => 'neutral',
    'icon' => null,
])

@php
    if ($variant === 'success') {
        $colorClass = 'badge-success';
    } elseif ($variant === 'warning') {
        $colorClass = 'badge-warning';
    } elseif ($variant === 'danger') {
        $colorClass = 'badge-danger';
    } else {
        $colorClass = 'badge-neutral';
    }
@endphp

<span {{ $attributes->class(['app-badge', $colorClass]) }}>
    @if ($icon)
        <i class="bi {{ $icon }}"></i>
    @endif
    {{ $slot }}
</span>