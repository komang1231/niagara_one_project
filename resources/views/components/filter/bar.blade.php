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

        {{--
        Penanda "form ini udah pernah di-submit user". Selalu ikut kekirim
        tiap kali tombol Search diklik, TERLEPAS dari isi field apapun.
        Beda sama request()->anyFilled(), karena status[]/role[ defaultny semua kecentang.
    --}}
    <input type="hidden" name="filtered" value="1">

    <div class="app-filter-bar__actions">
        <x-button type="submit" variant="search" icon="bi-search">Search</x-button>
      
       @if (request()->has('filtered'))
            <a href="{{ request()->url() }}" class="app-filter-clear">Clear All</a>
        @endif
    </div>
</form>