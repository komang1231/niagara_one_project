@props([
    'name',
    'label',
    'options' => [],
    'placeholder' => null,
    'accent' => null,
])

@php
    $isTouched = request()->has("{$name}_state");

    $selected = $isTouched
        ? collect(request($name, []))->map(fn ($v) => (string) $v)->all()
        : array_keys($options); // default: semua option kecentang
@endphp

<div
    class="app-filter-field app-filter-field--select app-multiselect"
    data-multiselect
    @if ($accent) style="--multiselect-accent: {{ $accent }}" @endif
>
    <label class="app-filter-label">{{ $label }}</label>

    <input type="hidden" name="{{ $name }}_state" value="1">

    <button type="button" class="app-multiselect__control" data-multiselect-toggle aria-expanded="false">
        <span class="app-multiselect__chips" data-multiselect-chips>
            <span class="app-multiselect__placeholder" data-multiselect-placeholder>
                {{ $placeholder ?? 'Pilih ' . strtolower($label) }}
            </span>
        </span>
        <i class="bi bi-chevron-down app-multiselect__caret"></i>
    </button>

    <div class="app-multiselect__menu" data-multiselect-menu>
        @foreach ($options as $value => $text)
            <label class="app-multiselect__option">
                <input
                    type="checkbox"
                    class="app-multiselect__input"
                    name="{{ $name }}[]"
                    value="{{ $value }}"
                    data-multiselect-input
                    data-label="{{ $text }}"
                    @checked(in_array((string) $value, $selected))
                >
                <span class="app-multiselect__box"><i class="bi bi-check-lg"></i></span>
                <span>{{ $text }}</span>
            </label>
        @endforeach
    </div>
</div>