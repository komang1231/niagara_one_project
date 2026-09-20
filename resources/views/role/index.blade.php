@extends('layouts.app')

@section('content')
    <x-page-header eyebrow="Akses & Pengguna" title="Role"
        description="Kelola data role" icon="bi-diagram-3-fill">
        <x-slot:badges>
            <x-badge>{{ $role->total() }} role</x-badge>
        </x-slot:badges>

        <x-slot:actions>
            <x-button variant="outline" icon="bi-trash" href="{{ route('role.trash') }}">
                Trash
            </x-button>

            <x-button variant="primary" icon="bi-plus-lg" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-role">
                Tambah Role
            </x-button>
        </x-slot:actions>
    </x-page-header>

    <x-panel>
        <div>
            <x-filter.bar :clearable="['search', 'status']" ajax-target="#role-table">
                <x-filter.search />

                <x-filter.multiselect name="status" label="Status" :options="$statusOptions" />
            </x-filter.bar>
        </div>

        <hr class="app-panel__divider">

        <div id="role-table">
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
                    @forelse ($role as $i => $row)
                        <tr>
                            <td class="app-table__col-no">
                                {{ $role->firstItem() + $i }}
                            </td>

                            <td class="fw-semibold">
                                {{ $row->kode }}
                            </td>

                            <td>
                                {{ $row->nama }}
                            </td>

                            <td>
                                <x-badge :variant="$row->status === 'aktif' ? 'success' : 'neutral'">
                                    {{ ucfirst($row->status) }}
                                </x-badge>
                            </td>

                            <td class="app-table__col-actions">
                                <div class="app-table__actions">
                                    {{-- Edit --}}
                                    <x-button variant="icon-edit" icon="bi-pencil" data-bs-toggle="offcanvas"
                                        data-bs-target="#offcanvas-role-edit"
                                        data-edit-url="{{ url('role/' . $row['id'] . '/edit-data') }}"
                                        data-update-url="{{ route('role.update', $row['id']) }}" />

                                    {{-- Hapus (soft delete) --}}
                                    <form action="{{ route('role.destroy', $row->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <x-button type="submit" variant="icon-danger" icon="bi-trash"
                                            onclick="return confirm('Hapus role ini?')" />
                                    </form>

                                    {{-- Status --}}
                                    <x-table.status-toggle :checked="$row->status === 'aktif'" id="status-toggle-{{ $row->id }}"
                                        data-id="{{ $row->id }}" />
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
                    {{ $role->firstItem() ?? 0 }}–{{ $role->lastItem() ?? 0 }}
                    dari
                    {{ $role->total() }}
                    entri
                </span>

                <x-pagination :paginator="$role" />
            </div>
        </div>
    </x-panel>

    @include('role.form-create')
    @include('role.form-edit')
    <script>
        document.addEventListener('change', function(e) {
            const toggle = e.target.closest('.app-table-toggle input[type="checkbox"]');

            if (!toggle) return;

            const id = toggle.dataset.id;

            fetch(`/role/${id}/toggle-status`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Gagal mengubah status.');
                    }

                    return response.json();
                })
                .then(() => window.location.reload())
                .catch(error => {
                    console.error(error);

                    // Kembalikan switch jika request gagal
                    toggle.checked = !toggle.checked;
                });
        });
    </script>
@endsection
