@props([
    'name',
    'label' => null,
    'value' => 'green',
    'required' => false,
    'colors' => ['green', 'red', 'purple', 'blue', 'orange', 'yellow', 'teal', 'pink', 'indigo', 'gray'],
    'inline' => false,
])

@php $inputId = $attributes->get('id', $name); @endphp

<div class="{{ $inline ? 'form-row-inline' : 'mb-3' }}">
    @if ($label)
        <label class="form-label fw-semibold {{ $inline ? 'form-row-inline__label' : '' }}">
            {{ $label }}
            @if ($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <div class="{{ $inline ? 'form-row-inline__control' : '' }}">
        <div class="app-color-picker" data-color-picker>
            <input type="hidden" name="{{ $name }}" id="{{ $inputId }}" value="{{ old($name, $value) }}"
                data-color-value {{ $required ? 'required' : '' }}>

            <button type="button" class="app-color-picker__trigger" data-color-trigger>
                <span class="app-color-picker__dot" data-color-dot></span>
                <i class="bi bi-chevron-down"></i>
            </button>

            <div class="app-color-picker__dropdown" data-color-dropdown>
                @foreach ($colors as $key)
                    <button type="button" class="app-color-picker__swatch" data-color-option="{{ $key }}"
                        data-color-key="{{ $key }}" title="{{ ucfirst($key) }}"></button>
                @endforeach
            </div>
        </div>

        @error($name)
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>
</div>
