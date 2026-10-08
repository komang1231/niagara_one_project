<x-page-header eyebrow="Persetujuan" title="Tukar Shift" description="Setujui atau tolak pengajuan tukar shift karyawan."
    icon="bi-arrow-left-right">
    <x-slot:badges>
        <x-badge>{{ $permintaanTukarShift->count() }} permintaan</x-badge>
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
                @forelse ($permintaanTukarShift as $i => $row)
                    @php
                        $pengaju = $row->karyawanPengaju?->nama ?? '-';
                        $pengganti = $row->karyawanPengganti?->nama ?? '-';
                        $shiftA = $row->shiftPengaju?->nama ?? '-';
                        $shiftB = $row->shiftPengganti?->nama ?? '-';
                        $tanggal = \Carbon\Carbon::parse($row->tanggal_tujuan)->format('d M Y');
                    @endphp
                    <tr>
                        <td class="app-table__col-no">
                            {{ $permintaanTukarShift->firstItem() + $i }}
                        </td>

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

        <div class="mt-3">
            {{ $permintaanTukarShift->links() }}
        </div>
    </div>
</x-panel>
