@extends('layouts.app')

@section('title', 'Detail Rekapan Absensi')

@section('content')
    {{-- @php
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $dummyPeriode = [
            12 => [9, 2026, 'Admin HR', '2026-09-30 15:10'],
            11 => [8, 2026, 'Admin HR', '2026-08-31 14:45'],
            10 => [7, 2026, 'Super Admin', '2026-07-31 16:02'],
            9 => [6, 2026, 'Admin HR', '2026-06-30 13:30'],
            8 => [5, 2026, 'Admin HR', '2026-05-31 15:20'],
            7 => [4, 2026, 'Admin HR', '2026-04-30 14:05'],
            6 => [3, 2026, 'Super Admin', '2026-03-31 17:12'],
            5 => [2, 2026, 'Admin HR', '2026-02-28 11:50'],
            4 => [1, 2026, 'Admin HR', '2026-01-31 14:32'],
            3 => [12, 2025, 'Admin HR', '2025-12-31 15:40'],
            2 => [11, 2025, 'Super Admin', '2025-11-30 10:15'],
            1 => [10, 2025, 'Admin HR', '2025-10-31 16:25'],
        ];
        $idPeriode = (int) ($id ?? (request()->route('id') ?? 4));
        [$bln, $thn, $closedBy, $closedAtRaw] = $dummyPeriode[$idPeriode] ?? $dummyPeriode[4];

        $awal = \Carbon\Carbon::create($thn, $bln, 1);
        $akhirBulan = $awal->daysInMonth;
        $closedAt = \Carbon\Carbon::parse($closedAtRaw)->locale('id');
        $tgl = fn($v, $format = 'd M Y') => \Carbon\Carbon::parse($v)->locale('id')->translatedFormat($format);
        $stack = fn(...$lines) => array_values(array_filter($lines, 'filled'));

        $periode = [
            'label' => $namaBulan[$bln] . ' ' . $thn,
            'rentang' => '01 ' . $namaBulan[$bln] . ' ' . $thn . ' - ' . $akhirBulan . ' ' . $namaBulan[$bln] . ' ' . $thn,
            'closed_by' => $closedBy,
            'closed_at' => $closedAt->translatedFormat('d F Y, H:i') . ' WIB',
            'closed_tgl' => $closedAt->translatedFormat('d F Y'),
        ];

        // Summary (dummy)
        $summary = [
            ['label' => 'Total Karyawan', 'value' => 125, 'icon' => 'bi-people-fill', 'tone' => 'neutral'],
            ['label' => 'Hadir', 'value' => 108, 'icon' => 'bi-check-circle-fill', 'tone' => 'success'],
            ['label' => 'Terlambat', 'value' => 9, 'icon' => 'bi-clock-history', 'tone' => 'warning'],
            ['label' => 'Cuti', 'value' => 4, 'icon' => 'bi-calendar2-heart-fill', 'tone' => 'neutral'],
            ['label' => 'Izin', 'value' => 2, 'icon' => 'bi-envelope-paper-fill', 'tone' => 'neutral'],
            ['label' => 'Alpa', 'value' => 2, 'icon' => 'bi-x-circle-fill', 'tone' => 'danger'],
        ];

        // Karyawan dummy
        $karyawan = [
            ['Budi Santoso', '20250123', 'Staff IT', 'IT Department'],
            ['Siti Rahmawati', '20250124', 'Staff HRD', 'HR Department'],
            ['Andi Pratama', '20250125', 'Akuntan', 'Finance Department'],
            ['Dewi Lestari', '20250126', 'Marketing Executive', 'Marketing Department'],
            ['Rizky Ramadhan', '20250127', 'Teknisi', 'Operasional'],
            ['Putri Anggraini', '20250128', 'Admin Gudang', 'Logistik'],
            ['Agus Setiawan', '20250129', 'Supervisor', 'Operasional'],
            ['Nur Aini', '20250130', 'Staff Finance', 'Finance Department'],
            ['Fajar Nugroho', '20250131', 'Programmer', 'IT Department'],
            ['Maya Sari', '20250132', 'Customer Service', 'Layanan Pelanggan'],
        ];

        // Pola status (dummy). Nilai mengikuti enum attendances: hadir, terlambat, sakit, cuti, alpa, izin, libur
        $polaStatus = ['hadir', 'hadir', 'terlambat', 'hadir', 'cuti', 'hadir', 'izin', 'hadir', 'sakit', 'alpa',
            'hadir', 'libur', 'hadir', 'terlambat', 'hadir', 'hadir', 'cuti', 'hadir', 'izin', 'hadir'];

        $ketStatus = [
            'hadir' => '-',
            'cuti' => 'Cuti tahunan',
            'izin' => 'Keperluan keluarga',
            'sakit' => 'Surat dokter terlampir',
            'alpa' => 'Tanpa keterangan',
            'libur' => 'Hari libur',
        ];

        $absensi = [];
        foreach ($polaStatus as $i => $status) {
            $emp = $karyawan[$i % count($karyawan)];
            $hari = min(2 + intdiv($i, 4), $akhirBulan);
            $hadir = in_array($status, ['hadir', 'terlambat']);
            $menit = ($i * 3) % 10;
            $telat = 20 + (($i * 7) % 30);

            $absensi[] = [
                'nama' => $emp[0],
                'nip' => $emp[1],
                'jabatan' => $emp[2],
                'dept' => $emp[3],
                'tanggal' => $tgl(\Carbon\Carbon::create($thn, $bln, $hari)),
                'shift' => $i % 4 === 3 ? 'Shift Siang' : 'Shift Pagi',
                'masuk' => $hadir ? sprintf('08:%02d', $status === 'terlambat' ? $telat : $menit) : '-',
                'keluar' => $hadir ? sprintf('17:%02d', ($i * 5) % 20) : '-',
                'status' => $status,
                'ket' => $status === 'terlambat' ? "Terlambat {$telat} menit" : $ketStatus[$status],
            ];
        }
    @endphp --}}
    @php
        $namaBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $periodeBulan = $namaBulan[$closing->bulan] ?? '-';

        $closedBy = $closing->user?->nama ?? '-';

        $tanggalClosing = $closing->created_at
            ? \Carbon\Carbon::parse($closing->created_at)->locale('id')->translatedFormat('d F Y')
            : '-';

        $jamClosing = $closing->created_at
            ? \Carbon\Carbon::parse($closing->created_at)->locale('id')->translatedFormat('H:i') . ' WIB'
            : '-';

        $stack = fn(...$lines) => array_values(array_filter($lines, 'filled'));

        $periode = [
            'label' => $periodeBulan . ' ' . $closing->tahun,

            'rentang' => $awal->translatedFormat('d F Y') . ' - ' . $akhir->translatedFormat('d F Y'),

            'closed_by' => $closedBy,

            'closed_at' => $tanggalClosing . ' ' . $jamClosing,

            'closed_tgl' => $tanggalClosing,
        ];
    @endphp

    {{-- Page header: hanya tampil di layar (disembunyikan saat print) --}}
    <x-page-header eyebrow="Attendance" title="Rekapan Absensi" :description="'Detail hasil absensi karyawan untuk periode ' . $periode['label'] . '.'" icon="bi-calendar-check-fill">
        <x-slot:badges>
            <x-badge variant="success" icon="bi-lock-fill">{{ $periode['label'] }}</x-badge>
        </x-slot:badges>

        <x-slot:actions>
            <x-button variant="outline" icon="bi-arrow-left" :href="route('closing-attendance.index')">Kembali</x-button>
            <x-button variant="primary" icon="bi-file-earmark-pdf" id="btnExportPdf">Export PDF</x-button>
        </x-slot:actions>
    </x-page-header>

    {{-- AREA LAPORAN: hanya bagian ini yang dicetak (closing-attendance.js + @media print) --}}
    <section class="attendance-report">

        {{-- Header khusus cetak --}}
        <div class="ca-print-only ca-print-header">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" onerror="this.style.display='none'">
            <div>
                <h1 class="ca-print-title">REKAPAN ABSENSI KARYAWAN</h1>
                <p class="ca-print-subtitle">Periode {{ $periode['label'] }}</p>
            </div>
        </div>

        {{-- Info periode --}}
        <div class="ca-info-grid">
            <div>
                <div class="ca-info-label">Periode</div>
                <div class="ca-info-value">{{ $periode['rentang'] }}</div>
            </div>
            <div>
                <div class="ca-info-label">Di-close Oleh</div>
                <div class="ca-info-value">{{ $periode['closed_by'] }}</div>
            </div>
            <div>
                <div class="ca-info-label">Tanggal Closing</div>
                <div class="ca-info-value">{{ $periode['closed_at'] }}</div>
            </div>
        </div>

        {{-- Summary --}}
        <div class="ca-summary">
            <div class="ca-summary-card">
                <div class="ca-summary-label">
                    <i class="bi bi-people-fill"></i>Total Karyawan
                </div>
                <div class="ca-summary-value">{{ $summary['total_karyawan'] }}</div>
            </div>

            <div class="ca-summary-card">
                <div class="ca-summary-label">
                    <i class="bi bi-check-circle-fill"></i>Hadir
                </div>
                <div class="ca-summary-value">{{ $summary['hadir'] }}</div>
            </div>

            <div class="ca-summary-card">
                <div class="ca-summary-label">
                    <i class="bi bi-clock-fill"></i>Terlambat
                </div>
                <div class="ca-summary-value">{{ $summary['terlambat'] }}</div>
            </div>

            <div class="ca-summary-card">
                <div class="ca-summary-label">
                    <i class="bi bi-calendar-check-fill"></i>Cuti
                </div>
                <div class="ca-summary-value">{{ $summary['cuti'] }}</div>
            </div>

            <div class="ca-summary-card">
                <div class="ca-summary-label">
                    <i class="bi bi-info-circle-fill"></i>Izin
                </div>
                <div class="ca-summary-value">{{ $summary['izin'] }}</div>
            </div>

            <div class="ca-summary-card">
                <div class="ca-summary-label">
                    <i class="bi bi-x-circle-fill"></i>Alpa
                </div>
                <div class="ca-summary-value">{{ $summary['alpa'] }}</div>
            </div>
        </div>

        {{-- Tabel hasil absensi --}}
        <x-panel>
            <x-table class="ca-report-table">
                <thead>
                    <tr>
                        <th class="app-table__col-no">NO</th>
                        <th>Karyawan</th>
                        <th>Tanggal</th>
                        <th>Shift</th>
                        <th>Jam Masuk</th>
                        <th>Jam Keluar</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($attendances as $i => $a)
                        <tr>
                            <td class="app-table__col-no">{{ $i + 1 }}</td>

                            <td>
                                <x-table.cell-stack :avatar="$a->karyawan?->nama ?? '-'" :lines="$stack($a->karyawan?->nama ?? '-', 'NIP. ' . ($a->karyawan?->nip ?? '-'))" />
                            </td>

                            <td class="ca-col-date">
                                {{ \Carbon\Carbon::parse($a->tanggal)->locale('id')->translatedFormat('d M Y') }}
                            </td>

                            <td>
                                {{ $a->shift?->nama ?? '-' }}
                            </td>

                            <td>
                                {{ $a->jam_masuk ? \Carbon\Carbon::parse($a->jam_masuk)->format('H:i') : '-' }}
                            </td>

                            <td>
                                {{ $a->jam_keluar ? \Carbon\Carbon::parse($a->jam_keluar)->format('H:i') : '-' }}
                            </td>

                            <td>
                                <x-attendance.status-badge :status="$a->status" />
                            </td>

                            <td>
                                {{ $a->keterangan ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <x-table.empty-row colspan="8" text="Belum ada data absensi pada periode ini." />
                    @endforelse
                </tbody>
            </x-table>
        </x-panel>

        {{-- Footer khusus cetak --}}
        <div class="ca-print-only ca-print-footer">
            Dokumen ini merupakan rekapan absensi periode yang telah ditutup.
            Ditutup oleh {{ $periode['closed_by'] }} pada {{ $periode['closed_tgl'] }}.
        </div>
    </section>
@endsection
