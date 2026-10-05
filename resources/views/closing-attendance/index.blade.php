@extends('layouts.app')

@section('title', 'Rekapan Absensi')

@section('content')
    {{-- @php
        $dummyKosong = false; // ubah ke true untuk melihat empty state "Belum Ada Rekapan Absensi"

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        $tahunOptions = [2026 => '2026', 2025 => '2025', 2024 => '2024'];

        $semua = collect($dummyKosong ? [] : [
            ['id' => 12, 'bulan' => 9,  'tahun' => 2026, 'closed_by' => 'Admin HR',    'role' => 'HR Administrator', 'closed_at' => '2026-09-30 15:10'],
            ['id' => 11, 'bulan' => 8,  'tahun' => 2026, 'closed_by' => 'Admin HR',    'role' => 'HR Administrator', 'closed_at' => '2026-08-31 14:45'],
            ['id' => 10, 'bulan' => 7,  'tahun' => 2026, 'closed_by' => 'Super Admin', 'role' => 'Super Admin',      'closed_at' => '2026-07-31 16:02'],
            ['id' => 9,  'bulan' => 6,  'tahun' => 2026, 'closed_by' => 'Admin HR',    'role' => 'HR Administrator', 'closed_at' => '2026-06-30 13:30'],
            ['id' => 8,  'bulan' => 5,  'tahun' => 2026, 'closed_by' => 'Admin HR',    'role' => 'HR Administrator', 'closed_at' => '2026-05-31 15:20'],
            ['id' => 7,  'bulan' => 4,  'tahun' => 2026, 'closed_by' => 'Admin HR',    'role' => 'HR Administrator', 'closed_at' => '2026-04-30 14:05'],
            ['id' => 6,  'bulan' => 3,  'tahun' => 2026, 'closed_by' => 'Super Admin', 'role' => 'Super Admin',      'closed_at' => '2026-03-31 17:12'],
            ['id' => 5,  'bulan' => 2,  'tahun' => 2026, 'closed_by' => 'Admin HR',    'role' => 'HR Administrator', 'closed_at' => '2026-02-28 11:50'],
            ['id' => 4,  'bulan' => 1,  'tahun' => 2026, 'closed_by' => 'Admin HR',    'role' => 'HR Administrator', 'closed_at' => '2026-01-31 14:32'],
            ['id' => 3,  'bulan' => 12, 'tahun' => 2025, 'closed_by' => 'Admin HR',    'role' => 'HR Administrator', 'closed_at' => '2025-12-31 15:40'],
            ['id' => 2,  'bulan' => 11, 'tahun' => 2025, 'closed_by' => 'Super Admin', 'role' => 'Super Admin',      'closed_at' => '2025-11-30 10:15'],
            ['id' => 1,  'bulan' => 10, 'tahun' => 2025, 'closed_by' => 'Admin HR',    'role' => 'HR Administrator', 'closed_at' => '2025-10-31 16:25'],
        ]);

        $totalClosing = $semua->count();

        // Pilihan multiselect: default semua terpilih, sama seperti <x-filter.multiselect>
        $dipilih = fn($name, $options) => request()->has("{$name}_state")
            ? collect(request($name, []))->map(fn($v) => (string) $v)->all()
            : array_map('strval', array_keys($options));

        $tahunDipilih = $dipilih('tahun', $tahunOptions);
        $bulanDipilih = $dipilih('bulan', $namaBulan);
        $kataKunci = strtolower(trim((string) request('q', '')));

        $hasil = $semua
            ->filter(
                fn($r) => (!$kataKunci ||
                    str_contains(strtolower($namaBulan[$r['bulan']] . ' ' . $r['tahun'] . ' ' . $r['closed_by']), $kataKunci)) &&
                    (empty($tahunDipilih) || in_array((string) $r['tahun'], $tahunDipilih)) &&
                    (empty($bulanDipilih) || in_array((string) $r['bulan'], $bulanDipilih)),
            )
            ->values();

        $perPage = 5;
        $halaman = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $closingAttendances = new \Illuminate\Pagination\LengthAwarePaginator(
            $hasil->forPage($halaman, $perPage)->values(),
            $hasil->count(),
            $perPage,
            $halaman,
            ['path' => request()->url(), 'query' => request()->query()],
        );

        // Helper tampilan
        $tgl = fn($v, $format = 'd M Y') => \Carbon\Carbon::parse($v)->locale('id')->translatedFormat($format);
        $stack = fn(...$lines) => array_values(array_filter($lines, 'filled'));
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

        $totalClosing = $closingAttendances->total();

        $tahunOptions = $tahunOptions->toArray();

        $dipilih = fn($name, $options) => request()->has("{$name}_state")
            ? collect(request($name, []))->map(fn($v) => (string) $v)->all()
            : array_map('strval', array_keys($options));

        $tahunDipilih = $dipilih('tahun', $tahunOptions);
        $bulanDipilih = $dipilih('bulan', $namaBulan);

        $kataKunci = strtolower(trim((string) request('search', '')));

        $tgl = fn($v, $format = 'd M Y') => \Carbon\Carbon::parse($v)->locale('id')->translatedFormat($format);

        $stack = fn(...$lines) => array_values(array_filter($lines, 'filled'));
    @endphp

    <x-page-header eyebrow="Attendance" title="Rekapan Absensi"
        description="Lihat dan kelola rekapan absensi karyawan berdasarkan periode yang telah ditutup."
        icon="bi-calendar-check-fill">
        <x-slot:badges>
            <x-badge>{{ $totalClosing }} Closing</x-badge>
        </x-slot:badges>

        <x-slot:actions>
            <x-button variant="primary" icon="bi-lock-fill" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-closing">
                Closing Periode
            </x-button>
        </x-slot:actions>
    </x-page-header>

    <x-panel>
        @if ($totalClosing === 0)
            {{-- EMPTY STATE: belum ada periode yang di-closing sama sekali --}}
            <div class="ca-empty">
                <div class="ca-empty__icon"><i class="bi bi-calendar-x"></i></div>
                <h2 class="ca-empty__title">Belum Ada Rekapan Absensi</h2>
                <p class="ca-empty__desc">Belum ada periode absensi yang ditutup.</p>
            </div>
        @else
            <x-filter.bar>
                <x-filter.search placeholder="Cari bulan atau nama pengguna..." />

                <x-filter.multiselect name="tahun" label="Tahun" :options="$tahunOptions" />
                <x-filter.multiselect name="bulan" label="Bulan" :options="$namaBulan" />
            </x-filter.bar>

            <hr class="app-panel__divider">

            <div id="closing-attendance-table">
                <x-table>
                    <thead>
                        <tr>
                            <th class="app-table__col-no">NO</th>
                            <th>Periode</th>
                            <th>Tahun</th>
                            <th>Di-close Oleh</th>
                            <th>Tanggal Closing</th>
                            <th class="app-table__col-actions">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        {{-- @forelse ($closingAttendances as $i => $row)
                            @php
                                $awal = \Carbon\Carbon::create($row['tahun'], $row['bulan'], 1);
                                $akhir = $awal->copy()->endOfMonth();
                            @endphp

                            <tr>
                                <td class="app-table__col-no">{{ $closingAttendances->firstItem() + $i }}</td>

                                // Periode
                                <td>
                                    <x-table.cell-stack :lines="$stack(
                                        $namaBulan[$row['bulan']],
                                        'Periode ' . $tgl($awal, 'd M') . ' - ' . $tgl($akhir),
                                    )" />
                                </td>

                                // Tahun
                                <td><x-badge>{{ $row['tahun'] }}</x-badge></td>

                                // Di-close oleh
                                <td>
                                    <x-table.cell-stack :avatar="$row['closed_by']" :lines="$stack($row['closed_by'], $row['role'])" />
                                </td>

                                // Tanggal closing
                                <td class="ca-col-date">
                                    <x-table.cell-stack :lines="$stack($tgl($row['closed_at']), $tgl($row['closed_at'], 'H:i') . ' WIB')" />
                                </td>

                                // Aksi: hanya lihat & export, tidak ada edit/hapus/status
                                <td class="app-table__col-actions">
                                    <div class="app-table__actions">
                                        <x-button variant="icon-view" icon="bi-eye" title="Lihat detail absensi"
                                            :href="route('closing-attendance.show', $row['id'])" />

                                        // Export PDF: buka detail lalu otomatis window.print() (closing-attendance.js)
                                        <x-button variant="icon-danger" icon="bi-file-earmark-pdf" title="Export PDF"
                                            :href="route('closing-attendance.show', [
                                                'id' => $row['id'],
                                                'print' => 1,
                                            ])" />
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <x-table.empty-row colspan="6" text="Tidak ada rekapan yang sesuai dengan filter." />
                        @endforelse --}}
                        @forelse ($closingAttendances as $i => $row)
                            @php
                                $awal = \Carbon\Carbon::create($row->tahun, $row->bulan, 1);
                                $akhir = $awal->copy()->endOfMonth();

                                $closedBy = $row->user?->nama ?? '-';
                                $role = $row->user?->role?->nama ?? ($row->user?->role ?? '-');
                            @endphp

                            <tr>
                                <td class="app-table__col-no">{{ $closingAttendances->firstItem() + $i }}</td>

                                {{-- Periode --}}
                                <td>
                                    <x-table.cell-stack :lines="$stack(
                                        $namaBulan[$row->bulan],
                                        'Periode ' . $tgl($awal, 'd M') . ' - ' . $tgl($akhir),
                                    )" />
                                </td>

                                {{-- Tahun --}}
                                <td>
                                    <x-badge>{{ $row->tahun }}</x-badge>
                                </td>

                                {{-- Di-close oleh --}}
                                <td>
                                    <x-table.cell-stack :avatar="$closedBy" :lines="$stack($closedBy, $role)" />
                                </td>

                                {{-- Tanggal closing --}}
                                <td class="ca-col-date">
                                    <x-table.cell-stack :lines="$stack($tgl($row->created_at), $tgl($row->created_at, 'H:i') . ' WIB')" />
                                </td>

                                {{-- Aksi: hanya lihat & export, tidak ada edit/hapus/status --}}
                                <td class="app-table__col-actions">
                                    <div class="app-table__actions">
                                        <x-button variant="icon-view" icon="bi-eye" title="Lihat detail absensi"
                                            :href="route('closing-attendance.show', $row->id)" />

                                        {{-- Export PDF --}}
                                        <x-button variant="icon-danger" icon="bi-file-earmark-pdf" title="Export PDF"
                                            :href="route('closing-attendance.show', [
                                                'id' => $row->id,
                                                'print' => 1,
                                            ])" />
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <x-table.empty-row colspan="6" text="Tidak ada rekapan yang sesuai dengan filter." />
                        @endforelse
                    </tbody>
                </x-table>

                <div class="app-table-footer">
                    <span>
                        Menampilkan
                        {{ $closingAttendances->firstItem() ?? 0 }}–{{ $closingAttendances->lastItem() ?? 0 }}
                        dari
                        {{ $closingAttendances->total() }}
                        entri
                    </span>

                    <x-pagination :paginator="$closingAttendances" />
                </div>
            </div>
        @endif
    </x-panel>

    {{-- OFFCANVAS CLOSING PERIODE (dummy: belum ada proses backend, lihat closing-attendance.js) --}}
    <x-offcanvas.form id="offcanvas-closing" title="Closing Periode"
        description="Tutup periode absensi agar hasilnya dapat dilihat sebagai rekapan." size="md">
        <form id="offcanvas-closing-form" action="{{ route('closing-attendance.store') }}" method="POST" novalidate>
            @csrf

            {{-- TODO backend: ganti action dengan route closing, lalu hapus data-closing-form
                 (atribut itu hanya untuk simulasi di frontend) --}}
            <x-form.select name="bulan_closing" id="closing_bulan" label="Bulan" :options="$namaBulan" nullable required />

            <x-form.select name="tahun_closing" id="closing_tahun" label="Tahun" :options="$tahunOptions" nullable required />

            <p class="text-muted small mb-0">
                Setelah ditutup, data absensi periode tersebut tidak dapat diubah lagi.
                Closing periode akan diproses oleh backend.
            </p>
        </form>
    </x-offcanvas.form>
@endsection
