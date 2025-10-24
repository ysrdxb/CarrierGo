<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
<head>
    <link id="style" href="{{ asset('admin/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/plugins/datatable/responsive.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/css/invoice.css') }}" rel="stylesheet"/>
    <script src="{{ asset('admin/js/jquery.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    @stack('head')
</head>
<body>


    @yield('body')


</body>
</html>
