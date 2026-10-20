@php
    $user = Auth::user();

    $userName = $user?->nama ?? 'User';
    $userEmail = $user?->email ?? '-';
    $userRole = $user?->role?->nama ?? '-';
    $userNoTlp = $user?->no_tlp ?? '-';

    $userInitials = collect(explode(' ', $userName))
        ->filter()
        ->map(fn ($word) => strtoupper(substr($word, 0, 1)))
        ->take(2)
        ->implode('');

    $profileRows = [
        'Nama' => $userName,
        'Email' => $userEmail,
        'Role' => $userRole,
        'No. Tlp' => $userNoTlp,
    ];
@endphp

<header class="topbar">
    <div class="topbar-actions">

        <div class="dropdown topbar-user">

            <button
                type="button"
                class="topbar-user-trigger"
                data-bs-toggle="dropdown"
                aria-expanded="false"
            >
                <span class="topbar-avatar">
                    {{ $userInitials }}
                </span>

                <span class="topbar-user-info">
                    <span class="topbar-user-name">
                        {{ $userName }}
                    </span>

                    <span class="topbar-user-role">
                        {{ $userRole }}
                    </span>
                </span>

                <i class="bi bi-chevron-down topbar-chevron"></i>
            </button>

            <div class="dropdown-menu dropdown-menu-end topbar-profile-card">

                {{-- HEADER PROFIL --}}
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

                {{-- INFORMASI PROFIL --}}
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

                {{-- TOMBOL LOGOUT --}}
                <a
                    href="{{ route('logout') }}"
                    class="topbar-profile-logout-btn"
                    data-confirm-logout
                >
                    <i class="bi bi-box-arrow-right"></i>
                    Logout
                </a>

                {{-- FORM LOGOUT --}}
                <form
                    id="topbar-logout-form"
                    action="{{ route('logout') }}"
                    method="POST"
                    class="d-none"
                >
                    @csrf
                </form>

            </div>
        </div>

    </div>
</header>