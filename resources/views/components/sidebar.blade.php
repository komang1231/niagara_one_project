@php
    $menu = [

        [
            'label' => 'Dashboard',
            'icon' => 'bi-grid-fill',
            'slug' => 'dashboard',
            'children' => [],
        ],

        [
            'label' => 'Karyawan',
            'icon' => 'bi-people-fill',
            'slug' => 'karyawan',
            'children' => [
                ['label' => 'Data Karyawan', 'slug' => 'data-karyawan'],
                ['label' => 'Kontrak Karyawan', 'slug' => 'kontrak-karyawan'],
                ['label' => 'Perubahan Karyawan', 'slug' => 'perubahan-karyawan'],
                ['label' => 'Permintaan Resign', 'slug' => 'permintaan-resign'],
                ['label' => 'Surat Peringatan', 'slug' => 'surat-peringatan'],
            ],
        ],

        [
            'label' => 'Kehadiran',
            'icon' => 'bi-clock-fill',
            'slug' => 'kehadiran',
            'children' => [
                ['label' => 'Jadwal Karyawan', 'slug' => 'jadwal-karyawan'],
                ['label' => 'Shift', 'slug' => 'shift'],
                ['label' => 'Pola Shift', 'slug' => 'pola-shift'],
                ['label' => 'Permintaan Tukar Shift', 'slug' => 'tukar-shift'],
                ['label' => 'Permintaan Lembur', 'slug' => 'lembur'],
            ],
        ],

        [
            'label' => 'Cuti',
            'icon' => 'bi-calendar-week-fill',
            'slug' => 'cuti',
            'children' => [
                ['label' => 'Saldo Cuti', 'slug' => 'saldo-cuti'],
                ['label' => 'Permintaan Cuti', 'slug' => 'permintaan-cuti'],
            ],
        ],

        [
            'label' => 'Rekrutmen',
            'icon' => 'bi-person-fill-add',
            'slug' => 'rekrutmen-menu',
            'children' => [
                ['label' => 'Rekrutmen', 'slug' => 'rekrutmen'],
                ['label' => 'Lowongan', 'slug' => 'lowongan'],
                ['label' => 'Permintaan Karyawan', 'slug' => 'permintaan-karyawan'],
            ],
        ],

        [
            'label' => 'Struktur Organisasi',
            'icon' => 'bi-diagram-3-fill',
            'slug' => 'struktur-organisasi',
            'children' => [
                ['label' => 'Departemen', 'slug' => 'departemen'],
                ['label' => 'Divisi', 'slug' => 'divisi'],
                ['label' => 'Section', 'slug' => 'section'],
                ['label' => 'Job Level', 'slug' => 'job-level'],
                ['label' => 'Job Position', 'slug' => 'job-position'],
                ['label' => 'Cabang Kantor', 'slug' => 'cabang-kantor'],
            ],
        ],

        [
            'label' => 'Master Data',
            'icon' => 'bi-database-fill-gear',
            'slug' => 'master-data',
            'children' => [
                ['label' => 'Sumber Pelamar', 'slug' => 'sumber-pelamar'],
                ['label' => 'Status Kepegawaian', 'slug' => 'status-kepegawaian'],
                ['label' => 'Status Kawin', 'slug' => 'status-kawin'],
                ['label' => 'Jenjang Pendidikan', 'slug' => 'jenjang-pendidikan'],
                ['label' => 'Agama', 'slug' => 'agama'],
                ['label' => 'Bank', 'slug' => 'bank'],
            ],
        ],

        [
            'label' => 'Akses & Pengguna',
            'icon' => 'bi-shield-lock-fill',
            'slug' => 'akses-pengguna',
            'children' => [
                ['label' => 'User', 'slug' => 'user'],
                ['label' => 'Role', 'slug' => 'role'],
            ],
        ],

    ];
@endphp

<aside class="sidebar">

    {{-- ===== HEADER ===== --}}
    <header class="sidebar-header">
        <h1 class="sidebar-title">Niagara One System</h1>
        <p class="sidebar-subtitle text-muted">Employee Service</p>
    </header>

    {{-- ===== NAVIGASI ===== --}}
    <nav class="sidebar-nav">
        <ul class="nav-list">

            @foreach ($menu as $item)

                @if (count($item['children']) === 0)

                    @php
                        $routeName = $item['slug'] . '.index';
                        $isActive = Route::is($routeName);
                    @endphp

                    <li class="nav-item">
                        <a href="{{ route($routeName) }}"
                           class="nav-link @if($isActive) nav-link-active @endif">
                            <i class="bi {{ $item['icon'] }} nav-icon"></i>
                            <span class="nav-text">{{ $item['label'] }}</span>
                        </a>
                    </li>

                @else

                    @php
                        $collapseId = $item['slug'] . 'Submenu';
                        $isSectionActive = false;

                        foreach ($item['children'] as $child) {
                            $childRouteName = $child['slug'] . '.index';
                            if (Route::is($childRouteName)) {
                                $isSectionActive = true;
                            }
                        }
                    @endphp

                    <li class="nav-item">

                        <a href="#{{ $collapseId }}"
                           class="nav-link d-flex justify-content-between align-items-center
                                  @if($isSectionActive) nav-link-active @else collapsed @endif"
                           data-bs-toggle="collapse"
                           role="button"
                           aria-expanded="{{ $isSectionActive ? 'true' : 'false' }}"
                           aria-controls="{{ $collapseId }}">

                            <span class="d-flex align-items-center">
                                <i class="bi {{ $item['icon'] }} nav-icon"></i>
                                <span class="nav-text">{{ $item['label'] }}</span>
                            </span>
                            <i class="bi bi-chevron-up chevron"></i>
                        </a>

                        <ul id="{{ $collapseId }}"
                            class="submenu-tree collapse @if($isSectionActive) show @endif">

                            {{-- Garis trunk tunggal, panjangnya otomatis berhenti
                                 tepat sebelum lengkungan item terakhir --}}
                            <div class="submenu-trunk"></div>

                            @foreach ($item['children'] as $child)
                                @php
                                    $childRouteName = $child['slug'] . '.index';
                                    $isChildActive = Route::is($childRouteName);
                                @endphp

                                <li class="submenu-item @if($isChildActive) active @endif">
                                    <a href="{{ route($childRouteName) }}">
                                        <span class="active-indicator"></span>
                                        <span class="submenu-label">{{ $child['label'] }}</span>
                                    </a>
                                </li>
                            @endforeach

                        </ul>
                    </li>

                @endif

            @endforeach

        </ul>
    </nav>

    {{-- ===== FOOTER ===== --}}
    <footer class="sidebar-footer">
        <a href="#" class="see-all-link">See All Service</a>
    </footer>

</aside>