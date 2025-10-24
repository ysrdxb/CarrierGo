<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
<head>
    @include('layouts.inc.head')
    @livewireStyles   
</head>
<body>

    <div class="page">
        <div class="page-main">
        
            @yield('content')

        </div>
    </div>

    @include('layouts.inc.script')
</body>
</html>
