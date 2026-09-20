@extends('layouts.app')

@section('content')
    <x-page-header eyebrow="Struktur Karyawan" title="Trash Divisi"
        description="Divisi yang telah dihapus. Pulihkan atau hapus permanen di sini." icon="bi-trash-fill">

        <x-slot:badges>
            <x-badge>{{ $divisis->total() }} divisi</x-badge>
        </x-slot:badges>

        <x-slot:actions>
            <x-button variant="outline" icon="bi-arrow-left" href="{{ route('divisi.index') }}">
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
                    <th>Nama Divisi</th>
                    <th>Departemen</th>
                    <th>Status</th>
                    <th class="app-table__col-actions">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($divisis as $i => $row)
                    <tr>
                        <td class="app-table__col-no">
                            {{ $divisis->firstItem() + $i }}
                        </td>

                        <td class="fw-semibold">
                            {{ $row->kode }}
                        </td>

                        <td>
                            {{ $row->nama }}
                        </td>

                        <td>
                            {{ $row->departemen?->nama ?? 'N/A' }}
                        </td>

                        <td>
                            <x-badge :variant="$row->status === 'aktif' ? 'success' : 'neutral'">
                                {{ ucfirst($row->status) }}
                            </x-badge>
                        </td>

                        <td class="app-table__col-actions">
                            <div class="app-table__actions">

                                {{-- Restore --}}
                                <form action="{{ route('divisi.restore', $row->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <x-button
                                        type="submit"
                                        variant="icon-success"
                                        icon="bi-arrow-counterclockwise"
                                        onclick="return confirm('Pulihkan divisi ini?')"
                                    />
                                </form>

                                {{-- Hapus permanen --}}
                                @if (Route::has('divisi.force-delete'))
                                    <form action="{{ route('divisi.force-delete', $row->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <x-button
                                            type="submit"
                                            variant="icon-danger"
                                            icon="bi-trash"
                                            onclick="return confirm('Divisi akan dihapus permanen. Lanjutkan?')"
                                        />
                                    </form>
                                @endif

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
                {{ $divisis->firstItem() ?? 0 }}–{{ $divisis->lastItem() ?? 0 }}
                dari
                {{ $divisis->total() }} entri
            </span>

            <x-pagination :paginator="$divisis" />
        </div>
    </x-panel>
@endsection