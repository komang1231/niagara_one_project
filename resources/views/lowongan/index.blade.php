@extends('layouts.app')
@if (session('error'))
    <script>
        alert(@json(session('error')));
    </script>
@endif
@section('content')
    @php
        // Baris kosong dibuang supaya baris pertama di cell-stack selalu berisi data.
        $stack = fn(...$lines) => array_values(array_filter($lines, 'filled'));
    @endphp

    <x-page-header eyebrow="Rekrutmen" title="Lowongan" description="Kelola data lowongan pekerjaan dan status pembukaannya."
        icon="bi-briefcase-fill">
        <x-slot:badges>
            <x-badge>{{ $lowongan->total() }} lowongan</x-badge>
        </x-slot:badges>

        <x-slot:actions>
            <x-button variant="outline" icon="bi-trash" href="{{ route('lowongan.trash') }}">
                Trash
            </x-button>

            <x-button variant="primary" icon="bi-plus-lg" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-lowongan">
                Tambah Lowongan
            </x-button>
        </x-slot:actions>
    </x-page-header>

    <x-panel>
        <div>
            <x-filter.bar :clearable="['search', 'status']" ajax-target="#lowongan-table">
                <x-filter.search placeholder="Cari judul atau kode lowongan" />

                <x-filter.multiselect name="status" label="Status" :options="$statusOptions" />
            </x-filter.bar>
        </div>

        <hr class="app-panel__divider">

        <div id="lowongan-table">
            <x-table>
                <thead>
                    <tr>
                        <th class="app-table__col-no">NO</th>
                        <th>Kode</th>
                        <th>Lowongan</th>
                        <th>Penempatan</th>
                        <th>Gaji &amp; Kuota</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th class="app-table__col-actions">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($lowongan as $i => $row)
                        @php
                            // Jabatan: job position · job level (dilewati kalau kosong)
                            $jabatan = collect([optional($row->jobPosition)->nama, optional($row->jobLevel)->nama])
                                ->filter()
                                ->implode(' · ');

                            // Penempatan: departemen › divisi › section
                            $penempatan = collect([
                                optional($row->departemen_id)->nama,
                                optional($row->divisi)->nama,
                                optional($row->section)->nama,
                            ])
                                ->filter()
                                ->implode(' › ');

                            $gajiRange =
                                'Rp ' .
                                number_format((float) $row->min_gaji, 0, ',', '.') .
                                ' - Rp ' .
                                number_format((float) $row->max_gaji, 0, ',', '.');

                            $periodeBuka = \Carbon\Carbon::parse($row->tanggal_buka)->translatedFormat('d M Y');
                            $periodeTutup = \Carbon\Carbon::parse($row->tanggal_tutup)->translatedFormat('d M Y');
                        @endphp

                        <tr>
                            <td class="app-table__col-no">
                                {{ $lowongan->firstItem() + $i }}
                            </td>

                            <td class="fw-semibold">
                                {{ $row->kode }}
                            </td>

                            {{-- Judul + jabatan --}}
                            <td>
                                <x-table.cell-stack :lines="$stack($row->judul, $jabatan)" />
                            </td>

                            {{-- Cabang kantor + departemen/divisi/section --}}
                            <td>
                                <x-table.cell-stack :lines="$stack(optional($row->cabangKantor)->nama, $penempatan)" />
                            </td>

                            {{-- Gaji + kuota --}}
                            <td>
                                <x-table.cell-stack :lines="$stack($gajiRange, 'Kuota: ' . $row->kuota . ' orang')" />
                            </td>

                            {{-- Periode buka - tutup --}}
                            <td>
                                <x-table.cell-stack :lines="$stack($periodeBuka . ' – ' . $periodeTutup)" />
                            </td>

                            <td>
                                <x-badge :variant="$row->status === 'aktif' ? 'success' : 'neutral'">
                                    {{ ucfirst($row->status) }}
                                </x-badge>
                            </td>

                            <td class="app-table__col-actions">
                                <div class="app-table__actions">
                                    <x-button variant="icon-view" icon="bi-eye" title="Lihat detail"
                                        data-bs-toggle="offcanvas" data-bs-target="#offcanvas-lowongan-detail"
                                        data-detail-url="{{ route('lowongan.show', $row->id) }}" />

                                    {{-- Edit --}}
                                    <x-button variant="icon-edit" icon="bi-pencil" title="Edit" data-bs-toggle="offcanvas"
                                        data-bs-target="#offcanvas-lowongan-edit"
                                        data-edit-url="{{ url('lowongan/' . $row->id . '/edit-data') }}"
                                        data-update-url="{{ route('lowongan.update', $row->id) }}" />

                                    {{-- Hapus (soft delete) --}}
                                    <form action="{{ route('lowongan.destroy', $row->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <x-button type="submit" variant="icon-danger" icon="bi-trash" title="Hapus"
                                            />
                                    </form>

                                    {{-- Status --}}
                                    <x-table.status-toggle :checked="$row->status === 'aktif'" id="status-toggle-{{ $row->id }}"
                                        data-id="{{ $row->id }}" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <x-table.empty-row colspan="8" />
                    @endforelse
                </tbody>
            </x-table>

            <div class="app-table-footer">
                <span>
                    Menampilkan
                    {{ $lowongan->firstItem() ?? 0 }}–{{ $lowongan->lastItem() ?? 0 }}
                    dari
                    {{ $lowongan->total() }}
                    entri
                </span>

                <x-pagination :paginator="$lowongan" />
            </div>
        </div>
    </x-panel>

    @includeIf('lowongan.form-create')
    @includeIf('lowongan.form-edit')
    @includeIf('lowongan.detail')
    <script>
        document.addEventListener('change', function(e) {
            const toggle = e.target.closest('.app-table-toggle input[type="checkbox"]');

            if (!toggle) return;

            const id = toggle.dataset.id;

            fetch(`/lowongan/${id}/toggle-status`, {
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
