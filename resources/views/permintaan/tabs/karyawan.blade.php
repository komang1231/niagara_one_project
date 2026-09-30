<x-page-header eyebrow="Permintaan" title="Karyawan" description="Kelola pengajuan karyawan beserta status persetujuannya."
    icon="bi-people-fill">
    <x-slot:badges>
        <x-badge>{{ $permintaanKaryawan->total() }} permintaan</x-badge>
    </x-slot:badges>

    <x-slot:actions>
        <x-button variant="outline" icon="bi-trash" href="{{ route('permintaan-karyawan.trash') }}">
            Trash
        </x-button>

        <x-button variant="primary" icon="bi-plus-lg" data-bs-toggle="offcanvas"
            data-bs-target="#offcanvas-permintaan-karyawan">
            Tambah Permintaan Karyawan
        </x-button>
    </x-slot:actions>
</x-page-header>

<x-panel>
    <div>
        <x-filter.bar :clearable="['search', 'status']" ajax-target="#karyawan-table">

            <x-filter.search placeholder="Cari nama atau kode Karyawan" />

            {{-- <x-filter.multiselect name="status" label="Status" :options="$statusOptions" /> --}}

        </x-filter.bar>
    </div>

    <hr class="app-panel__divider">
    <div id="karyawan-table"></div>
    <x-table>
        <thead>
            <tr>
                <th class="app-table__col-no">NO</th>
                <th>Kode</th>
                <th>Pemohon</th>
                <th>Info Utama</th>
                <th>Status</th>
                <th class="text-end">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($permintaanKaryawan as $i => $row)
                @php
                    $pemohon = $row->karyawan->nama ?? '-';
                    $posisi = $row->jobPosition->nama ?? ($row->departemen->nama ?? '-');
                    $level = $row->jobLevel->nama ?? '-';
                @endphp

                <tr>
                    <td class="app-table__col-no">
                        {{ $permintaanKaryawan->firstItem() + $i }}
                    </td>

                    <td class="fw-semibold">{{ $row->kode }}</td>

                    <td><x-table.cell-stack :avatar="$pemohon" :lines="[$pemohon]" /></td>

                    <td><x-table.cell-stack :lines="[$posisi, $level . ' • ' . $row->jumlah . ' orang']" /></td>

                    <td>@include('permintaan.partials.status', ['row' => $row])</td>

                    <td>@include('permintaan.partials.aksi', [
                        'row' => $row,
                        'slug' => 'permintaan-karyawan',
                        'label' => 'Permintaan Karyawan',
                    ])</td>
                </tr>
            @empty
                <x-table.empty-row :colspan="5" text="Belum ada permintaan karyawan." />
            @endforelse
        </tbody>
    </x-table>

    <div class="mt-3">{{ $permintaanKaryawan->links() }}</div>
</x-panel>
