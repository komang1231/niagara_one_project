@extends('layouts.app')

@section('content')
    <x-page-header eyebrow="Rekrutmen" title="Trash Lowongan"
        description="Lowongan yang telah dihapus. Pulihkan atau hapus permanen di sini." icon="bi-trash-fill">
        <x-slot:badges>
            <x-badge>{{ $lowongans->total() }} lowongan</x-badge>
        </x-slot:badges>
        <x-slot:actions>
            <x-button variant="outline" icon="bi-arrow-left" href="{{ route('lowongan.index') }}">Kembali</x-button>
        </x-slot:actions>
    </x-page-header>

    <x-panel>
        <x-table>
            <thead>
                <tr>
                    <th class="app-table__col-no">NO</th>
                    <th>Lowongan</th>
                    <th>Penempatan</th>
                    <th>Periode</th>
                    <th>Status</th>
                    <th class="app-table__col-actions">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($lowongans as $i => $row)
                    <tr>
                        <td class="app-table__col-no">{{ $lowongans->firstItem() + $i }}</td>

                        <td>
                            <x-table.cell-stack :lines="[$row->judul, $row->kode]" />
                        </td>

                        <td>
                            <x-table.cell-stack :lines="[
                                optional($row->cabangKantor)->nama ?? '-',
                                optional($row->departemen)->nama ?? '-',
                            ]" />
                        </td>

                        <td>
                            <x-table.cell-stack :lines="[
                                \Carbon\Carbon::parse($row->tanggal_buka)->format('d M Y'),
                                \Carbon\Carbon::parse($row->tanggal_tutup)->format('d M Y'),
                            ]" />
                        </td>

                        <td>
                            <x-badge :variant="$row->status === 'aktif' ? 'success' : 'neutral'">
                                {{ ucfirst($row->status) }}
                            </x-badge>
                        </td>

                        <td class="app-table__col-actions">
                            <div class="app-table__actions">
                                <form action="{{ route('lowongan.restore', $row->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <x-button type="submit" variant="icon-success" icon="bi-arrow-counterclockwise" />
                                </form>

                                <form action="{{ route('lowongan.force-delete', $row->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <x-button type="submit" variant="icon-danger" icon="bi-trash3-fill" />
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
                Menampilkan {{ $lowongans->firstItem() ?? 0 }}–{{ $lowongans->lastItem() ?? 0 }}
                dari {{ $lowongans->total() }} entri
            </span>
            <x-pagination :paginator="$lowongans" />
        </div>
    </x-panel>
@endsection
