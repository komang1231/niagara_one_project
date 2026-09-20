@extends('layouts.app')
@if (session('error'))
    <script>
        alert(@json(session('error')));
    </script>
@endif
@section('content')
    <x-page-header eyebrow="Struktur Karyawan" title="Job Level" description="Kelola data job level dan status job level perusahaan."
        icon="bi-diagram-3-fill">
        <x-slot:badges>
            <x-badge>{{ $jobLevel->total() }} job level</x-badge>
        </x-slot:badges>

        <x-slot:actions>
            <x-button variant="outline" icon="bi-trash" href="{{ route('job-level.trash') }}">
                Trash
            </x-button>

            <x-button variant="primary" icon="bi-plus-lg" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-job-level">
                Tambah Job Level
            </x-button>
        </x-slot:actions>
    </x-page-header>

    <x-panel>
        <div>
            <x-filter.bar :clearable="['search', 'status']" ajax-target="#job-level-table">
                <x-filter.search />

                <x-filter.multiselect name="status" label="Status" :options="$statusOptions" />
            </x-filter.bar>
        </div>

        <hr class="app-panel__divider">

        <div id="job-level-table">
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
                    @forelse ($jobLevel as $i => $row)
                        <tr>
                            <td class="app-table__col-no">
                                {{ $jobLevel->firstItem() + $i }}
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
                                        data-bs-target="#offcanvas-job-level-edit"
                                        data-edit-url="{{ url('job-level/' . $row['id'] . '/edit-data') }}"
                                        data-update-url="{{ route('job-level.update', $row['id']) }}" />

                                    {{-- Hapus (soft delete) --}}
                                    <form action="{{ route('job-level.destroy', $row->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <x-button type="submit" variant="icon-danger" icon="bi-trash"
                                            onclick="return confirm('Hapus job level ini?')" />
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
                    {{ $jobLevel->firstItem() ?? 0 }}–{{ $jobLevel->lastItem() ?? 0 }}
                    dari
                    {{ $jobLevel->total() }}
                    entri
                </span>

                <x-pagination :paginator="$jobLevel" />
            </div>
        </div>
    </x-panel>

    @include('job-level.form-create')
    @include('job-level.form-edit')
    <script>
        document.addEventListener('change', function(e) {
            const toggle = e.target.closest('.app-table-toggle input[type="checkbox"]');

            if (!toggle) return;

            const id = toggle.dataset.id;

            fetch(`/job-level/${id}/toggle-status`, {
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
