@props([
    'name',
    'value' => '',
    'label' => null,
    'placeholder' => 'Pilih tanggal',
    'required' => false,
])

<div class="mb-3">
    @if($label)
        <label for="{{ $name }}" class="form-label fw-semibold">
            {{ $label }}

            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <div class="input-date-wrapper">
        <input
            type="text"
            id="{{ $name }}"
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            class="form-control input-date"
            placeholder="{{ $placeholder }}"
            autocomplete="off"
            data-air-datepicker
            readonly
            {{ $required ? 'required' : '' }}
        >

        <button
            type="button"
            class="input-date-icon"
            aria-label="Pilih tanggal"
            data-date-trigger="{{ $name }}"
        >
            <i class="bi bi-calendar3"></i>
        </button>
    </div>
</div>