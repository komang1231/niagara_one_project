@props(['checked' => false, 'name' => null])

<label class="app-table-toggle">
    <input type="checkbox" @checked($checked) @if ($name) name="{{ $name }}" @endif>
    <span class="app-table-toggle__slider"></span>
</label>