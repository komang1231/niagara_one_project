@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid px-0 dashboard-page">

    {{-- HEADER ATTENDANCE --}}
    <x-page-header
        eyebrow="Attendance"
        title="Attendance"
        description="Pantau kehadiran dan aktivitas absensi karyawan."
        icon="bi-clock-fill"
    >
        <x-slot:badges>
            @include('attendance.partials.badges')
        </x-slot:badges>

        <x-slot:actions>
            @include('attendance.partials.actions')
        </x-slot:actions>
    </x-page-header>

     {{-- STATUS KEHADIRAN HARI INI --}}
    <div class="mb-4">
        @include('attendance.partials.today')
    </div>


    {{-- FILTER DASHBOARD --}}
    <div class="dashboard-filter-wrapper">
        <x-filter.bar>
            <x-filter.date-range
                name="periode"
                label="Periode"
                type="date"
                :default-from="now()->startOfMonth()->format('Y-m-d')"
                :default-to="now()->format('Y-m-d')"
            />

            <x-filter.multiselect
                name="cabang"
                label="Cabang"
                :options="$cabangOptions"
                placeholder="Semua Cabang"
            />

            <x-filter.multiselect
                name="departemen"
                label="Departemen"
                :options="$departemenOptions"
                placeholder="Semua Departemen"
            />
        </x-filter.bar>
    </div>

    {{-- KPI KARYAWAN --}}
    <div class="row g-3 dashboard-kpi-row mb-4">

        <div class="col-12 col-sm-6 col-xl-4 col-xxl-2">
            <div class="card dashboard-card kpi-card h-100">
                <div class="card-body">
                    <div class="kpi-icon icon-green">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <p class="kpi-label">Total Karyawan Aktif</p>
                    <h3 class="kpi-value">{{ $totalKaryawan ?? 0 }}</h3>
                    <small class="text-muted">Karyawan berstatus aktif</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-4 col-xxl-2">
            <div class="card dashboard-card kpi-card h-100">
                <div class="card-body">
                    <div class="kpi-icon icon-blue">
                        <i class="bi bi-person-plus-fill"></i>
                    </div>
                    <p class="kpi-label">Karyawan Baru</p>
                    <h3 class="kpi-value">{{ $karyawanBaru ?? 0 }}</h3>
                    <small class="text-muted">Pada periode terpilih</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-4 col-xxl-2">
            <div class="card dashboard-card kpi-card h-100">
                <div class="card-body">
                    <div class="kpi-icon icon-red">
                        <i class="bi bi-person-dash-fill"></i>
                    </div>
                    <p class="kpi-label">Karyawan Keluar</p>
                    <h3 class="kpi-value">{{ $karyawanKeluar ?? 0 }}</h3>
                    <small class="text-muted">Pada periode terpilih</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-4 col-xxl-2">
            <div class="card dashboard-card kpi-card h-100">
                <div class="card-body">
                    <div class="kpi-icon icon-orange">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <p class="kpi-label">Turnover Rate</p>
                    <h3 class="kpi-value">{{ $turnoverRate ?? 0 }}%</h3>
                    <small class="text-muted">Persentase pergantian karyawan</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-4 col-xxl-2">
            <div class="card dashboard-card kpi-card h-100">
                <div class="card-body">
                    <div class="kpi-icon icon-purple">
                        <i class="bi bi-briefcase-fill"></i>
                    </div>
                    <p class="kpi-label">Lowongan Aktif</p>
                    <h3 class="kpi-value">{{ $lowonganAktif ?? 0 }}</h3>
                    <small class="text-muted">Jumlah data lowongan</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-4 col-xxl-2">
            <div class="card dashboard-card kpi-card h-100">
                <div class="card-body">
                    <div class="kpi-icon icon-teal">
                        <i class="bi bi-person-lines-fill"></i>
                    </div>
                    <p class="kpi-label">Pelamar Baru</p>
                    <h3 class="kpi-value">{{ $pelamarBaru ?? 0 }}</h3>
                    <small class="text-muted">Pada periode terpilih</small>
                </div>
            </div>
        </div>

    </div>

    {{-- GRAFIK DAN KOMPOSISI KEPEGAWAIAN --}}
    <div class="row g-3 dashboard-chart-row mb-4">

        {{-- KARYAWAN PER DEPARTEMEN --}}
        <div class="col-12 col-xl-7">
            <div class="card dashboard-card h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-1">Karyawan per Departemen</h5>
                    <p class="text-muted small mb-4">
                        Jumlah karyawan aktif pada setiap departemen.
                    </p>

                    @php
                        $jumlahMaksimal = $karyawanPerDepartemen->max('jumlah_karyawan') ?? 0;
                    @endphp

                    @forelse ($karyawanPerDepartemen as $item)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="small fw-semibold">
                                    {{ $item->nama }}
                                </span>
                                <span class="small text-muted">
                                    {{ $item->jumlah_karyawan }} karyawan
                                </span>
                            </div>

                            <div class="progress" style="height:10px; border-radius:10px;">
                                <div
                                    class="progress-bar"
                                    role="progressbar"
                                    style="width: {{ $jumlahMaksimal > 0 ? ($item->jumlah_karyawan / $jumlahMaksimal) * 100 : 0 }}%; background:#168568; border-radius:10px;"
                                ></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center py-4">
                            Belum ada data departemen.
                        </p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- KOMPOSISI KEPEGAWAIAN --}}
        <div class="col-12 col-xl-5">
            <div class="card dashboard-card h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-1">Komposisi Kepegawaian</h5>
                    <p class="text-muted small mb-4">
                        Karyawan aktif berdasarkan status kepegawaian.
                    </p>

                    @forelse ($komposisiKepegawaian as $item)
                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                            <span>{{ $item->nama }}</span>
                            <span class="badge rounded-pill text-bg-success">
                                {{ $item->jumlah_karyawan }} karyawan
                            </span>
                        </div>
                    @empty
                        <p class="text-muted text-center py-4">
                            Belum ada data status kepegawaian.
                        </p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

</div>

{{-- CSS DASHBOARD --}}
<style>
    .dashboard-page {
        width: 100%;
        color: #263238;
    }

    .dashboard-section-divider {
        padding-top: 24px;
        border-top: 1px solid #e9edf1;
    }

    .dashboard-filter-wrapper {
        margin-bottom: 24px;
    }

    .dashboard-page .app-filter-bar {
        margin-bottom: 0;
    }

    .dashboard-kpi-row,
    .dashboard-chart-row {
        --bs-gutter-x: 16px;
        --bs-gutter-y: 16px;
    }

    .dashboard-card {
        background: #fff;
        border: 1px solid #e9edf1;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(25, 45, 65, .035);
    }

    .kpi-card {
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 18px rgba(25, 45, 65, .08);
    }

    .kpi-card .card-body {
        padding: 20px 16px;
    }

    .kpi-icon {
        width: 42px;
        height: 42px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 10px;
        font-size: 20px;
        margin-bottom: 18px;
    }

    .icon-green {
        color: #168568;
        background: #e5f5ef;
    }

    .icon-blue {
        color: #2878c8;
        background: #e8f2ff;
    }

    .icon-red {
        color: #d94b55;
        background: #ffebed;
    }

    .icon-orange {
        color: #c77b19;
        background: #fff3df;
    }

    .icon-purple {
        color: #8155c7;
        background: #f1eaff;
    }

    .icon-teal {
        color: #128b99;
        background: #e3f7f8;
    }

    .kpi-label {
        color: #687383;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .kpi-value {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .kpi-card .text-muted {
        font-size: 11px;
    }

    @media (max-width: 576px) {
        .dashboard-filter-wrapper {
            margin-bottom: 20px;
        }

        .kpi-card .card-body {
            padding: 16px;
        }

        .kpi-value {
            font-size: 24px;
        }
    }
</style>
@endsection

