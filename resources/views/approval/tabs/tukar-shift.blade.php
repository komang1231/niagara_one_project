<x-page-header eyebrow="Persetujuan" title="Tukar Shift"
    description="Setujui atau tolak pengajuan tukar shift karyawan." icon="bi-arrow-left-right">
    <x-slot:badges>
        <x-badge>{{ $total }} permintaan</x-badge>
    </x-slot:badges>
</x-page-header>

<x-panel>
    <div>
        {{-- Tanpa ajax-target: controller belum return partial, jadi search pakai submit biasa (aman, tidak error) --}}
        <x-filter.bar :clearable="['search', 'status']">
            <x-filter.search placeholder="Cari nama atau kode Tukar Shift" />
        </x-filter.bar>
    </div>

    <hr class="app-panel__divider">

    <div id="tukar-shift-table">
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
                        $pengaju = $namaKaryawan[$row->karyawan_pengaju] ?? '-';
                        $pengganti = $namaKaryawan[$row->karyawan_pengganti] ?? '-';
                        $shiftA = $namaShift[$row->shift_pengaju] ?? '-';
                        $shiftB = $namaShift[$row->shift_pengganti] ?? '-';
                        $tanggal = \Carbon\Carbon::parse($row->tanggal_tujuan)->format('d/m/Y');
                    @endphp
                    <tr>
                        <td class="app-table__col-no">{{ $noAwal + $i }}</td>

                        <td class="fw-semibold">{{ $row->kode }}</td>

                        <td><x-table.cell-stack :avatar="$pengaju" :lines="[$pengaju]" /></td>

                        <td>
                            <x-table.cell-stack :lines="['Ditukar dengan ' . $pengganti, $tanggal . ' • ' . $shiftA . ' ⇄ ' . $shiftB]" />
                        </td>

                        <td>@include('permintaan.partials.status', ['row' => $row])</td>

                        <td class="app-table__col-actions">
                            <x-approval.actions :row="$row" slug="tukar-shift" label="Permintaan Tukar Shift" />
                        </td>
                    </tr>
                @empty
                    <x-table.empty-row colspan="6" text="Belum ada permintaan tukar shift." />
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
