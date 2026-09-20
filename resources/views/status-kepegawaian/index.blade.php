@extends('layouts.app')
@if (session('error'))
    <script>
        alert(@json(session('error')));
    </script>
@endif
@section('content')
    <x-page-header eyebrow="Akses & Pengguna" title="Status Kepegawaian"
        description="Kelola data status kepegawaian." icon="bi-diagram-3-fill">
        <x-slot:badges>
            <x-badge>{{ $statusKepegawaian->total() }} status</x-badge>
        </x-slot:badges>

        <x-slot:actions>
            <x-button variant="outline" icon="bi-trash" href="{{ route('status-kepegawaian.trash') }}">
                Trash
            </x-button>

            <x-button variant="primary" icon="bi-plus-lg" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-status-kepegawaian">
                Tambah Status Kepegawaian
            </x-button>
        </x-slot:actions>
    </x-page-header>

    <x-panel>
        <div>
            <x-filter.bar :clearable="['search', 'status']" ajax-target="#status-kepegawaian-table">
                <x-filter.search />

                <x-filter.multiselect name="status" label="Status" :options="$statusOptions" />
            </x-filter.bar>
        </div>

        <hr class="app-panel__divider">

        <div id="status-kepegawaian-table">
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
                    @forelse ($statusKepegawaian as $i => $row)
                        <tr>
                            <td class="app-table__col-no">
                                {{ $statusKepegawaian->firstItem() + $i }}
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
                                        data-bs-target="#offcanvas-status-kepegawaian-edit"
                                        data-edit-url="{{ url('status-kepegawaian/' . $row['id'] . '/edit-data') }}"
                                        data-update-url="{{ route('status-kepegawaian.update', $row['id']) }}" />

                                    {{-- Hapus (soft delete) --}}
                                    <form action="{{ route('status-kepegawaian.destroy', $row->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <x-button type="submit" variant="icon-danger" icon="bi-trash"
                                            onclick="return confirm('Hapus status kepegawaian ini?')" />
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
                    {{ $statusKepegawaian->firstItem() ?? 0 }}–{{ $statusKepegawaian->lastItem() ?? 0 }}
                    dari
                    {{ $statusKepegawaian->total() }}
                    entri
                </span>

                <x-pagination :paginator="$statusKepegawaian" />
            </div>
        </div>
    </x-panel>

    @include('status-kepegawaian.form-create')
    @include('status-kepegawaian.form-edit')
    <script>
        document.addEventListener('change', function(e) {
            const toggle = e.target.closest('.app-table-toggle input[type="checkbox"]');

            if (!toggle) return;

            const id = toggle.dataset.id;

            fetch(`/status-kepegawaian/${id}/toggle-status`, {
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
