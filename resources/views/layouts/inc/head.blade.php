
    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ session()->has('logo') ? asset('storage/' . session('favicon')) : asset('admin/images/brand/favicon.ico') }}" />

    <!-- TITLE -->
    <title>{{ env('APP_NAME') }}</title>

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ session()->has('logo') ? asset('storage/' . session('favicon')) : asset('admin/images/brand/favicon.ico') }}" />

    <!-- BOOTSTRAP CSS -->
    <link id="style" href="{{ asset('admin/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />

    <!-- STYLE CSS -->
    <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/css/dark-style.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/css/transparent-style.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/skin-modes.css') }}" rel="stylesheet" />

    <!-- SINGLE-PAGE CSS -->
    <link href="{{ asset('admin/plugins/single-page/css/main.css') }}" rel="stylesheet" type="text/css">

    <!-- P-scroll bar css-->
    <link href="{{ asset('admin/plugins/p-scroll/perfect-scrollbar.css') }}" rel="stylesheet">

    <!--- FONT-ICONS CSS -->
    <link href="{{ asset('admin/css/icons.css') }}" rel="stylesheet">

    <!-- COLOR SKIN CSS -->
    <link id="theme" rel="stylesheet" type="text/css" media="all" href="{{ asset('admin/colors/color1.css') }}">

    <link href="{{ asset('admin/plugins/charts-c3/c3-chart.css') }}" rel="stylesheet" />

    <!-- INTERNAL Jvectormap css -->
    <link href="{{ asset('admin/plugins/jvectormap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet" />

    <!-- SIDEBAR CSS -->
    <link href="{{ asset('admin/plugins/sidebar/sidebar.css') }}" rel="stylesheet">

    <!-- SELECT2 CSS -->
    <link href="{{ asset('admin/plugins/select2/select2.min.css') }}" rel="stylesheet" />

    <!-- INTERNAL Data table css -->
    <link href="{{ asset('admin/plugins/datatable/responsive.bootstrap5.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="{{ asset('joydeep-bhowmik/livewire-datatable/css/data-table.css') }}">

    <style>
        .toast-close{
            position: absolute;
            right:4px;
            top:4px
        }
    </style>
    @stack('head')
    @livewireStyles
