<x-page-header eyebrow="Permintaan" title="Resign"
    description="Kelola pengajuan resign karyawan beserta status persetujuannya." icon="bi-box-arrow-right">
    <x-slot:badges>
        <x-badge>{{ $permintaanResign->total() }} permintaan</x-badge>
    </x-slot:badges>

    <x-slot:actions>
        <x-button variant="outline" icon="bi-trash" href="{{ route('permintaan-resign.trash') }}">
            Trash
        </x-button>

        <x-button variant="primary" icon="bi-plus-lg" data-bs-toggle="offcanvas"
            data-bs-target="#offcanvas-permintaan-resign">
            Tambah Permintaan Resign
        </x-button>
    </x-slot:actions>
</x-page-header>

<x-panel>
    <div>
        <x-filter.bar :clearable="['search', 'status']" ajax-target="#resign-table">

            <x-filter.search placeholder="Cari nama atau kode Resign" />

            {{-- <x-filter.multiselect name="status" label="Status" :options="$statusOptions" /> --}}

        </x-filter.bar>
    </div>

    <hr class="app-panel__divider">
    <div id="resign-table"></div>

    <x-table.table>
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
            @forelse ($permintaanResign as $i => $row)
                <tr>
                    <td class="app-table__col-no">
                        {{ $permintaanResign->firstItem() + $i }}
                    </td>
                    <td class="fw-semibold">{{ $row->kode }}</td>
                    <td><x-table.cell-stack :avatar="$pemohon" :lines="[$pemohon]" /></td>
                    <td><x-table.cell-stack :lines="['Efektif ' . $efektif, \Illuminate\Support\Str::limit($row->alasan, 40)]" /></td>
                    <td>@include('permintaan.partials.status', ['row' => $row])</td>
                    <td>@include('permintaan.partials.aksi', [
                        'row' => $row,
                        'slug' => 'permintaan-resign',
                        'label' => 'Permintaan Resign',
                    ])</td>
                </tr>
            @empty
                <x-table.empty-row :colspan="5" text="Belum ada permintaan resign." />
            @endforelse
        </tbody>
    </x-table.table>

    <div class="mt-3">{{ $permintaanResign->links() }}</div>
</x-panel>
