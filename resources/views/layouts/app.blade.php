<!DOCTYPE html>
<html lang="id">

@include('layouts.head')

<body>

    <x-sidebar />

    <div class="app-content-wrapper">

        <x-topbar {{-- untuk smntara --}}
            :user-name="auth()->user()->name ?? 'Super Admin'"
            :user-email="auth()->user()->email ?? 'superadmin@gmail.com'"
            :user-role="auth()->user()->role_label ?? 'Super Admin'"
            :user-initials="auth()->user() ? Str::of(auth()->user()->name)->explode(' ')->map(fn ($w) => $w[0])->take(2)->implode('') : 'SA'"
        />

        <main class="app-main">
            @yield('content')
        </main>

    </div>

</body>
</html>