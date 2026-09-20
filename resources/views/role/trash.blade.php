@extends('layouts.app')

@section('content')
    <x-page-header eyebrow="Akses & Pengguna" title="Trash Role"
        description="Role yang telah dihapus. Pulihkan atau hapus permanen di sini." icon="bi-trash-fill">
        <x-slot:badges>
            <x-badge>{{ $roles->total() }} role</x-badge>
        </x-slot:badges>

        <x-slot:actions>
            <x-button variant="outline" icon="bi-arrow-left" href="{{ route('role.index') }}">
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
                    <th>Nama Role</th>
                    <th>Status</th>
                    <th class="app-table__col-actions">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($roles as $i => $row)
                    <tr>
                        <td class="app-table__col-no">
                            {{ $roles->firstItem() + $i }}
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
                                <form action="{{ route('role.restore', $row->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <x-button type="submit" variant="icon-success" icon="bi-arrow-counterclockwise"
                                        onclick="return confirm('Pulihkan role ini?')" />
                                </form>

                                {{-- Hapus permanen --}}
                                    <form action="{{ route('role.force-delete', $row->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <x-button type="submit" variant="icon-danger" icon="bi-trash"
                                            onclick="return confirm('Role akan dihapus permanen. Lanjutkan?')" />
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
                Menampilkan {{ $roles->firstItem() ?? 0 }}–{{ $roles->lastItem() ?? 0 }}
                dari {{ $roles->total() }} entri
            </span>
            <x-pagination :paginator="$roles" />

        </div>
    </x-panel>
@endsection
