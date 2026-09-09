{{--
    Cuma bungkus <table>. Kolom apa aja yang ada di <thead>/<tbody>
    ditulis manual tiap halaman (soalnya kolom Karyawan vs Departemen
    beda), sama kayak <x-filter.bar> gak nentuin field-nya sendiri.
--}}
<div class="app-table-wrapper">
    <table {{ $attributes->class(['app-table']) }}>
        {{ $slot }}
    </table>
</div>