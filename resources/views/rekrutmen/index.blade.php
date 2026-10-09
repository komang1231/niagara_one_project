@extends('layouts.app')

@section('content')
    @php
        $stack = fn(...$lines) => array_values(array_filter($lines, 'filled'));
    @endphp

    <x-page-header eyebrow="Rekrutmen" title="Data Rekrutmen" description="Kelola kandidat dan proses rekrutmen."
        icon="bi-person-badge-fill">
        <x-slot:badges>
            <x-badge>
                {{ $rekrutmen->total() }} kandidat
            </x-badge>
        </x-slot:badges>

        <x-slot:actions>

            <x-button variant="outline" icon="bi-trash" href="{{ route('rekrutmen.trash') }}">
                Trash
            </x-button>

            <x-button variant="primary" icon="bi-plus-lg" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvas-tambah-rekrutmen">
                Tambah Rekrutmen
            </x-button>

        </x-slot:actions>
    </x-page-header>


    <x-panel>

        <div>
            <x-filter.bar :clearable="['search', 'status']" ajax-target="#rekrutmen-table">

                <x-filter.search placeholder="Cari nama atau kode rekrutmen" />

                <x-filter.multiselect name="status" label="Status" :options="$statusOptions" />

            </x-filter.bar>
        </div>

        <hr class="app-panel__divider">


        <div id="rekrutmen-table">

            <x-table>

                <thead>
                    <tr>
                        <th class="app-table__col-no">NO</th>
                        <th>Kandidat</th>
                        <th>Posisi</th>
                        <th>Tahap Rekrutmen</th>
                        <th>Kontak</th>
                        <th>Status</th>
                        <th class="app-table__col-actions">Aksi</th>
                    </tr>
                </thead>


                <tbody>

                    @forelse ($rekrutmen as $i => $row)
                        <tr>

                            <td class="app-table__col-no">
                                {{ $rekrutmen->firstItem() + $i }}
                            </td>


                            {{-- Kandidat --}}
                            <td>
                                <x-table.cell-stack :lines="$stack($row->nama, $row->kode)" />
                            </td>


                            {{-- Posisi --}}
                            <td>
                                <x-table.cell-stack :lines="$stack($row->jobPosition?->nama ?? '-', $row->departemen?->nama)" />
                            </td>


                            {{-- Tahap --}}
                            <td>
                                @php
                                    $statusVariant = match ($row->status_rekrutmen) {
                                        'pelamar' => 'neutral',
                                        'screening' => 'warning',
                                        'interview' => 'info',
                                        'offering' => 'primary',
                                        'diterima' => 'success',
                                        'ditolak' => 'danger',
                                        default => 'neutral',
                                    };
                                @endphp

                                <x-badge :variant="$statusVariant">
                                    {{ ucfirst($row->status_rekrutmen) }}
                                </x-badge>
                            </td>


                            {{-- Kontak --}}
                            <td>
                                <x-table.cell-stack :lines="$stack($row->email, $row->no_tlp)" />
                            </td>


                            {{-- Status --}}
                            <td>
                                <x-badge :variant="$row->status === 'aktif' ? 'success' : 'neutral'">
                                    {{ ucfirst($row->status) }}
                                </x-badge>
                            </td>


                            {{-- Aksi --}}
                            <td class="app-table__col-actions">

                                <div class="app-table__actions">

                                    {{-- EDIT: offcanvas terbuka mode lihat (field terkunci), tombol Edit ada di footer --}}
                                    <x-button variant="icon-edit" icon="bi-pencil" title="Lihat / edit"
                                        data-bs-toggle="offcanvas" data-bs-target="#offcanvas-rekrutmen-edit" data-rk-edit
                                        data-edit-url="{{ route('rekrutmen.edit-data', $row->id) }}"
                                        data-update-url="{{ route('rekrutmen.update', $row->id) }}" />


                                    {{-- DELETE --}}
                                    <form action="{{ route('rekrutmen.destroy', $row->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <x-button type="submit" variant="icon-danger" icon="bi-trash" title="Hapus" />
                                    </form>


                                    {{-- STATUS --}}
                                    <x-table.status-toggle :checked="$row->status === 'aktif'" id="status-toggle-{{ $row->id }}"
                                        data-id="{{ $row->id }}" />

                                </div>

                            </td>

                        </tr>

                    @empty

                        <x-table.empty-row colspan="7" />
                    @endforelse

                </tbody>

            </x-table>


            <div class="app-table-footer">

                <span>
                    Menampilkan
                    {{ $rekrutmen->firstItem() ?? 0 }}–{{ $rekrutmen->lastItem() ?? 0 }}
                    dari
                    {{ $rekrutmen->total() }}
                    entri
                </span>

                <x-pagination :paginator="$rekrutmen" />

            </div>

        </div>

    </x-panel>


    @include('rekrutmen.form-create')
    @include('rekrutmen.form-edit')
    @include('rekrutmen.form-rekrutmen-diterima')


    {{-- STATUS TOGGLE --}}
    <script>
        document.addEventListener('change', function(e) {

            const toggle = e.target.closest(
                '.app-table-toggle input[type="checkbox"]'
            );

            if (!toggle) return;

            const id = toggle.dataset.id;

            fetch(`/rekrutmen/${id}/toggle-status`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
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
                    toggle.checked = !toggle.checked;
                });

        });
    </script>
@endsection
