{{--
    Dropdown checkbox + tampilan chip (Status & Role di Figma).

    Prop 'accent' opsional buat ganti warna kotak centang per halaman:
    <x-filter.multiselect accent="var(--accent)" ... />
--}}
@props([
    'name',
    'label',
    'options' => [],
    'placeholder' => null,
    'accent' => null,
])

@php
    /*
     * Cara bedain "halaman baru pertama dibuka" vs "user submit tapi
     * semua opsi di-uncheck": checkbox yang ke-uncheck itu GA IKUT
     * kekirim di query string, jadi keduanya sama-sama "request($name)
     * kosong" kalau cuma ngecek itu doang.
     *
     * Makanya dipasang hidden input "{name}_state" yang SELALU ikut
     * kekirim tiap submit. Kalau penanda ini belum ada di query string
     * => halaman baru dibuka => default: semua opsi kecentang (karena
     * data yang ditampilin emang semua status/role).
     */
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

    {{-- penanda "filter ini udah pernah disubmit user", jangan dihapus --}}
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