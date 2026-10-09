@props(['name', 'label' => null, 'value' => null, 'required' => false, 'step' => 10, 'inline' => false])

@php $inputId = $attributes->get('id', $name); @endphp

<div class="{{ $inline ? 'form-row-inline' : 'mb-3' }}">
    @if ($label)
        <label for="{{ $inputId }}" class="form-label fw-semibold {{ $inline ? 'form-row-inline__label' : '' }}">
            {{ $label }}
            @if ($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <div class="{{ $inline ? 'form-row-inline__control' : '' }}">
        <div class="app-time-picker" data-time-picker data-step="{{ $step }}">
            <input type="text" id="{{ $inputId }}" name="{{ $name }}" value="{{ old($name, $value) }}"
                class="form-control app-time-picker__input{{ $errors->has($name) ? ' is-invalid' : '' }}"
                placeholder="--:--" autocomplete="off" inputmode="numeric" maxlength="5" data-time-input
                {{ $required ? 'required' : '' }}>

            <ul class="app-time-picker__dropdown" data-time-dropdown></ul>
        </div>

        @error($name)
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>