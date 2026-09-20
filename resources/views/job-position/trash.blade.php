@extends('layouts.app')

@section('content')
    <x-page-header eyebrow="Struktur Karyawan" title="Trash Job Position"
        description="Job position yang telah dihapus. Pulihkan atau hapus permanen di sini." icon="bi-trash-fill">

        <x-slot:badges>
            <x-badge>{{ $jobPositions->total() }} job position</x-badge>
        </x-slot:badges>

        <x-slot:actions>
            <x-button variant="outline" icon="bi-arrow-left" href="{{ route('job-position.index') }}">
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
                    <th>Nama Job Position</th>
                    <th>Section</th>
                    <th>Status</th>
                    <th class="app-table__col-actions">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($jobPositions as $i => $row)
                    <tr>
                        <td class="app-table__col-no">
                            {{ $jobPositions->firstItem() + $i }}
                        </td>

                        <td class="fw-semibold">
                            {{ $row->kode }}
                        </td>

                        <td>
                            {{ $row->nama }}
                        </td>

                        <td>
                            {{ $row->section?->nama ?? '-' }}
                        </td>

                        <td>
                            <x-badge :variant="$row->status === 'aktif' ? 'success' : 'neutral'">
                                {{ ucfirst($row->status) }}
                            </x-badge>
                        </td>

                        <td class="app-table__col-actions">
                            <div class="app-table__actions">
                                {{-- Restore --}}
                                <form action="{{ route('job-position.restore', $row->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <x-button type="submit" variant="icon-success" icon="bi-arrow-counterclockwise"
                                        onclick="return confirm('Pulihkan job position ini?')" />
                                </form>

                                {{-- Hapus permanen --}}
                                <form action="{{ route('job-position.force-delete', $row->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <x-button type="submit" variant="icon-danger" icon="bi-trash3-fill"
                                        onclick="return confirm('Job position akan dihapus permanen dan tidak bisa dikembalikan lagi. Lanjutkan?')" />
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
                {{ $jobPositions->firstItem() ?? 0 }}–{{ $jobPositions->lastItem() ?? 0 }}
                dari
                {{ $jobPositions->total() }} entri
            </span>

            <x-pagination :paginator="$jobPositions" />
        </div>
    </x-panel>
@endsection
