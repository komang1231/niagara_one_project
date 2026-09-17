@extends('layouts.app')

@section('content')
    <x-page-header
        eyebrow="Struktur Karyawan"
        title="Departemen"
        description="Kelola data departemen dan status departemen perusahaan."
        icon="bi-diagram-3-fill"
    >
        <x-slot:badges>
            <x-badge>{{ $departemen->count() }} departemen</x-badge>
        </x-slot:badges>

        <x-slot:actions>
            <x-button
                variant="outline"
                icon="bi-trash"
                href="{{ route('departemen.trash') }}"
            >
                Trash
            </x-button>

             <x-button
                variant="primary"
                icon="bi-plus-lg"
                data-bs-toggle="offcanvas"
                data-bs-target="#offcanvas-departemen"
            >
                Tambah Departemen
            </x-button>
        </x-slot:actions>
    </x-page-header>

    <x-panel>
        <div>
            <x-filter.bar
                :clearable="['search', 'status']"
                ajax-target="#departemen-table"
            >
                <x-filter.search />

                <x-filter.multiselect
                    name="status"
                    label="Status"
                    :options="$statusOptions"
                />
            </x-filter.bar>
        </div>

        <hr class="app-panel__divider">

        <div id="departemen-table">
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
                    @forelse ($departemen as $i => $row)
                        <tr>
                            <td class="app-table__col-no">
                                {{ $i + 1 }}
                            </td>
{{-- 
                            <td>
    <div class="app-table__cell-stack">
        <span class="app-table__cell-primary">{{ $row['nama'] }}</span>
        <span class="app-table__cell-secondary">{{ $row['kode'] }}</span>
    </div>
</td> --}}

                            <td>
                                {{ $row['kode'] }}
                            </td>

                            <td>
                                {{ $row['nama'] }}
                            </td>

                            <td>
                                <x-badge
                                    :variant="$row['status'] === 'aktif'
                                        ? 'success'
                                        : 'neutral'"
                                >
                                    {{ ucfirst($row['status']) }}
                                </x-badge>
                            </td>

                            <td class="app-table__col-actions">
                                <div class="app-table__actions">
                                  <x-button
                                    variant="icon-edit"
                                    icon="bi-pencil"
                                    data-bs-toggle="offcanvas"
                                    data-bs-target="#offcanvas-departemen-edit"
                                    data-id="{{ $row['id'] }}"
                                   />

                                    <x-button
                                        variant="icon-danger"
                                        icon="bi-trash"
                                        href="#"
                                    />
                                    <x-table.status-toggle
                                        :checked="$row['status'] === 'aktif'"
                                        id="status-toggle-{{ $row['id'] }}"
                                        data-id="{{ $row['id'] }}"
                                    />
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
                    {{ $departemen->count() }}
                    dari
                    {{ $departemen->count() }}
                    entri
                </span>
            </div>
        </div>
    </x-panel>

       @include('departemen.form-create')
       @include('departemen.form-edit')
       <script>
    document.addEventListener('change', function (e) {
        const toggle = e.target.closest('.app-table-toggle input[type="checkbox"]');

        if (!toggle) return;

        const id = toggle.dataset.id;

        fetch(`/departemen/${id}/toggle-status`, {
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
        .then(data => {
            console.log('Status berhasil diubah:', data.status);
        })
        .catch(error => {
            console.error(error);

            // Kembalikan switch jika request gagal
            toggle.checked = !toggle.checked;
        });
    });
</script>
@endsection