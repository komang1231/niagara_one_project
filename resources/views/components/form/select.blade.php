@props([
    'name', 'label' => null, 'options' => [], 'optionValue' => 'id', 'optionLabel' => 'nama',
    'selected' => null, 'placeholder' => '-- Pilih --', 'nullable' => false, 'required' => false,
])

<div class="mb-3">
    @if($label)
        <label for="{{ $attributes->get('id', $name) }}" class="form-label fw-semibold">
            {{ $label }}
            @if($required) <span class="text-danger">*</span> @endif
        </label>
    @endif

    <select 
        name="{{ $name }}" 
        {{ $required && !$nullable ? 'required' : '' }}
        {{ $attributes->merge(['id' => $name, 'class' => 'form-select' . ($errors->has($name) ? ' is-invalid' : '')]) }}
    >
        @if($nullable || !$required)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach ($options as $option)
            @php
                $value = is_array($option) || is_object($option) ? data_get($option, $optionValue) : $loop->iteration;
                $text = is_array($option) || is_object($option) ? data_get($option, $optionLabel) : $option;
            @endphp
            <option value="{{ $value }}" {{ (string) old($name, $selected) === (string) $value ? 'selected' : '' }}>
                {{ $text }}
            </option>
        @endforeach
    </select>

    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>