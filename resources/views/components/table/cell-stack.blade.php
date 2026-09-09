{{--
    Buat 1 kolom yang nyimpen beberapa baris info sekaligus.
    Contoh pemakaian:
    - 2 baris (nama+kode)  : <x-table.cell-stack :lines="[$row['nama'], $row['kode']]" />
    - 3 baris + avatar     : <x-table.cell-stack avatar="Budi" :lines="['Budi Santoso','EMP-001','budi@email.com']" />

    Baris pertama di $lines OTOMATIS jadi tebal (judul), sisanya abu-abu kecil.
    'avatar' cukup dikasih nama orangnya, huruf depannya yang dipajang di lingkaran.
--}}
@props([
    'avatar' => null,
    'lines' => [],
])

<div class="app-table-stack">
    @if ($avatar)
        <div class="app-table-stack__avatar">{{ strtoupper(substr($avatar, 0, 1)) }}</div>
    @endif

    <div class="app-table-stack__lines">
        @foreach ($lines as $i => $line)
            <span class="{{ $i === 0 ? 'app-table-stack__line--title' : 'app-table-stack__line--muted' }}">
                {{ $line }}
            </span>
        @endforeach
    </div>
</div>