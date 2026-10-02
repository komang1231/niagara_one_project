<x-page-header eyebrow="Permintaan" title="Tukar Shift"
    description="Kelola pengajuan tukar shift karyawan beserta status persetujuannya." icon="bi-calendar2-check-fill">
    <x-slot:badges>
        <x-badge>{{ $permintaanTukarShift->total() }} permintaan</x-badge>
    </x-slot:badges>

    <x-slot:actions>
        <x-button variant="outline" icon="bi-trash" href="{{ route('permintaan-tukar-shift.trash') }}">
            Trash
        </x-button>

        <x-button variant="primary" icon="bi-plus-lg" data-bs-toggle="offcanvas"
            data-bs-target="#offcanvas-permintaan-tukar-shift">
            Tambah Permintaan Tukar Shift
        </x-button>
    </x-slot:actions>
</x-page-header>

<x-panel>
    <div>
        <x-filter.bar :clearable="['search', 'status']" ajax-target="#tukar-shift-table">

            <x-filter.search placeholder="Cari nama atau kode Tukar Shift" />

            {{-- <x-filter.multiselect name="status" label="Status" :options="$statusOptions" /> --}}

        </x-filter.bar>
    </div>

    <hr class="app-panel__divider">
    <div id="tukar-shift-table"></div>

    <x-table.table>
        <thead>
            <tr>
                <th class="app-table__col-no">NO</th>
                <th>Kode</th>
                <th>Pemohon</th>
                <th>Info Utama</th>
                <th>Status</th>
                <th class="app-table__col-actions">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($permintaanTukarShift as $i => $row)
                <tr>
                    <td class="app-table__col-no">
                        {{ $permintaanTukarShift->firstItem() + $i }}
                    </td>
                    <td class="fw-semibold">{{ $row->kode }}</td>
                    <td>
                        <x-table.cell-stack :avatar="$row->karyawanPengaju?->avatar" :lines="[$row->karyawanPengaju?->nama ?? '-']" />
                    </td>
                    <x-table.cell-stack :lines="[
                        'Ditukar dengan ' . $row->karyawanPengganti?->nama,
                        $row->tanggal_tujuan . ' • ' . $row->shift_pengaju . ' ⇄ ' . $row->shift_pengganti,
                    ]" />
                    </td>
                    <td>@include('permintaan.partials.status', ['row' => $row])</td>
                    <td>@include('permintaan.partials.aksi', [
                        'row' => $row,
                        'slug' => 'permintaan-tukar-shift',
                        'label' => 'Permintaan Tukar Shift',
                    ])</td>
                </tr>
            @empty
                <x-table.empty-row :colspan="5" text="Belum ada permintaan tukar shift." />
            @endforelse
        </tbody>
    </x-table.table>

    <div class="mt-3">{{ $permintaanTukarShift->links() }}</div>

</x-panel>
