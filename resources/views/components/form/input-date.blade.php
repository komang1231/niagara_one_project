{{--
    Input tanggal (Air Datepicker). Logikanya ada di resources/js/input-date.js

    Prop tambahan:
      min   : 'today' (default) | 'none' | 'Y-m-d'  -> batas tanggal paling awal
      max   : 'Y-m-d' (opsional)                    -> batas tanggal paling akhir
      after : nama field tanggal lain di form yang sama
              (contoh: Tanggal Tutup -> after="tanggal_buka")
      id    : opsional, kalau name pakai kurung (jadwal[0][tanggal])
--}}
@props([
    'name',
    'value' => '',
    'label' => null,
    'placeholder' => 'Pilih tanggal',
    'required' => false,
    'id' => null,
    'min' => 'today',
    'max' => null,
    'after' => null,
])

@php
    // name "jadwal[0][tanggal]" -> id "jadwal_0_tanggal" & kunci error "jadwal.0.tanggal"
    $inputId = $id ?? trim(str_replace(['][', '[', ']', '.'], '_', $name), '_');
    $errorKey = rtrim(str_replace(['][', '[', ']'], ['.', '.', ''], $name), '.');
@endphp

<div class="{{ $attributes->get('class', 'mb-3') }}">
    @if($label)
        <label for="{{ $inputId }}" class="form-label fw-semibold">
            {{ $label }}

            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <div class="input-date-wrapper">
        <input
            type="text"
            id="{{ $inputId }}"
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            class="form-control input-date {{ $errors->has($errorKey) ? 'is-invalid' : '' }}"
            placeholder="{{ $placeholder }}"
            autocomplete="off"
            data-air-datepicker
            data-min="{{ $min }}"
            @if($max) data-max="{{ $max }}" @endif
            @if($after) data-after="{{ $after }}" @endif
            readonly
            {{ $required ? 'required' : '' }}
        >

        <button
            type="button"
            class="input-date-icon"
            aria-label="Pilih tanggal"
            data-date-trigger
        >
            <i class="bi bi-calendar3"></i>
        </button>
    </div>

    @if($errors->has($errorKey))
        <div class="invalid-feedback d-block">{{ $errors->first($errorKey) }}</div>
    @endif
</div>