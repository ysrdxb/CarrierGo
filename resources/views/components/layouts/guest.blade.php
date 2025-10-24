<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
<head>
    @include('layouts.inc.head')
    @livewireStyles
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Navigation -->
    @include('layouts.guest-navbar')

    <!-- Main Content -->
    <main class="flex-grow-1">
        @isset($slot)
            {{ $slot }}
        @endisset

        @yield('content')
    </main>

    <!-- Footer -->
    @include('layouts.guest-footer')

    @include('layouts.inc.script')
</body>
</html>
