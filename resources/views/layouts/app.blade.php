<!DOCTYPE html>
<html lang="id">

    @include('layouts.head')

<body>

    <x-sidebar></x-sidebar>

    {{-- Wrapper konten, margin kiri selebar sidebar (liat app.css) --}}
    <div class="app-content-wrapper">

        <main class="app-main">
            {{-- Ini yg diisi sama masing-masing halaman (dashboard, users, dll) --}}
            @yield('content')
        </main>
    </div>

</body>
</html>
