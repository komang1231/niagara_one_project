@extends('layouts.app')

@section('content')
    <x-page-header eyebrow="Karyawan" title="Trash Karyawan"
        description="Karyawan yang telah dihapus. Pulihkan atau hapus permanen di sini." icon="bi-trash-fill">
        <x-slot:badges>
            <x-badge>{{ $karyawans->total() }} karyawan</x-badge>
        </x-slot:badges>

        <x-slot:actions>
            <x-button variant="outline" icon="bi-arrow-left" href="{{ route('karyawan.index') }}">
                Kembali
            </x-button>
        </x-slot:actions>
    </x-page-header>

    <x-panel>
        <x-table>
            <thead>
                <tr>
                    <th class="app-table__col-no">NO</th>
                    <th>Karyawan</th>
                    <th>Penempatan</th>
                    <th>Status</th>
                    <th class="app-table__col-actions">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($karyawans as $i => $row)
                    <tr>
                        <td class="app-table__col-no">
                            {{ $karyawans->firstItem() + $i }}
                        </td>

                        <td>
                            <x-table.cell-stack :avatar="$row->nama"
                                :lines="array_values(array_filter([$row->nama, $row->nip, $row->email], 'filled')) " />
                        </td>

                        <td>
                            {{ $row->departemen?->nama ?? '-' }}
                        </td>

                        <td>
                            <x-badge :variant="$row->status === 'aktif' ? 'success' : 'neutral'">
                                {{ ucfirst($row->status) }}
                            </x-badge>
                        </td>

                        <td class="app-table__col-actions">
                            <div class="app-table__actions">
                                {{-- Restore --}}
                                <form action="{{ route('karyawan.restore', $row->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <x-button type="submit" variant="icon-success" icon="bi-arrow-counterclockwise"
                                        onclick="return confirm('Pulihkan karyawan ini?')" />
                                </form>

                                {{-- Hapus permanen --}}
                                <form action="{{ route('karyawan.force-delete', $row->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <x-button type="submit" variant="icon-danger" icon="bi-trash"
                                        onclick="return confirm('Karyawan akan dihapus permanen. Lanjutkan?')" />
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-table.empty-row colspan="5" />
                @endforelse
            </tbody>
        </x-table>

        <div class="app-table-footer">
            <span>
                Menampilkan {{ $karyawans->firstItem() ?? 0 }}–{{ $karyawans->lastItem() ?? 0 }}
                dari {{ $karyawans->total() }} entri
            </span>
            <x-pagination :paginator="$karyawans" />
        </div>
    </x-panel>
@endsection