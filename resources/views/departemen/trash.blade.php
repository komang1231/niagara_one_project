@extends('layouts.app')

@section('content')
    <x-page-header eyebrow="Struktur Karyawan" title="Trash Departemen"
        description="Departemen yang telah dihapus. Pulihkan atau hapus permanen di sini." icon="bi-trash-fill">
        <x-slot:badges>
            <x-badge>{{ $departemens->total() }} departemen</x-badge>
        </x-slot:badges>

        <x-slot:actions>
            <x-button variant="outline" icon="bi-arrow-left" href="{{ route('departemen.index') }}">
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
                    <th>Nama Departemen</th>
                    <th>Status</th>
                    <th class="app-table__col-actions">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($departemens as $i => $row)
                    <tr>
                        <td class="app-table__col-no">
                            {{ $departemens->firstItem() + $i }}
                        </td>

                        <td class="fw-semibold">{{ $row->kode }}</td>
                        <td>{{ $row->nama }}</td>

                        <td>
                            <x-badge :variant="$row->status === 'aktif' ? 'success' : 'neutral'">
                                {{ ucfirst($row->status) }}
                            </x-badge>
                        </td>

                        <td class="app-table__col-actions">
                            <div class="app-table__actions">
                                {{-- Restore --}}
                                <form action="{{ route('departemen.restore', $row->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <x-button type="submit" variant="icon-success" icon="bi-arrow-counterclockwise"
                                        onclick="return confirm('Pulihkan departemen ini?')" />
                                </form>

                                {{-- Hapus permanen --}}
                                @if (Route::has('departemen.force-delete'))
                                    <form action="{{ route('departemen.force-delete', $row->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <x-button type="submit" variant="icon-danger" icon="bi-trash"
                                            onclick="return confirm('Departemen akan dihapus permanen. Lanjutkan?')" />
                                    </form>
                                @endif
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
                Menampilkan {{ $departemens->firstItem() ?? 0 }}–{{ $departemens->lastItem() ?? 0 }}
                dari {{ $departemens->total() }} entri
            </span>


        </div>
    </x-panel>
@endsection
