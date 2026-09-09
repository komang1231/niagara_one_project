{{-- Dipake di dalam @empty milik @forelse, buat state "data tidak ditemukan" --}}
@props(['colspan' => 1, 'text' => 'Data tidak ditemukan.'])

<tr>
    <td colspan="{{ $colspan }}" class="app-table__empty">
        <i class="bi bi-inbox"></i>
        <span>{{ $text }}</span>
    </td>
</tr>