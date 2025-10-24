@extends('layouts.guest')
<style>
    @media print {
        .row .col-lg-8,
        .row .col-lg-6,
        .row .col-lg-4 {
            width: 50%;
            float: left;
        }

        .row .col-lg-4 {
            float: right;
        }
    }
</style>
@section('content')
    <div class="main-content mt-0 hor-content">
        <div class="side-app">

            <!-- CONTAINER -->
            <div class="main-container container">


                <!-- ROW-1 OPEN -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div>
                                            <address class="pt-3">
                                                NMH Cargo &amp; Shipping L.L.C<br>
                                                ان ام اتش الشحن و المكحة ش ذ.م.م
                                                <br>P.O. Box 415073 - Al Saqr Business Tower<br>
                                                Sheikh Zayed Road - Dubai - UAE<br>
                                                email: nmhshipping@gmail.com<br>
                                                phone: + 971 50 461 0645 / +971 56 245 2386<br>
                                            </address>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 text-end">
                                        <a class="header-brand" href="{{ route('dashboard.index') }}">
                                            <img style="max-width: 120px" src="{{ asset('admin/images/brand/logo-main.png') }}" class="header-brand-img logo-3" alt="Sash logo">
                                        </a>
                                    </div>
                                </div>

                                <div class="row pt-6">
                                    <div class="col-lg-6 mb-4">
                                        <h5>To</h5>
                                        <p class="mb-1"> {{ $transportOrder->reference->carrier->company_name }} </p>
                                        <p class="mb-1"> {{ $transportOrder->reference->carrier->street_no }} {{ $transportOrder->reference->carrier->street }}</p>
                                        <p class="mb-1"> {{ $transportOrder->reference->carrier->zip_code }} {{ $transportOrder->reference->carrier->city }} </p>
                                    </div>  
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div>
                                                <h5>Loading Address</h5>
                                                @if(!empty($transportOrder->loading_company_name))
                                                <address class="pt-0">
                                                    {{ $transportOrder->loading_company_name }}
                                                    <br>{{ $transportOrder->loading_street . ' <br>' .$transportOrder->loading_zip_city }}<br>
                                                    {{ $transportOrder->loading_contact_name . ' ' . $transportOrder->loading_contact_phone }}<br>
                                                </address>
                                                @else
                                                <address class="pt-0">
                                                    {{ optional($transportOrder->merchant)->company_name }}
                                                    <br>{{ optional($transportOrder->merchant)->street }} <br> 
                                                    {{ optional($transportOrder->merchant)->zip_code . ' ' . $transportOrder->merchant->city }}<br>
                                                    {{ optional($transportOrder->merchant)->firstname . ' ' . optional($transportOrder->merchant)->lastname . ' ' . optional($transportOrder->merchant)->phone }}<br>
                                                </address>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6" style="text-align: right">
                                                <h5>Un Loading Address</h5>
                                                <address class="pt-0">
                                                    {{ $transportOrder->unloadingAddress->company_name }}
                                                    <br>{{ $transportOrder->unloadingAddress->street }} <br>
                                                    {{ $transportOrder->unloadingAddress->zip_city }}<br>
                                                    {{ $transportOrder->unloadingAddress->contact_name . ' ' . $transportOrder->unloadingAddress->contact_phone }}<br>
                                                </address>
                                        </div>                                        
                                    </div>                                
                                </div>
								
                        
                                <div class="row pt-6">
                                    <div class="col-lg-8">
                                        <p class="h4 fw-semibold"><br><br> Power of attorney to pick up a vehicle </p>
                                    </div>
                                    <div class="col-lg-4 text-end">
                                        <p class="mb-1"><br><br>{{ \Carbon\Carbon::parse($transportOrder->add_date)->format('d-m-Y') }}</p>
                                    </div>
                                </div>
                        
                                <div class="table-responsive push">
                                    <table class="table table-bordered table-hover mb-0 text-nowrap">
                                        <tbody>
                        
                                            @php $counter = 1; @endphp
                                            @foreach($transportOrder->reference->freights as $freight)
                                            <tr>
                                                <td>{{ $counter++ }}</td>
                                                <td>
                                                    <p class="font-w600 mb-1">{{ $freight->vehicle_model }}</p>
                                                    <div class="text-muted">
                                                        <div class="text-muted">{{ $freight->vehicle_fin }}</div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                        
                        
                                        </tbody>
                                    </table><br>
                        
                                    @php
                                        $company = \App\Models\Setting::first();
                                    @endphp            
                                <div>Best regards, <br><br>{{ $company->company_name }} <br>{{ $company->address }}</div>
                        
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button type="button" class="btn btn-primary mb-1 d-print-none" onclick="javascript:window.print();"><i class="si si-wallet"></i> Save Transport Order</button>
                            </div>
                        </div>
                                        
                        
                    </div>
                   
                </div>
                <!-- ROW-1 CLOSED -->

            </div>
            <!-- CONTAINER CLOSED -->
        </div>
    </div>

@endsection
