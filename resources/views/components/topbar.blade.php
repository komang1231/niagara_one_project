@props([
    'userName' => 'Super Admin',
    'userEmail' => 'superadmin@gmail.com',
    'userRole' => 'Super Admin',
    'userInitials' => 'SA',
    'rows' => null,
])

@php

    $profileRows = $rows ?? [
        'Nama' => $userName,
        'Email' => $userEmail,
        'Role' => $userRole,
    ];
@endphp

<header class="topbar">
    <div class="topbar-actions">

        <div class="dropdown topbar-user">

            <button type="button"
                    class="topbar-user-trigger"
                    data-bs-toggle="dropdown"
                    aria-expanded="false">

                <span class="topbar-avatar">{{ $userInitials }}</span>

                <span class="topbar-user-info">
                    <span class="topbar-user-name">{{ $userName }}</span>
                    <span class="topbar-user-role">{{ $userRole }}</span>
                </span>

                <i class="bi bi-chevron-down topbar-chevron"></i>
            </button>


            <div class="dropdown-menu dropdown-menu-end topbar-profile-card">

                <div class="topbar-profile-header">
                    <span class="topbar-profile-avatar">{{ $userInitials }}</span>
                    <div>
                        <p class="topbar-profile-name">{{ $userName }}</p>
                        <p class="topbar-profile-email">{{ $userEmail }}</p>
                    </div>
                </div>

                <hr class="topbar-profile-divider">

                <div class="topbar-profile-rows">
                    @foreach ($profileRows as $label => $value)
                        <div class="topbar-profile-row">
                            <span class="topbar-profile-row-label">{{ $label }}</span>
                            <span class="topbar-profile-row-value">{{ $value }}</span>
                        </div>
                    @endforeach
                </div>

                <a href="#"
                   class="topbar-profile-logout-btn"
                   onclick="event.preventDefault(); document.getElementById('topbar-logout-form').submit();">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
                <form id="topbar-logout-form" action="#" method="POST" class="d-none">
                    @csrf
                </form>

            </div>
        </div>

    </div>
</header>