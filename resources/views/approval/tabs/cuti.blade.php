
<x-page-header eyebrow="Persetujuan" title="Cuti"
    description="Setujui atau tolak pengajuan cuti karyawan." icon="bi-calendar2-check-fill">
    <x-slot:badges>
        <x-badge>{{ $total }} permintaan</x-badge>
    </x-slot:badges>
</x-page-header>

<x-panel>
    <div>
        {{-- Tanpa ajax-target: controller belum return partial, jadi search pakai submit biasa (aman, tidak error) --}}
        <x-filter.bar :clearable="['search', 'status']">
            <x-filter.search placeholder="Cari nama atau kode Cuti" />
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
                @forelse ($items as $i => $row)
                    @php
                        $pemohon = $row->karyawan->nama ?? '-';
                        $mulai = \Carbon\Carbon::parse($row->tanggal_mulai)->format('d/m/Y');
                        $selesai = \Carbon\Carbon::parse($row->tanggal_selesai)->format('d/m/Y');
                        $jumlahHari = optional($row->details)->count() ?? 0;
                    @endphp
                    <tr>
                        <td class="app-table__col-no">{{ $noAwal + $i }}</td>

                        <td class="fw-semibold">{{ $row->kode }}</td>

                        <td><x-table.cell-stack :avatar="$pemohon" :lines="[$pemohon]" /></td>

                        <td>
                            <x-table.cell-stack :lines="[
                                $row->cuti->nama ?? '-',
                                $mulai . ' - ' . $selesai . ' • ' . $jumlahHari . ' hari',
                            ]" />
                        </td>

                        <td>@include('permintaan.partials.status', ['row' => $row])</td>

                        <td class="app-table__col-actions">
                            <x-approval.actions :row="$row" slug="cuti" label="Permintaan Cuti" />
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
                {{ $paginated ? $items->firstItem() ?? 0 : $items->count() }}–{{ $paginated ? $items->lastItem() ?? 0 : $items->count() }}
                dari
                {{ $total }}
                entri
            </span>

            @if ($paginated)
                <x-pagination :paginator="$items" />
            @endif
        </div>
    </div>
</x-panel>
