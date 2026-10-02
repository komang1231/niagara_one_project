<x-page-header eyebrow="Permintaan" title="Cuti"
    description="Kelola pengajuan cuti karyawan beserta status persetujuannya." icon="bi-calendar2-check-fill">
    <x-slot:badges>
        <x-badge>{{ $permintaanCuti->total() }} permintaan</x-badge>
    </x-slot:badges>

    <x-slot:actions>
        {{-- Hapus tombol Trash kalau route-nya belum ada --}}
        <x-button variant="outline" icon="bi-trash" href="{{ route('permintaan-cuti.trash') }}">
            Trash
        </x-button>

        <x-button variant="primary" icon="bi-plus-lg" data-bs-toggle="offcanvas"
            data-bs-target="#offcanvas-permintaan-cuti">
            Tambah Permintaan Cuti
        </x-button>
    </x-slot:actions>
</x-page-header>

<x-panel>
    <div>
        <x-filter.bar :clearable="['search', 'status']" ajax-target="#cuti-table">
            <x-filter.search placeholder="Cari nama atau kode Cuti" />

            {{-- <x-filter.multiselect name="status" label="Status" :options="$statusOptions" /> --}}
        </x-filter.bar>
    </div>

    <hr class="app-panel__divider">

    <div id="cuti-table">
        <x-table>
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
                @forelse ($permintaanCuti as $i => $row)
                    <tr>
                        <td class="app-table__col-no">
                            {{ $permintaanCuti->firstItem() + $i }}
                        </td>

                        <td class="fw-semibold">{{ $row->kode }}</td>

                        <td><x-table.cell-stack :avatar="$pemohon" :lines="[$pemohon]" /></td>

                        <td>
                            <x-table.cell-stack :lines="[
                                $row->cuti->nama ?? '-',
                                $mulai . ' - ' . $selesai . ' • ' . $row->details->count() . ' hari',
                            ]" />
                        </td>

                        <td>@include('permintaan.partials.status', ['row' => $row])</td>

                        <td class="app-table__col-actions">
                            @include('permintaan.partials.aksi', [
                                'row' => $row,
                                'slug' => 'permintaan-cuti',
                                'label' => 'Permintaan Cuti',
                            ])
                        </td>
                    </tr>
                @empty
                    <x-table.empty-row colspan="6" text="Belum ada permintaan cuti." />
                @endforelse
            </tbody>
        </x-table>

        <div class="app-table-footer">
            <span>
                Menampilkan
                {{ $permintaanCuti->firstItem() ?? 0 }}–{{ $permintaanCuti->lastItem() ?? 0 }}
                dari
                {{ $permintaanCuti->total() }}
                entri
            </span>

            <x-pagination :paginator="$permintaanCuti" />
        </div>
    </div>
</x-panel>