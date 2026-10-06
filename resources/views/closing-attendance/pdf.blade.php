<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <title>Detail Rekapan Absensi - {{ $periode['label'] }}</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 12mm 10mm 14mm 10mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            background: #fff;
            color: #1f2937;
            font-family: DejaVu Sans, sans-serif;
            font-size: 9pt;
        }

        /* =========================================
           ca-print-header
        ========================================= */

        .ca-print-header {
            display: table;
            width: 100%;
            padding-bottom: 8px;
            margin-bottom: 12px;
            border-bottom: 2px solid #198754;
        }

        .ca-print-header-logo {
            display: table-cell;
            width: 55px;
            vertical-align: middle;
        }

        .ca-print-header-logo img {
            display: block;
            height: 44px;
            width: auto;
        }

        .ca-print-header-content {
            display: table-cell;
            vertical-align: middle;
        }

        .ca-print-title {
            margin: 0;
            font-size: 15pt;
            font-weight: 700;
            color: #1f2937;
        }

        .ca-print-subtitle {
            margin: 0;
            color: #4b5563;
            font-size: 9pt;
        }

        /* =========================================
           ca-info-grid
        ========================================= */

        .ca-info-grid {
            display: table;
            width: 100%;
            margin-bottom: 12px;
        }

        .ca-info-grid > div {
            display: table-cell;
            width: 33.333%;
            padding: 10px 12px;
            border: 1px solid #e5e7eb;
            vertical-align: middle;
        }

        .ca-info-grid > div + div {
            border-left: none;
        }

        .ca-info-label {
            font-size: 7.5pt;
            font-weight: 700;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            color: #6b7280;
        }

        .ca-info-value {
            margin-top: 2px;
            font-weight: 600;
            color: #1f2937;
            font-size: 9pt;
        }

        /* =========================================
           ca-summary
        ========================================= */

        .ca-summary {
            display: table;
            width: 100%;
            margin-bottom: 12px;
        }

        .ca-summary-card {
            display: table-cell;
            width: 16.666%;
            padding: 10px;
            border: 1px solid #e5e7eb;
            vertical-align: top;
        }

        .ca-summary-card + .ca-summary-card {
            border-left: none;
        }

        .ca-summary-label {
            font-size: 8pt;
            font-weight: 500;
            color: #6b7280;
        }

        .ca-summary-value {
            margin-top: 2px;
            font-size: 15pt;
            font-weight: 700;
            line-height: 1.2;
            color: #1f2937;
        }

        /* =========================================
           PANEL + TABLE
        ========================================= */

        .app-panel {
            width: 100%;
            padding: 14px;
            background: #fff;
            border: 1px solid #fff;
            border-radius: 8px;
        }

        .ca-report-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
        }

        .ca-report-table thead {
            display: table-header-group;
        }

        .ca-report-table thead th {
            padding: 8px 6px;
            text-align: left;
            font-size: 7.5pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: #1f2937;
            background: #f3f4f6;
            border-bottom: 1px solid #d1d5db;
            white-space: nowrap;
        }

        .ca-report-table tbody td {
            padding: 8px 6px;
            border-bottom: 1px solid #e5e7eb;
            color: #374151;
            vertical-align: middle;
        }

        .ca-report-table tr {
            page-break-inside: avoid;
        }

        .app-table__col-no {
            width: 35px;
            text-align: center !important;
            color: #6b7280 !important;
        }

        .ca-col-date {
            white-space: nowrap;
        }

        /* =========================================
           TABLE CELL STACK
        ========================================= */

        .app-table-stack {
            display: table;
            width: 100%;
        }

        .app-table-stack__avatar {
            display: table-cell;
            width: 28px;
            height: 28px;
            vertical-align: middle;
            text-align: center;
            border-radius: 50%;
            background: #e8f5e9;
            color: #198754;
            font-weight: 700;
            font-size: 8pt;
        }

        .app-table-stack__lines {
            display: table-cell;
            padding-left: 8px;
            vertical-align: middle;
        }

        .app-table-stack__line--title {
            display: block;
            font-weight: 700;
            color: #1f2937;
        }

        .app-table-stack__line--muted {
            display: block;
            margin-top: 2px;
            font-size: 7.5pt;
            color: #6b7280;
        }

        /* =========================================
           STATUS BADGE
        ========================================= */

        .app-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 999px;
            font-size: 7pt;
            font-weight: 600;
            white-space: nowrap;
        }

        .badge-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .badge-neutral {
            background: #f3f4f6;
            color: #4b5563;
            border: 1px solid #e5e7eb;
        }

        /* =========================================
           EMPTY STATE
        ========================================= */

        .app-table__empty {
            text-align: center;
            padding: 25px 10px !important;
            color: #6b7280 !important;
        }

        /* =========================================
           ca-print-footer
        ========================================= */

        .ca-print-footer {
            margin-top: 14px;
            padding-top: 6px;
            font-size: 8.5pt;
            text-align: center;
            color: #6b7280;
            border-top: 1px solid #d1d5db;
        }

        /* =========================================
           PAGE NUMBER
        ========================================= */

        .page-number {
            position: fixed;
            bottom: -8mm;
            right: 0;
            font-size: 8pt;
            color: #6b7280;
        }

        .page-number:after {
            content: "Halaman " counter(page);
        }
    </style>
</head>

<body>

    <section class="attendance-report">

        {{-- Header khusus cetak --}}
        <div class="ca-print-header">
            {{-- <div class="ca-print-header-logo">
                <img src="{{ public_path('images/logo.png') }}" alt="Logo">
            </div> --}}

            <div class="ca-print-header-content">
                <h1 class="ca-print-title">REKAPAN ABSENSI KARYAWAN</h1>

                <p class="ca-print-subtitle">
                    Periode {{ $periode['label'] }}
                </p>
            </div>
        </div>


        {{-- Info periode --}}
        <div class="ca-info-grid">
            <div>
                <div class="ca-info-label">Periode</div>
                <div class="ca-info-value">
                    {{ $periode['rentang'] }}
                </div>
            </div>

            <div>
                <div class="ca-info-label">Di-close Oleh</div>
                <div class="ca-info-value">
                    {{ $periode['closed_by'] }}
                </div>
            </div>

            <div>
                <div class="ca-info-label">Tanggal Closing</div>
                <div class="ca-info-value">
                    {{ $periode['closed_at'] }}
                </div>
            </div>
        </div>


        {{-- Summary --}}
        <div class="ca-summary">

            <div class="ca-summary-card">
                <div class="ca-summary-label">
                    Total Karyawan
                </div>

                <div class="ca-summary-value">
                    {{ $summary['total_karyawan'] }}
                </div>
            </div>

            <div class="ca-summary-card">
                <div class="ca-summary-label">
                    Hadir
                </div>

                <div class="ca-summary-value">
                    {{ $summary['hadir'] }}
                </div>
            </div>

            <div class="ca-summary-card">
                <div class="ca-summary-label">
                    Terlambat
                </div>

                <div class="ca-summary-value">
                    {{ $summary['terlambat'] }}
                </div>
            </div>

            <div class="ca-summary-card">
                <div class="ca-summary-label">
                    Cuti
                </div>

                <div class="ca-summary-value">
                    {{ $summary['cuti'] }}
                </div>
            </div>

            <div class="ca-summary-card">
                <div class="ca-summary-label">
                    Izin
                </div>

                <div class="ca-summary-value">
                    {{ $summary['izin'] }}
                </div>
            </div>

            <div class="ca-summary-card">
                <div class="ca-summary-label">
                    Alpa
                </div>

                <div class="ca-summary-value">
                    {{ $summary['alpa'] }}
                </div>
            </div>

        </div>


        {{-- Tabel hasil absensi --}}
        <div class="app-panel">

            <table class="ca-report-table">

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

                        @php
                            $statusMap = [
                                'hadir' => ['Hadir', 'badge-success'],
                                'terlambat' => ['Terlambat', 'badge-warning'],
                                'alpa' => ['Alpa', 'badge-danger'],
                                'sakit' => ['Sakit', 'badge-neutral'],
                                'izin' => ['Izin', 'badge-neutral'],
                                'cuti' => ['Cuti', 'badge-neutral'],
                                'libur' => ['Libur', 'badge-neutral'],
                                'libur_nasional' => ['Libur Nasional', 'badge-neutral'],
                            ];

                            [$statusLabel, $statusClass] =
                                $statusMap[$a->status]
                                ?? [
                                    ucfirst(str_replace('_', ' ', (string) $a->status)),
                                    'badge-neutral',
                                ];
                        @endphp

                        <tr>

                            <td class="app-table__col-no">
                                {{ $i + 1 }}
                            </td>

                            <td>
                                <div class="app-table-stack">

                                    <div class="app-table-stack__avatar">
                                        {{ strtoupper(substr($a->karyawan?->nama ?? '-', 0, 1)) }}
                                    </div>

                                    <div class="app-table-stack__lines">

                                        <span class="app-table-stack__line--title">
                                            {{ $a->karyawan?->nama ?? '-' }}
                                        </span>

                                        <span class="app-table-stack__line--muted">
                                            NIP. {{ $a->karyawan?->nip ?? '-' }}
                                        </span>

                                    </div>

                                </div>
                            </td>

                            <td class="ca-col-date">
                                {{ \Carbon\Carbon::parse($a->tanggal)->locale('id')->translatedFormat('d M Y') }}
                            </td>

                            <td>
                                {{ $a->shift?->nama ?? '-' }}
                            </td>

                            <td>
                                {{ $a->jam_masuk
                                    ? \Carbon\Carbon::parse($a->jam_masuk)->format('H:i')
                                    : '-' }}
                            </td>

                            <td>
                                {{ $a->jam_keluar
                                    ? \Carbon\Carbon::parse($a->jam_keluar)->format('H:i')
                                    : '-' }}
                            </td>

                            <td>
                                <span class="app-badge {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>

                            <td>
                                {{ $a->keterangan ?? '-' }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" class="app-table__empty">
                                Belum ada data absensi pada periode ini.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Footer khusus cetak --}}
        <div class="ca-print-footer">
            Dokumen ini merupakan rekapan absensi periode yang telah ditutup.
            Ditutup oleh {{ $periode['closed_by'] }}
            pada {{ $periode['closed_tgl'] }}.
        </div>

    </section>

    <div class="page-number"></div>

</body>

</html>