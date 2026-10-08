@php
    $rows = $attendances ?? null;

    $statusOptions = [
        'hadir' => 'Hadir',
        'terlambat' => 'Terlambat',
        'sakit' => 'Sakit',
        'izin' => 'Izin',
        'cuti' => 'Cuti',
        'alpa' => 'Alpa',
        'libur' => 'Libur',
        'libur_nasional' => 'Libur Nasional',
    ];

    $jam = fn($t) => $t ? \Carbon\Carbon::parse($t)->format('H:i') : null;
    $tgl = fn($v) => $v ? \Carbon\Carbon::parse($v)->format('d M Y') : '-';

    $durasi = function ($menit) {
        if ($menit === null) {
            return null;
        }
        $menit = (int) round($menit);
        $h = intdiv($menit, 60);
        $m = $menit % 60;

        return $h > 0 ? "{$h} j {$m} mnt" : "{$m} mnt";
    };
@endphp

<x-panel class="attendance-monitor">
    <div class="attendance-monitor__head">
        <div>
            <h2 class="attendance-monitor__title">Aktivitas absensi karyawan</h2>
            <p class="attendance-monitor__desc">Pantau jam masuk, jam keluar, dan status kehadiran seluruh karyawan.</p>
        </div>

        @if ($rows)
            <x-badge>{{ $rows->total() }} data</x-badge>
        @endif
    </div>

    <x-filter.bar :clearable="['search', 'status', 'tanggal']">
        <x-filter.search placeholder="Cari nama atau NIP karyawan" />

        <x-filter.multiselect name="status" label="Status" :options="$statusOptions" />

        <x-filter.date-range name="tanggal" label="Tanggal" type="date" />
    </x-filter.bar>

    <hr class="app-panel__divider">

    <div id="attendance-table">
        <x-table>
            <thead>
                <tr>
                    <th class="app-table__col-no">NO</th>
                    <th>Karyawan</th>
                    <th>Tanggal</th>
                    <th>Shift</th>
                    <th>Jam Masuk</th>
                    <th>Jam Keluar</th>
                    <th>Durasi Kerja</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($rows ?? [] as $i => $row)
                    @php
                        $shiftNama = $row->shift?->nama;
                        $shiftJam = $row->shift
                            ? $jam($row->shift->jam_masuk) . ' – ' . $jam($row->shift->jam_pulang)
                            : null;
                    @endphp

                    <tr>
                        <td class="app-table__col-no">{{ $rows->firstItem() + $i }}</td>

                        <td>
                            <x-table.cell-stack :avatar="$row->karyawan?->nama" :lines="array_values(array_filter([$row->karyawan?->nama ?? '-', $row->karyawan?->nip], 'filled'))" />
                        </td>

                        <td class="attendance-monitor__nowrap">{{ $tgl($row->tanggal) }}</td>

                        <td>
                            @if ($shiftNama)
                                <x-table.cell-stack :lines="[$shiftNama, $shiftJam]" />
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>

                        {{-- Jam masuk + keterangan terlambat --}}
                        <td>
                            @if ($row->jam_masuk)
                                <x-table.cell-stack :lines="array_values(array_filter([
                                    $jam($row->jam_masuk),
                                    $row->total_menit_terlambat ? 'Terlambat ' . $durasi($row->total_menit_terlambat) : null,
                                ], 'filled'))" />
                            @else
                                <span class="text-muted">–</span>
                            @endif
                        </td>

                        {{-- Jam keluar + keterangan pulang cepat --}}
                        <td>
                            @if ($row->jam_keluar)
                                <x-table.cell-stack :lines="array_values(array_filter([
                                    $jam($row->jam_keluar),
                                    $row->total_menit_pulang_cepat ? 'Pulang cepat ' . $durasi($row->total_menit_pulang_cepat) : null,
                                ], 'filled'))" />
                            @elseif ($row->jam_masuk)
                                <span class="attendance-monitor__working"><i class="bi bi-circle-fill"></i> Sedang bekerja</span>
                            @else
                                <span class="text-muted">–</span>
                            @endif
                        </td>

                        <td>
                            @if ($row->total_menit_kerja !== null)
                                {{ $durasi($row->total_menit_kerja) }}
                            @else
                                <span class="text-muted">–</span>
                            @endif
                        </td>

                        <td>
                            <div class="attendance-monitor__status">
                                <x-attendance.status-badge :status="$row->status" />
                                @if ($row->is_manual)
                                    <x-badge icon="bi-pencil-square">Manual</x-badge>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-table.empty-row colspan="8" text="Belum ada data absensi." />
                @endforelse
            </tbody>
        </x-table>

        @if ($rows)
            <div class="app-table-footer">
                <span>
                    Menampilkan {{ $rows->firstItem() ?? 0 }}–{{ $rows->lastItem() ?? 0 }}
                    dari {{ $rows->total() }} entri
                </span>

                <x-pagination :paginator="$rows" />
            </div>
        @endif
    </div>
</x-panel>
