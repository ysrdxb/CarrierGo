<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">

<head>

    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- TITLE -->
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('admin/images/brand/favicon.ico') }}" />

    <!-- BOOTSTRAP CSS -->
    <link id="style" href="{{ asset('admin/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />

    <!-- STYLE CSS -->
    <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/css/dark-style.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/css/transparent-style.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/skin-modes.css') }}" rel="stylesheet" />

    <!-- SINGLE-PAGE CSS -->
    <link href="{{ asset('admin/plugins/single-page/css/main.css') }}" rel="stylesheet" type="text/css">    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" type="text/css">

    @stack('head')
</head>

<body class="app sidebar-mini ltr">
    <!-- GLOABAL LOADER -->
    <div id="global-loader">
        <img src="{{ asset('admin/images/loader.svg') }}" class="loader-img" alt="Loader">
    </div>
    <!-- /GLOABAL LOADER -->
    @yield('content')

    <script src="{{ asset('admin/js/jquery.min.js') }}"></script>

    <!-- BOOTSTRAP JS -->
    <script src="{{ asset('admin/plugins/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

    <!-- SHOW PASSWORD JS -->
    <script src="{{ asset('admin/js/show-password.min.js') }}"></script>

    <!-- GENERATE OTP JS -->
    <script src="{{ asset('admin/js/generate-otp.js') }}"></script>

    <!-- CUSTOM JS -->
    <script src="{{ asset('admin/js/custom.js') }}"></script>

    <script src="{{ asset('admin/js/jquery.min.js') }}"></script>    

    @stack('script')
</body>

</html>
