@extends('layouts.app')

@section('content')
    <x-page-header eyebrow="Master Data" title="Trash Status Kepegawaian"
        description="Status kepegawaian yang telah dihapus. Pulihkan atau hapus permanen di sini." icon="bi-trash-fill">
        <x-slot:badges>
            <x-badge>{{ $statusKepegawaians->total() }} status</x-badge>
        </x-slot:badges>

        <x-slot:actions>
            <x-button variant="outline" icon="bi-arrow-left" href="{{ route('status-kepegawaian.index') }}">
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
                    <th>Nama Status</th>
                    <th>Status</th>
                    <th class="app-table__col-actions">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($statusKepegawaians as $i => $row)
                    <tr>
                        <td class="app-table__col-no">
                            {{ $statusKepegawaians->firstItem() + $i }}
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
                                <form action="{{ route('status-kepegawaian.restore', $row->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <x-button type="submit" variant="icon-success" icon="bi-arrow-counterclockwise"
                                        onclick="return confirm('Pulihkan status kepegawaian ini?')" />
                                </form>

                                {{-- Hapus permanen --}}
                                <form action="{{ route('status-kepegawaian.force-delete', $row->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <x-button type="submit" variant="icon-danger" icon="bi-trash"
                                        onclick="return confirm('Status kepegawaian akan dihapus permanen. Lanjutkan?')" />
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
                Menampilkan {{ $statusKepegawaians->firstItem() ?? 0 }}–{{ $statusKepegawaians->lastItem() ?? 0 }}
                dari {{ $statusKepegawaians->total() }} entri
            </span>

            <x-pagination :paginator="$statusKepegawaians" />
        </div>
    </x-panel>
@endsection
