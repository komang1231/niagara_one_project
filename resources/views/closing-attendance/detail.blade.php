@extends('layouts.app')

@section('title', 'Detail Rekapan Absensi')

@section('content')
    {{-- Page header: hanya tampil di layar (disembunyikan saat print) --}}
    <x-page-header eyebrow="Attendance" title="Rekapan Absensi" :description="'Detail hasil absensi karyawan untuk periode ' . $periode['label'] . '.'" icon="bi-calendar-check-fill">
        <x-slot:badges>
            <x-badge variant="success" icon="bi-lock-fill">{{ $periode['label'] }}</x-badge>
        </x-slot:badges>

        <x-slot:actions>
            <x-button variant="outline" icon="bi-arrow-left" :href="route('closing-attendance.index')">Kembali</x-button>
            <x-button variant="primary" icon="bi-file-earmark-pdf" :href="route('closing-attendance.export-pdf', $closing->id)">
                Export PDF
            </x-button>
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
