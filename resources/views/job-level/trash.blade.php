@extends('layouts.app')

@section('content')
    <x-page-header eyebrow="Struktur Karyawan" title="Trash Job Level"
        description="Job level yang telah dihapus. Pulihkan atau hapus permanen di sini." icon="bi-trash-fill">
        <x-slot:badges>
            <x-badge>{{ $jobLevels->total() }} job level</x-badge>
        </x-slot:badges>

        <x-slot:actions>
            <x-button variant="outline" icon="bi-arrow-left" href="{{ route('job-level.index') }}">
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
                    <th>Nama Job Level</th>
                    <th>Status</th>
                    <th class="app-table__col-actions">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($jobLevels as $i => $row)
                    <tr>
                        <td class="app-table__col-no">
                            {{ $jobLevels->firstItem() + $i }}
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
                                <form action="{{ route('job-level.restore', $row->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <x-button type="submit" variant="icon-success" icon="bi-arrow-counterclockwise"
                                        onclick="return confirm('Pulihkan job level ini?')" />
                                </form>

                                {{-- Hapus permanen --}}
                                    <form action="{{ route('job-level.force-delete', $row->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <x-button type="submit" variant="icon-danger" icon="bi-trash"
                                            onclick="return confirm('Job level akan dihapus permanen. Lanjutkan?')" />
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
                Menampilkan
                 {{ $jobLevels->firstItem() ?? 0 }}–{{ $jobLevels->lastItem() ?? 0 }}
                dari {{ $jobLevels->total() }} entri
            </span>
            <x-pagination :paginator="$jobLevels" />

        </div>
    </x-panel>
@endsection
