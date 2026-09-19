@props(['checked' => false, 'name' => null])
@props([
    'checked' => false,
    'name' => null,
    'id' => null,
])

<label class="app-table-toggle">
    <input
        type="checkbox"
        id="{{ $id }}"
        @checked($checked)
        @if ($name) name="{{ $name }}" @endif
        {{ $attributes }}
    >
    <span class="app-table-toggle__slider"></span>
</label>