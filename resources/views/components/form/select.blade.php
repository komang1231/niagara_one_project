@props([
    'name',
    'label' => null,
    'options' => [],
    'optionValue' => 'id',
    'optionLabel' => 'nama',
    'selected' => null,
    'placeholder' => null,
    'nullable' => false,
    'required' => false,
])

@php
    $selectPlaceholder = $placeholder ?? ($label ? 'Pilih ' . $label : 'Pilih');
    $searchPlaceholder = 'Cari ' . strtolower($label ?? 'data') . '...';
    $selectId = $attributes->get('id', $name);
@endphp

<div class="mb-3">

    @if ($label)
        <label for="{{ $selectId }}" class="form-label fw-semibold">
            {{ $label }}

            @if ($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <select
        name="{{ $name }}"
        id="{{ $selectId }}"
        data-placeholder="{{ $selectPlaceholder }}"
        data-search-placeholder="{{ $searchPlaceholder }}"
        {{ $required && !$nullable ? 'required' : '' }}
        {{ $attributes->except(['id', 'class'])->merge([
            'class' => 'form-select select2' . ($errors->has($name) ? ' is-invalid' : ''),
        ]) }}
    >

        @if ($nullable)
            <option value=""></option>
        @endif

        @foreach ($options as $key => $option)

            @php
                if (is_array($option) || is_object($option)) {
                    $value = data_get($option, $optionValue);
                    $text = data_get($option, $optionLabel);
                } else {
                    $value = $key;
                    $text = $option;
                }
            @endphp

            <option
                value="{{ $value }}"
                {{ (string) old($name, $selected) === (string) $value ? 'selected' : '' }}
            >
                {{ $text }}
            </option>

        @endforeach

    </select>

    @error($name)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror

</div>