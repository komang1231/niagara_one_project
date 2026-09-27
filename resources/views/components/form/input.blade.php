@props([
    'name',
    'label' => null,
    'placeholder' => '',
    'value' => null,
    'required' => false,
    'readonly' => false,
    'type' => 'text',
    'inline' => false, // true = label di kiri, input di kanan (kayak Connecteam)
])

<div class="{{ $inline ? 'form-row-inline' : 'mb-3' }}">
    @if ($label)
        <label for="{{ $attributes->get('id', $name) }}"
            class="form-label fw-semibold {{ $inline ? 'form-row-inline__label' : '' }}">
            {{ $label }}
            @if ($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <div class="{{ $inline ? 'form-row-inline__control' : '' }}">
        <input type="{{ $type }}" name="{{ $name }}" value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}" {{ $required ? 'required' : '' }} {{ $readonly ? 'readonly' : '' }}
            {{ $attributes->merge(['id' => $name, 'class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }}>

        @error($name)
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
