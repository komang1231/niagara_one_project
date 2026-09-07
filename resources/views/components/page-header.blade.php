@props([
    'eyebrow' => null,
    'title' => '',
    'description' => null,
    'icon' => 'bi-grid-1x2-fill',
])

<div class="page-header">

    <div class="page-header-content">
        @if ($eyebrow)
            <p class="page-header-eyebrow">{{ strtoupper($eyebrow) }}</p>
        @endif

        <h1 class="page-header-title">{{ $title }}</h1>

        @if ($description)
            <p class="page-header-description">{{ $description }}</p>
        @endif

        {{-- Baris badge + tombol, cuma muncul kalau slot-nya diisi --}}
        @if ($badges->isNotEmpty() || $actions->isNotEmpty())
            <div class="page-header-row">
                @if ($badges->isNotEmpty())
                    <div class="page-header-badges">
                        {{ $badges }}
                    </div>
                @endif

                @if ($actions->isNotEmpty())
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