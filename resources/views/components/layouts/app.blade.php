<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
<head>
    @include('layouts.inc.head')
    @livewireStyles        
</head>
<body class="app ltr horizontal light-mode">
    <!-- GLOBAL-LOADER -->
    <div id="global-loader">
        <img src="{{ asset('admin/images/loader.svg') }}" class="loader-img" alt="Loader">
    </div>
    <div class="page">
        <div class="page-main">
            <!-- /GLOBAL-LOADER -->    
            @include('layouts.header') 

            @isset($slot)

                {{ $slot }}
                
            @endisset 
                
            @yield('content')

        </div>
        @include('layouts.inc.sidebar')        
        <!-- Country-selector modal-->
        <div class="modal fade" id="country-selector">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content country-select-modal">
                    <div class="modal-header">
                        <h6 class="modal-title">Choose Country</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row p-3">
                            <div class="col-lg-6">
                                <input type="radio" class="btn-check" name="btnradio" id="btnradio1"  checked="">
                                <label class="btn btn-country btn-lg btn-block" for="btnradio1">
                                <span class="country-selector"><img alt="" src="{{ asset('admin/images/flags/us_flag.jpg') }}" class="me-3 language"></span>English
                            </label>
                            </div>
                            <div class="col-lg-6">
                                <input type="radio" class="btn-check" name="btnradio" id="btnradio1"  checked="">
                                <label class="btn btn-country btn-lg btn-block" for="btnradio1">
                                <span class="country-selector"><img alt="" src="../assets/images/flags/germany_flag.jpg" class="me-3 language"></span>Deutsch
                            </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Country-selector modal-->
        @include('layouts.footer')            
    </div>
    @include('layouts.inc.script')
</body>
</html>
