@props([
    'name',
    'value' => null,
    'label' => null,
    'placeholder' => '0',
    'required' => false,
    'currency' => 'IDR',
])

@php
    $rawValue = old($name, $value);
@endphp

<div class="mb-3">
    @if($label)
        <label for="{{ $name }}_display" class="form-label fw-semibold">
            {{ $label }}
            @if($required)<span class="text-danger">*</span>@endif
        </label>
    @endif

    {{-- data-currency-group jadi "kunci" buat JS nyambungin display & hidden input --}}
    <div class="input-currency-wrapper" data-currency-group="{{ $name }}">
        <span class="input-currency-prefix">{{ $currency }}</span>
        <span class="input-currency-divider"></span>

        {{-- input yang keliatan user, cuma buat tampilan & ketikan --}}
        <input
            type="text"
            id="{{ $name }}_display"
            class="input-currency-field"
            inputmode="numeric"
            autocomplete="off"
            placeholder="{{ $placeholder }}"
            value="{{ $rawValue !== null ? number_format((float) $rawValue, 0, ',', '.') : '' }}"
            data-currency-display
            {{ $required ? 'required' : '' }}
        >

        {{-- input asli yang dikirim ke backend, isinya angka mentah tanpa titik --}}
        <input
            type="hidden"
            name="{{ $name }}"
            value="{{ $rawValue }}"
            data-currency-input
        >
    </div>
</div>