@extends('layouts.app')

@section('content')
    @php
        $stack = fn(...$lines) => array_values(array_filter($lines, 'filled'));
    @endphp

    <x-page-header eyebrow="Kehadiran" title="Trash Shift"
        description="Shift yang telah dihapus. Pulihkan atau hapus permanen di sini." icon="bi-trash-fill">

        <x-slot:badges>
            <x-badge>{{ $shifts->total() }} shift</x-badge>
        </x-slot:badges>

        <x-slot:actions>
            <x-button variant="outline" icon="bi-arrow-left" href="{{ route('shift.index') }}">
                Kembali
            </x-button>
        </x-slot:actions>

    </x-page-header>

    <x-panel>
        <x-table>
            <thead>
                <tr>
                    <th class="app-table__col-no">NO</th>
                    <th>Kode</th>
                    <th>Nama Shift</th>
                    <th>Jam Kerja</th>
                    <th>Status</th>
                    <th class="app-table__col-actions">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($shifts as $i => $row)
                    <tr>
                        <td class="app-table__col-no">
                            {{ $shifts->firstItem() + $i }}
                        </td>

                        <td class="fw-semibold">
                            {{ $row->kode }}
                        </td>

                        {{-- Nama shift jadi badge berwarna sesuai preset --}}
                        <td>
                            <span class="shift-badge"
                                style="background: var(--shift-{{ $row->warna }}-bg); color: var(--shift-{{ $row->warna }}-text); border-color: var(--shift-{{ $row->warna }}-border);">
                                {{ $row->nama }}
                            </span>
                        </td>

                        {{-- Baris 1: jam masuk-pulang (+ tanda lintas hari). Baris 2: istirahat & toleransi --}}
                        <td>
                            <x-table.cell-stack :lines="$stack(
                                \Carbon\Carbon::parse($row->jam_masuk)->format('H:i') .
                                    ' - ' .
                                    \Carbon\Carbon::parse($row->jam_pulang)->format('H:i') .
                                    ($row->lintas_hari ? ' (Lintas hari)' : ''),
                                'Istirahat ' .
                                    $row->istirahat_menit .
                                    ' menit · Toleransi ' .
                                    $row->toleransi_keterlambatan .
                                    ' menit',
                            )" />
                        </td>

                        <td>
                            <x-badge :variant="$row->status === 'aktif' ? 'success' : 'neutral'">
                                {{ ucfirst($row->status) }}
                            </x-badge>
                        </td>

                        <td class="app-table__col-actions">
                            <div class="app-table__actions">
                                {{-- Restore --}}
                                <form action="{{ route('shift.restore', $row->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <x-button type="submit" variant="icon-success" icon="bi-arrow-counterclockwise"
                                        onclick="return confirm('Pulihkan shift ini?')" />
                                </form>

                                {{-- Hapus permanen --}}
                                <form action="{{ route('shift.force-delete', $row->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <x-button type="submit" variant="icon-danger" icon="bi-trash3-fill"
                                        onclick="return confirm('Shift akan dihapus permanen dan tidak bisa dikembalikan lagi. Lanjutkan?')" />
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-table.empty-row colspan="6" />
                @endforelse
            </tbody>
        </x-table>

        <div class="app-table-footer">
            <span>
                Menampilkan
                {{ $shifts->firstItem() ?? 0 }}–{{ $shifts->lastItem() ?? 0 }}
                dari
                {{ $shifts->total() }} entri
            </span>

            <x-pagination :paginator="$shifts" />
        </div>
    </x-panel>
@endsection