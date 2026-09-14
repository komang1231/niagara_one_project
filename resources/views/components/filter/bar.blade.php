@props([
    'ajaxTarget' => null,
])

<form
    method="GET"
    action="{{ request()->url() }}"
    class="app-filter-bar"
    data-filter-form
    @if ($ajaxTarget) data-filter-ajax-target="{{ $ajaxTarget }}" @endif
>
    <div class="app-filter-bar__fields">
        {{ $slot }}
    </div>

    <div class="app-filter-bar__actions">
        <x-button type="submit" variant="search" icon="bi-search">Search</x-button>
        <a href="{{ request()->url() }}" class="app-filter-clear">Clear All</a>
    </div>
</form>