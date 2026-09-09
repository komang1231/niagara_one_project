@props([
    'name',
    'label' => null,
    'checked' => false,
])

<div class="mb-3">
    @if ($label)
        <label class="form-label d-block">{{ $label }}</label>
    @endif

    {{-- Nama class sengaja dibuat baru (app-form-switch), bukan pakai app-table-toggle
         yang sudah ada, supaya style switch di form tidak ikut berubah kalau nanti
         style switch di tabel diubah, atau sebaliknya. Kalau kamu mau keduanya
         benar-benar sama persis, tinggal disatukan classnya belakangan. --}}

    {{-- PENTING: input hidden ini wajib ada. Kalau checkbox di-uncheck, browser
         TIDAK mengirim field "{{ $name }}" sama sekali (bukan mengirim 0/false).
         Hidden ini kirim 0 duluan sebagai nilai default, lalu kalau checkbox
         dicentang, value="1" dari checkbox akan menimpanya karena urutan field
         yang sama, checkbox terakhir yang menang. --}}
    <input type="hidden" name="{{ $name }}" value="0">

    <label class="app-form-switch">
        <input type="checkbox" name="{{ $name }}" value="1" @checked($checked)>
        <span class="app-form-switch__slider"></span>
    </label>
</div>