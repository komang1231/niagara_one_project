@props([
    'name',
    'label' => null,
    'value' => 0,
    'min' => 0,
    'max' => 365,
    'step' => 1,
    'required' => false,
])

<div class="input-stepper-wrapper">
    @if($label)
        <label for="{{ $name }}" class="form-label">
            {{ $label }} @if($required)<span class="text-danger">*</span>@endif
        </label>
    @endif

    <div class="input-stepper">
        <button type="button" class="stepper-btn stepper-minus" data-step="-1" tabindex="-1">−</button>

        <input
            type="number"
            id="{{ $name }}"
            name="{{ $name }}"
            class="form-control input-stepper-field"
            value="{{ old($name, $value) }}"
            min="{{ $min }}"
            max="{{ $max }}"
            step="{{ $step }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes }}
        >

        <button type="button" class="stepper-btn stepper-plus" data-step="1" tabindex="-1">+</button>
    </div>

    @error($name)
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>