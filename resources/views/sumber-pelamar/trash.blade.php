@extends('layouts.app')

@section('content')
    <x-page-header eyebrow="Master Data" title="Trash Sumber Pelamar"
        description="Sumber pelamar yang telah dihapus. Pulihkan atau hapus permanen di sini." icon="bi-trash-fill">
        <x-slot:badges>
            <x-badge>{{ $sumberPelamars->total() }} sumber pelamar</x-badge>
        </x-slot:badges>

        <x-slot:actions>
            <x-button variant="outline" icon="bi-arrow-left" href="{{ route('sumber-pelamar.index') }}">
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
                    <th>Nama Sumber Pelamar</th>
                    <th>Status</th>
                    <th class="app-table__col-actions">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($sumberPelamars as $i => $row)
                    <tr>
                        <td class="app-table__col-no">
                            {{ $sumberPelamars->firstItem() + $i }}
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
                                <form action="{{ route('sumber-pelamar.restore', $row->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <x-button type="submit" variant="icon-success" icon="bi-arrow-counterclockwise"
                                     />
                                </form>

                                {{-- Hapus permanen --}}
                                <form action="{{ route('sumber-pelamar.force-delete', $row->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <x-button type="submit" variant="icon-danger" icon="bi-trash"
                                     />
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
                Menampilkan {{ $sumberPelamars->firstItem() ?? 0 }}–{{ $sumberPelamars->lastItem() ?? 0 }}
                dari {{ $sumberPelamars->total() }} entri
            </span>
            <x-pagination :paginator="$sumberPelamars" />

        </div>
    </x-panel>
@endsection
