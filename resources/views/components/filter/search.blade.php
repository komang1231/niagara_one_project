@props([
    'name' => 'search',
    'label' => 'Search',
    'placeholder' => 'e. name',
])

<div class="app-filter-field app-filter-field--search">
    <label class="app-filter-label">{{ $label }}</label>
    <div class="app-filter-search">
        <i class="bi bi-search"></i>
        <input
            type="text"
            name="{{ $name }}"
            value="{{ request($name) }}"
            placeholder="{{ $placeholder }}"
            data-filter-search-input
        >
    </div>
</div>