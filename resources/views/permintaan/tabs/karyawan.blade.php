<x-page-header eyebrow="Permintaan" title="Karyawan"
    description="Kelola pengajuan kebutuhan karyawan beserta status persetujuannya."
    icon="bi-people-fill">

    <x-slot:badges>
        <x-badge>{{ $permintaanKaryawan->total() }} permintaan</x-badge>
    </x-slot:badges>

    <x-slot:actions>
        <x-button variant="outline" icon="bi-trash"
            href="{{ route('permintaan-karyawan.trash') }}">
            Trash
        </x-button>

        <x-button variant="primary" icon="bi-plus-lg"
            data-bs-toggle="offcanvas"
            data-bs-target="#offcanvas-permintaan-karyawan">
            Tambah Permintaan Karyawan
        </x-button>
    </x-slot:actions>
</x-page-header>

<x-panel>

    <div>
        <x-filter.bar :clearable="['search', 'status']" ajax-target="#karyawan-table">

            <x-filter.search placeholder="Cari judul, kode, atau pemohon..." />

            {{-- Jika filter status sudah siap, bisa diaktifkan --}}
            {{-- <x-filter.multiselect name="status" label="Status" :options="$statusOptions" /> --}}

        </x-filter.bar>
    </div>

    <hr class="app-panel__divider">

    <div id="karyawan-table"></div>

    <x-table>
        <thead>
            <tr>
                <th class="app-table__col-no">NO</th>
                <th>Permintaan</th>
                <th>Penempatan</th>
                <th>Kebutuhan</th>
                <th>Pemohon</th>
                <th>Status</th>
                <th class="text-end">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($permintaanKaryawan as $i => $row)

                @php
                    $penempatan = collect([
                        $row->cabangKantor?->nama
                            ? 'Cabang: ' . $row->cabangKantor->nama
                            : null,

                        $row->departemen?->nama
                            ? 'Departemen: ' . $row->departemen->nama
                            : null,

                        $row->divisi?->nama
                            ? 'Divisi: ' . $row->divisi->nama
                            : null,

                        $row->section?->nama
                            ? 'Section: ' . $row->section->nama
                            : null,
                    ])->filter()->values()->all();

                    $kebutuhan = collect([
                        'Posisi: ' . ($row->jobPosition?->nama ?? 'Tidak ditentukan'),
                        'Level: ' . ($row->jobLevel?->nama ?? 'Tidak ditentukan'),
                    ])->all();
                @endphp

                <tr>

                    {{-- NO --}}
                    <td class="app-table__col-no">
                        {{ $permintaanKaryawan->firstItem() + $i }}
                    </td>

                    {{-- PERMINTAAN --}}
                    <td>
                        <x-table.cell-stack
                            :lines="[
                                $row->nama ?: 'Permintaan Karyawan',
                                $row->kode,
                            ]"
                        />
                    </td>

                    {{-- PENEMPATAN --}}
                    <td>
                        @if (count($penempatan))
                            <x-table.cell-stack :lines="$penempatan" />
                        @else
                            <span class="text-muted">Penempatan belum ditentukan</span>
                        @endif
                    </td>

                    {{-- KEBUTUHAN --}}
                    <td>
                        <x-table.cell-stack :lines="$kebutuhan" />

                        <div class="mt-1">
                            <span class="badge bg-light text-dark border">
                                {{ $row->jumlah }} orang
                            </span>
                        </div>
                    </td>

                    {{-- PEMOHON --}}
                    <td>
                        @if ($row->karyawan)
                            <x-table.cell-stack
                                :avatar="$row->karyawan->avatar"
                                :lines="[
                                    $row->karyawan->nama,
                                ]"
                            />
                        @else
                            <span class="text-muted">
                                Pemohon tidak ditemukan
                            </span>
                        @endif
                    </td>

                    {{-- STATUS --}}
                    <td>
                        @include('permintaan.partials.status', ['row' => $row])
                    </td>

                    {{-- AKSI --}}
                    <td>
                        @include('permintaan.partials.aksi', [
                            'row' => $row,
                            'slug' => 'permintaan-karyawan',
                            'label' => 'Permintaan Karyawan',
                        ])
                    </td>

                </tr>

            @empty

                <x-table.empty-row
                    :colspan="7"
                    text="Belum ada permintaan karyawan."
                />

            @endforelse
        </tbody>
    </x-table>

    <div class="mt-3">
        {{ $permintaanKaryawan->links() }}
    </div>

</x-panel>

