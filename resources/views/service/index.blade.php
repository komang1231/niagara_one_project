<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Home Service - Naga One</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f8f9fa;
            font-family: Arial, sans-serif;
            color: #1f2937;
        }

        /* =====================================================
           TOPBAR
        ===================================================== */

        .topbar {
            height: 70px;
            background: #fff;
            border-bottom: 1px solid #eeeeee;

            display: flex;
            align-items: center;
            justify-content: flex-end;

            padding: 0 40px;

            position: relative;
            z-index: 1000;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
        }

        .topbar-user {
            position: relative;
        }

        .topbar-user-trigger {
            border: 0;
            background: transparent;

            display: flex;
            align-items: center;

            gap: 10px;

            padding: 4px 0;

            cursor: pointer;
        }

        /* AVATAR */

        .topbar-avatar {
            width: 40px;
            height: 40px;

            border-radius: 50%;

            background: #dff5ee;
            color: #159b78;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 14px;
            font-weight: 700;

            flex-shrink: 0;
        }

        /* USER INFO */

        .topbar-user-info {
            display: flex;
            flex-direction: column;

            text-align: left;

            line-height: 1.2;
        }

        .topbar-user-name {
            font-size: 14px;
            font-weight: 700;

            color: #202020;
        }

        .topbar-user-role {
            font-size: 12px;

            color: #8993a4;

            margin-top: 3px;
        }

        /* CHEVRON */

        .topbar-chevron {
            font-size: 12px;
            color: #9aa3af;

            margin-left: 3px;

            transition: transform 0.2s ease;
        }

        .topbar-user-trigger[aria-expanded="true"] .topbar-chevron {
            transform: rotate(180deg);
        }

        /* =====================================================
           PROFILE DROPDOWN
        ===================================================== */

        .topbar-profile-card {
            width: 290px;

            padding: 18px !important;

            margin-top: 10px !important;

            border: 1px solid #eeeeee !important;
            border-radius: 16px !important;

            background: #ffffff;

            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .topbar-profile-header {
            display: flex;
            align-items: center;

            gap: 12px;

            padding-bottom: 15px;
        }

        .topbar-profile-avatar {
            width: 45px;
            height: 45px;

            border-radius: 50%;

            background: #dff5ee;
            color: #159b78;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 14px;
            font-weight: 700;

            flex-shrink: 0;
        }

        .topbar-profile-name {
            margin: 0;

            font-size: 16px;
            font-weight: 700;

            color: #202020;
        }

        .topbar-profile-email {
            margin: 3px 0 0;

            font-size: 13px;

            color: #8993a4;
        }

        .topbar-profile-divider {
            margin: 0 0 10px;

            border: 0;
            border-top: 1px solid #eeeeee;

            opacity: 1;
        }

        .topbar-profile-rows {
            padding: 0;
        }

        .topbar-profile-row {
            display: flex;

            align-items: center;
            justify-content: space-between;

            padding: 8px 0;

            font-size: 13px;
        }

        .topbar-profile-row-label {
            color: #8993a4;
        }

        .topbar-profile-row-value {
            color: #4b5563;

            font-weight: 600;

            text-align: right;

            max-width: 170px;

            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* LOGOUT */

        .topbar-profile-logout-btn {
            width: 100%;

            height: 42px;

            margin-top: 10px;

            border-radius: 10px;

            background: #fff0f0;
            color: #ef5350;

            display: flex;
            align-items: center;
            justify-content: center;

            gap: 8px;

            text-decoration: none;

            font-size: 14px;
            font-weight: 600;

            transition: 0.2s ease;
        }

        .topbar-profile-logout-btn:hover {
            background: #ffe2e2;
            color: #ef5350;
        }

        /* =====================================================
           CONTENT
        ===================================================== */

        .content {
            padding: 45px;
        }

        .title {
            font-size: 30px;
            font-weight: 700;

            margin-bottom: 5px;
        }

        .subtitle {
            color: #777;

            margin-bottom: 30px;
        }

        /* =====================================================
           SERVICE CARD
        ===================================================== */

        .service-card {
            background: #fff;

            border: 1px solid #e8e8e8;
            border-radius: 16px;

            padding: 25px;

            height: 100%;

            text-decoration: none;

            color: #222;

            display: block;

            transition: 0.2s ease;
        }

        .service-card:hover {
            transform: translateY(-4px);

            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .service-icon {
            width: 55px;
            height: 55px;

            border-radius: 14px;

            background: #fff3cd;
            color: #f0ad00;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 25px;

            margin-bottom: 20px;
        }

        .service-name {
            font-size: 18px;
            font-weight: 600;

            margin-bottom: 5px;
        }

        .service-description {
            font-size: 14px;

            color: #777;
        }

        /* =====================================================
           COMING SOON
        ===================================================== */

        .coming-soon {
            opacity: 0.6;
            cursor: default;
        }

        .badge-coming {
            font-size: 11px;

            background: #eee;
            color: #777;

            padding: 5px 8px;

            border-radius: 20px;
        }
    </style>
</head>

<body>

    @php
        $user = Auth::user();

        $userName = $user?->karyawan?->nama ?? $user?->name ?? 'User';
        $userEmail = $user?->email ?? '-';
        $userRole = $user?->role?->nama ?? 'Super Admin';

        $userInitials = collect(explode(' ', $userName))
            ->filter()
            ->map(fn($word) => strtoupper(substr($word, 0, 1)))
            ->take(2)
            ->implode('');

        $profileRows = [
            'Nama' => $userName,
            'Email' => $userEmail,
            'Role' => $userRole,
        ];
    @endphp


    <!-- =====================================================
         TOPBAR
    ====================================================== -->

    <header class="topbar">

        <div class="topbar-actions">

            <div class="dropdown topbar-user">

                <button type="button"
                    class="topbar-user-trigger"
                    data-bs-toggle="dropdown"
                    aria-expanded="false">

                    <!-- AVATAR -->
                    <span class="topbar-avatar">
                        {{ $userInitials }}
                    </span>

                    <!-- USER INFO -->
                    <span class="topbar-user-info">

                        <span class="topbar-user-name">
                            {{ $userName }}
                        </span>

                        <span class="topbar-user-role">
                            {{ $userRole }}
                        </span>

                    </span>

                    <!-- CHEVRON -->
                    <i class="bi bi-chevron-down topbar-chevron"></i>

                </button>


                <!-- =================================================
                     PROFILE DROPDOWN
                ================================================== -->

                <div class="dropdown-menu dropdown-menu-end topbar-profile-card">

                    <div class="topbar-profile-header">

                        <span class="topbar-profile-avatar">
                            {{ $userInitials }}
                        </span>

                        <div>

                            <p class="topbar-profile-name">
                                {{ $userName }}
                            </p>

                            <p class="topbar-profile-email">
                                {{ $userEmail }}
                            </p>

                        </div>

                    </div>


                    <hr class="topbar-profile-divider">


                    <div class="topbar-profile-rows">

                        @foreach ($profileRows as $label => $value)

                            <div class="topbar-profile-row">

                                <span class="topbar-profile-row-label">
                                    {{ $label }}
                                </span>

                                <span class="topbar-profile-row-value">
                                    {{ $value }}
                                </span>

                            </div>

                        @endforeach

                    </div>


                    <!-- LOGOUT -->

                    <a href="#"
                        class="topbar-profile-logout-btn"
                        onclick="event.preventDefault(); document.getElementById('service-logout-form').submit();">

                        <i class="bi bi-box-arrow-right"></i>

                        Logout

                    </a>

                    <form id="service-logout-form"
                        action="{{ route('logout') }}"
                        method="POST"
                        class="d-none">

                        @csrf

                    </form>

                </div>

            </div>

        </div>

    </header>


    <!-- =====================================================
         CONTENT
    ===================================================== -->

    <div class="content">

        <div class="title">
            All Service
        </div>

        <div class="subtitle">
            All Service Niagara One
        </div>


        <div class="row g-4">


            <!-- =================================================
                 EMPLOYEE - AKTIF
            ================================================== -->

            <div class="col-md-6 col-lg-4 col-xl-3">

                <a href="{{ route('karyawan.index') }}"
                    class="service-card">

                    <div class="service-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>

                    <div class="service-name">
                        Employee
                    </div>

                </a>

            </div>


            <!-- =================================================
                 ATTENDANCE - AKTIF
            ================================================== -->

            <div class="col-md-6 col-lg-4 col-xl-3">

                <a href="{{ route('attendance.index') }}"
                    class="service-card">

                    <div class="service-icon">
                        <i class="bi bi-calendar-check-fill"></i>
                    </div>

                    <div class="service-name">
                        Attendance
                    </div>

                </a>

            </div>


            <!-- =================================================
                 PAYROLL - COMING SOON
            ================================================== -->

            <div class="col-md-6 col-lg-4 col-xl-3">

                <div class="service-card coming-soon">

                    <div class="service-icon">
                        <i class="bi bi-wallet2"></i>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">

                        <div class="service-name">
                            Payroll
                        </div>

                        <span class="badge-coming">
                            Coming Soon
                        </span>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 FINANCE - COMING SOON
            ================================================== -->

            <div class="col-md-6 col-lg-4 col-xl-3">

                <div class="service-card coming-soon">

                    <div class="service-icon">
                        <i class="bi bi-cash-stack"></i>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">

                        <div class="service-name">
                            Finance
                        </div>

                        <span class="badge-coming">
                            Coming Soon
                        </span>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 LEADS - COMING SOON
            ================================================== -->

            <div class="col-md-6 col-lg-4 col-xl-3">

                <div class="service-card coming-soon">

                    <div class="service-icon">
                        <i class="bi bi-funnel-fill"></i>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">

                        <div class="service-name">
                            Leads
                        </div>

                        <span class="badge-coming">
                            Coming Soon
                        </span>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 CLIENTS - COMING SOON
            ================================================== -->

            <div class="col-md-6 col-lg-4 col-xl-3">

                <div class="service-card coming-soon">

                    <div class="service-icon">
                        <i class="bi bi-person-vcard-fill"></i>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">

                        <div class="service-name">
                            Clients
                        </div>

                        <span class="badge-coming">
                            Coming Soon
                        </span>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 PROJECT - COMING SOON
            ================================================== -->

            <div class="col-md-6 col-lg-4 col-xl-3">

                <div class="service-card coming-soon">

                    <div class="service-icon">
                        <i class="bi bi-kanban-fill"></i>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">

                        <div class="service-name">
                            Project
                        </div>

                        <span class="badge-coming">
                            Coming Soon
                        </span>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 DOCUMENT - COMING SOON
            ================================================== -->

            <div class="col-md-6 col-lg-4 col-xl-3">

                <div class="service-card coming-soon">

                    <div class="service-icon">
                        <i class="bi bi-file-earmark-text-fill"></i>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">

                        <div class="service-name">
                            Document
                        </div>

                        <span class="badge-coming">
                            Coming Soon
                        </span>

                    </div>

                </div>

            </div>


        </div>

    </div>


    <!-- BOOTSTRAP JS -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>