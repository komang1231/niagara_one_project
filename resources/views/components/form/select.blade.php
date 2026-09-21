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

        @foreach ($options as $key => $option)
            @php
                if (is_array($option) || is_object($option)) {
                    // options berupa koleksi objek/array, misal: $items (bukan pluck)
                    $value = data_get($option, $optionValue);
                    $text = data_get($option, $optionLabel);
                } else {
                    // options hasil pluck('nama', 'id') → key ADALAH id-nya
                    $value = $key;
                    $text = $option;
                }
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