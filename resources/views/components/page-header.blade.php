@props([
    'eyebrow' => null,
    'title' => '',
    'description' => null,
    'icon' => 'bi-grid-1x2-fill',
])

@php
    $badges = $badges ?? null;
    $actions = $actions ?? null;
@endphp

<div class="page-header">

    <div class="page-header-content">
        @if ($eyebrow)
            <p class="page-header-eyebrow">{{ strtoupper($eyebrow) }}</p>
        @endif

        <h1 class="page-header-title">{{ $title }}</h1>

        @if ($description)
            <p class="page-header-description">{{ $description }}</p>
        @endif

        @if (($badges && $badges->isNotEmpty()) || ($actions && $actions->isNotEmpty()))
            <div class="page-header-row">

                @if ($badges && $badges->isNotEmpty())
                    <div class="page-header-badges">
                        {{ $badges }}
                    </div>
                @endif

                @if ($actions && $actions->isNotEmpty())
                    <div class="page-header-actions">
                        {{ $actions }}
                    </div>
                @endif

            </div>
        @endif
    </div>

    {{-- Icon dekoratif di kanan bawah --}}
    <i class="bi {{ $icon }} page-header-decoration"></i>

</div>
