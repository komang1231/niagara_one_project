@extends('layouts.app')

@section('content')
    @php
        // Penyusun baris untuk <x-table.cell-stack>: baris kosong dibuang supaya
        // baris pertama (yang otomatis jadi judul tebal) selalu berisi data.
        $stack = fn(...$lines) => array_values(array_filter($lines, 'filled'));
    @endphp

    <x-page-header eyebrow="Karyawan" title="Data Karyawan"
        description="Kelola data karyawan, penempatan, dan status kepegawaian." icon="bi-people-fill">
        <x-slot:badges>
            <x-badge>{{ $karyawan->total() }} karyawan</x-badge>
        </x-slot:badges>

        <x-slot:actions>
            <x-button variant="outline" icon="bi-trash" href="{{ route('karyawan.trash') }}">
                Trash
            </x-button>

            <x-button variant="primary" icon="bi-plus-lg" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-karyawan">
                Tambah Karyawan
            </x-button>
        </x-slot:actions>
    </x-page-header>

    <x-panel>
        <div>
            <x-filter.bar :clearable="['search', 'status']" ajax-target="#karyawan-table">
                <x-filter.search placeholder="Cari nama, NIP, email, atau NIK" />

                <x-filter.multiselect name="status" label="Status" :options="$statusOptions" />
            </x-filter.bar>
        </div>

        <hr class="app-panel__divider">

        <div id="karyawan-table">
            <x-table>
                <thead>
                    <tr>
                        <th class="app-table__col-no">NO</th>
                        <th>Karyawan</th>
                        <th>Penempatan</th>
                        <th>Jabatan</th>
                        <th>Status</th>
                        <th class="app-table__col-actions">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($karyawan as $i => $row)
                        @php
                            // "Divisi › Section" digabung 1 baris; bagian yang kosong dilewati.
                            $divisiSection = collect([$row->divisi?->nama, $row->section?->nama])
                                ->filter()
                                ->implode(' › ');
                        @endphp

                        <tr>
                            <td class="app-table__col-no">
                                {{ $karyawan->firstItem() + $i }}
                            </td>

                            {{-- Karyawan: avatar + nama, NIP, email --}}
                            <td>
                                <x-table.cell-stack :avatar="$row->nama" :lines="$stack($row->nama, $row->nip, $row->email)" />
                            </td>

                            {{-- Penempatan: departemen, divisi › section --}}
                            <td>
                                <x-table.cell-stack :lines="$stack($row->departemen?->nama ?? '-', $divisiSection)" />
                            </td>

                            {{-- Jabatan: job position, level, status kepegawaian --}}
                            <td>
                                <x-table.cell-stack :lines="$stack(
                                    $row->jobPosition?->nama ?? '-',
                                    $row->jobLevel?->nama,
                                    $row->statusKepegawaian?->nama,
                                )" />
                            </td>

                            <td>
                                <x-badge :variant="$row->status === 'aktif' ? 'success' : 'neutral'">
                                    {{ ucfirst($row->status) }}
                                </x-badge>
                            </td>

                            <td class="app-table__col-actions">
                                <div class="app-table__actions">
                                    {{-- Detail (offcanvas detail dibuat di langkah berikutnya) --}}
                                    <x-button variant="icon-view" icon="bi-eye" title="Lihat detail"
                                        data-bs-toggle="offcanvas" data-bs-target="#offcanvas-karyawan-detail"
                                        data-detail-url="{{ route('karyawan.show', $row->id) }}" />

                                    {{-- Edit --}}
                                    <x-button variant="icon-edit" icon="bi-pencil" title="Edit" data-bs-toggle="offcanvas"
                                        data-bs-target="#offcanvas-karyawan-edit"
                                        data-edit-url="{{ route('karyawan.edit-data', $row->id) }}"
                                        data-update-url="{{ route('karyawan.update', $row->id) }}" />

                                    {{-- Hapus (soft delete) --}}
                                    <form action="{{ route('karyawan.destroy', $row->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <x-button type="submit" variant="icon-danger" icon="bi-trash" title="Hapus"
                                            onclick="return confirm('Hapus karyawan ini?')" />
                                    </form>

                                        <x-table.status-toggle :
                                            :checked="$row->status === 'aktif'" 
                                            id="status-toggle-{{ $row->id }}"
                                            {{-- data-toggle-url="{{ route('karyawan.toggle-status', $row->id) }}"  --}}
                                            data-id="{{ $row->id }}"
                                        />
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
                    {{ $karyawan->firstItem() ?? 0 }}–{{ $karyawan->lastItem() ?? 0 }}
                    dari
                    {{ $karyawan->total() }}
                    entri
                </span>

                <x-pagination :paginator="$karyawan" />
            </div>
        </div>
    </x-panel>

    {{--
        Offcanvas dipasang otomatis begitu file-nya dibuat (@includeIf tidak error kalau file belum ada).
        Setelah semua form selesai, boleh diganti jadi @include biasa.
    --}}
    @includeIf('karyawan.form-create')
    @includeIf('karyawan.form-edit')
    <script>
        document.addEventListener('change', function(e) {
            const toggle = e.target.closest('.app-table-toggle input[type="checkbox"]');

            if (!toggle) return;

            const id = toggle.dataset.id;

            fetch(`/karyawan/${id}/toggle-status`, {
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
    @includeIf('karyawan.detail')
@endsection
