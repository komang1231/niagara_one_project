@props([
    'name',
    'label' => null,
    'checked' => false,
    'id' => null
])

<div class="mb-3">
    @if ($label)
        <label class="form-label d-block">{{ $label }}</label>
    @endif
    
    <input type="hidden" name="{{ $name }}" value="0">

    <label class="app-form-switch">
        <input type="checkbox" name="{{ $name }}" id="{{ $id ?? $name }}" value="1" @checked($checked)>
        <span class="app-form-switch__slider"></span>
    </label>
</div>