@extends('layouts.app')

@section('content')
    <x-page-header eyebrow="Cuti" title="Trash Cuti"
        description="Cuti yang telah dihapus. Pulihkan atau hapus permanen di sini." icon="bi-trash-fill">
        <x-slot:badges>
            <x-badge>{{ $cutis->total() }} cuti</x-badge>
        </x-slot:badges>

        <x-slot:actions>
            <x-button variant="outline" icon="bi-arrow-left" href="{{ route('cuti.index') }}">
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
                    <th>Nama Cuti</th>
                    <th>Kuota Cuti</th>
                    <th>Status</th>
                    <th class="app-table__col-actions">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($cutis as $i => $row)
                    <tr>
                        <td class="app-table__col-no">
                            {{ $cutis->firstItem() + $i }}
                        </td>

                        <td class="fw-semibold">
                            {{ $row->kode }}
                        </td>

                        <td>
                            {{ $row->nama }}
                        </td>

                        <td>
                            {{ $row->kuota_hari_default }} hari
                        </td>

                        <td>
                            <x-badge :variant="$row->status === 'aktif' ? 'success' : 'neutral'">
                                {{ ucfirst($row->status) }}
                            </x-badge>
                        </td>

                        <td class="app-table__col-actions">
                            <div class="app-table__actions">
                                {{-- Restore --}}
                                <form action="{{ route('cuti.restore', $row->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <x-button type="submit" variant="icon-success" icon="bi-arrow-counterclockwise"
                                        onclick="return confirm('Pulihkan cuti ini?')" />
                                </form>

                                {{-- Hapus permanen --}}
                                <form action="{{ route('cuti.force-delete', $row->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <x-button type="submit" variant="icon-danger" icon="bi-trash"
                                        onclick="return confirm('Cuti akan dihapus permanen. Lanjutkan?')" />
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
                {{ $cutis->firstItem() ?? 0 }}–{{ $cutis->lastItem() ?? 0 }}
                dari
                {{ $cutis->total() }}
                entri
            </span>

            <x-pagination :paginator="$cutis" />
        </div>
    </x-panel>
@endsection
