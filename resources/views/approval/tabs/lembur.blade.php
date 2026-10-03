<x-page-header eyebrow="Persetujuan" title="Lembur" description="Setujui atau tolak pengajuan lembur karyawan."
    icon="bi-clock-fill">
    <x-slot:badges>
        <x-badge>{{ $permintaanLembur->count() }} permintaan</x-badge>
    </x-slot:badges>
</x-page-header>

<x-panel>
    <div>
        {{-- Tanpa ajax-target: controller belum return partial, jadi search pakai submit biasa (aman, tidak error) --}}
        <x-filter.bar :clearable="['search', 'status']">
            <x-filter.search placeholder="Cari nama atau kode Lembur" />
        </x-filter.bar>
    </div>

    <hr class="app-panel__divider">

    <div id="lembur-table">
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
                @forelse ($permintaanLembur as $i => $row)
                    @php
                        $pemohon = $row->karyawan->nama ?? '-';
                        $tanggal = \Carbon\Carbon::parse($row->tanggal_tujuan)->format('d/m/Y');
                        $jamMulai = substr((string) $row->jam_mulai, 0, 5);
                        $jamSelesai = substr((string) $row->jam_selesai, 0, 5);
                    @endphp
                    <tr>
                        <td class="app-table__col-no">
                            {{ $permintaanLembur->firstItem() + $i }}
                        </td>

                        <td class="fw-semibold">{{ $row->kode }}</td>

                        <td><x-table.cell-stack :avatar="$pemohon" :lines="[$pemohon]" /></td>

                        <td>
                            <x-table.cell-stack :lines="[$tanggal, $jamMulai . ' - ' . $jamSelesai . ' • x' . $row->pengali]" />
                        </td>

                        <td>@include('permintaan.partials.status', ['row' => $row])</td>

                        <td class="app-table__col-actions">
                            <x-approval.actions :row="$row" slug="lembur" label="Permintaan Lembur" />
                        </td>
                    </tr>
                @empty
                    <x-table.empty-row colspan="6" text="Belum ada permintaan lembur." />
                @endforelse
            </tbody>
        </x-table>

        <div class="mt-3">
            {{ $permintaanLembur->links() }}
        </div>
    </div>
</x-panel>
