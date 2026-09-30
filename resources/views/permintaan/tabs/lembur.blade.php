<x-page-header eyebrow="Permintaan" title="Lembur"
    description="Kelola pengajuan lembur karyawan beserta status persetujuannya." icon="bi-clock-fill">
    <x-slot:badges>
        <x-badge>{{ $permintaanLembur->total() }} permintaan</x-badge>
    </x-slot:badges>

    <x-slot:actions>
        <x-button variant="outline" icon="bi-trash" href="{{ route('permintaan-lembur.trash') }}">
            Trash
        </x-button>

        <x-button variant="primary" icon="bi-plus-lg" data-bs-toggle="offcanvas"
            data-bs-target="#offcanvas-permintaan-lembur">
            Tambah Permintaan Lembur
        </x-button>
    </x-slot:actions>
</x-page-header>

<x-panel>

    <div>
        <x-filter.bar :clearable="['search', 'status']" ajax-target="#lembur-table">

            <x-filter.search placeholder="Cari nama atau kode Lembur" />

            {{-- <x-filter.multiselect name="status" label="Status" :options="$statusOptions" /> --}}

        </x-filter.bar>
    </div>

    <hr class="app-panel__divider">
    <div id="lembur-table"></div>

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
            @forelse ($permintaanLembur as $i => $row)
                @php
                    $pemohon = $row->karyawan->nama ?? '-';
                    $tanggal = \Carbon\Carbon::parse($row->tanggal_tujuan)->format('d/m/Y');
                @endphp
                <tr>
                    <td class="app-table__col-no">
                        {{ $permintaanLembur->firstItem() + $i }}
                    </td>
                    <td class="fw-semibold">{{ $row->kode }}</td>
                    <td><x-table.cell-stack :avatar="$pemohon" :lines="[$pemohon]" /></td>
                    <td>
                        <x-table.cell-stack :lines="[
                            $tanggal,
                            substr($row->jam_mulai, 0, 5) .
                            ' - ' .
                            substr($row->jam_selesai, 0, 5) .
                            ' • x' .
                            $row->pengali,
                        ]" />
                    </td>
                    <td>@include('permintaan.partials.status', ['row' => $row])</td>
                    <td>@include('permintaan.partials.aksi', [
                        'row' => $row,
                        'slug' => 'permintaan-lembur',
                        'label' => 'Permintaan Lembur',
                    ])</td>
                </tr>
            @empty
                <x-table.empty-row :colspan="5" text="Belum ada permintaan lembur." />
            @endforelse
        </tbody>
    </x-table>

    <div class="mt-3">{{ $permintaanLembur->links() }}</div>
</x-panel>
