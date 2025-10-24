@extends('layouts.livewire-guest')
@section('content')
<div class="main-content mt-0 hor-content">
    <div class="side-app ">
        <!-- CONTAINER -->
        <div class="main-container container">
            <!-- PAGE-HEADER -->
            <div class="page-header">
                <h1 class="page-title">Booking Details {{ $order->reference->reference_no }}</h1>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div>
                                        <p class="mb-1"> {{ $order->reference->agent->company_name }} </p>
                                        <p class="mb-1"> {{ $order->reference->agent->street . ' ' . $order->reference->agent->street_no . ' ' . $order->reference->agent->zip_code }}</p>
                                        <p class="mb-1"> {{ $order->reference->agent->city }}</p>
                                        <p class="mb-1"> {{ $order->reference->agent->country }}</p>
                                        <p class="mb-1"> {{ $order->reference->agent->email }}</p><br>
                                    </div>
                                </div>
                                <div class="col-lg-6 text-end ">
                                    <a class="header-brand" href="index.html">
                                        <img style="max-width: 120px" src="{{ asset('admin/images/brand/logo-main.png') }}" class="header-brand-img logo-3" alt="logo">
                                    </a>
                                </div>
                            </div>
                            <div class="row pt-6">
                                <div class="col-lg-6">
                                    <p class="mb-1"> {{ $order->reference->agent->firstname .' '.$order->reference->agent->lastname}} ( Agent)</p>
                                    <p class="mb-1"> {{ $order->reference->agent->street }}</p>
                                    <p class="mb-1"> {{ $order->reference->agent->zip_code . ' ' . $order->reference->agent->city }}</p>
                                </div>
                                <div class="col-lg-6 text-end">
                                    <p class="h4 fw-semibold"></p>
                                </div>
                            </div>
                            <div class="row pt-6">
                                <div class="col-lg-8">
                                    <p class="h4 fw-semibold"><br><br> SHIPPING - INSTRUCTIONS </p>
                                </div>
                                <div class="col-lg-4 text-end">
                                    <p class="mb-1"><br><br>{{ $order->created_at->format('d-m-Y') }}</p>
                                </div>
                            </div>
                            <div class="row pt-6">
                                @php 
                                    $company = \App\Models\Setting::first();
                                @endphp
                                <div class="col-lg-12">
                                    <p class="h4 fw-semibold"> Shipper: </p>
                                    <p class="mb-1"> {{ $company->company_name }} <br>{{ $company->address }}<br>{{ $company->zip_code }} {{ $company->city }}<br>REF: {{ $order->reference->reference_no }} as agents only</p><br>
                                    <p class="h4 fw-semibold"> Consignee: </p>
                                    <p class="mb-1"> {{ $order->reference->consignee->firstname . ' ' . $order->reference->lastname }} </p>
                                    <p class="mb-1"> {{ $order->reference->consignee->street . ' ' . $order->reference->consignee->street_no . ' ' . $order->reference->consignee->zip_code }}</p>
                                    <p class="mb-1"> {{ $order->reference->consignee->city }}</p>
                                    <p class="mb-1"> {{ $order->reference->consignee->country }}</p>
                                    <p class="mb-1"> {{ $order->reference->consignee->email }}</p><br>
                                </div>
                            </div>
                            <div class="table-responsive push">
                                <table class="table table-bordered table-hover mb-0 text-nowrap">
                                    <tbody>
                                        @php $i=1; @endphp 
                                        @foreach($order->reference->freights as $freight)
                                        <tr>
                                            <td class="text-center">{{ $i++ }}</td>
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
                                <div class="row pt-6">
                                    <div class="col-lg-12">
                                        <p class="h4 fw-semibold"> Destination: </p>
                                        <p class="mb-1"> {{ $order->reference->freights->first()->destination->name }}</p><br> 
                                        <p class="h4 fw-semibold"> Bill of Lading: {{ $order->bill_of_lading }}</p>
                                        <p class="h4 fw-semibold"> Rate Price in €: {{ $order->rate_price }}€</p>
                                        <p class="mb-1"> choose from click on generate booking</p><br> 
                                    </div>
                                </div>
                                <div class="row pt-6">
                                    <div class="col-lg-12">
                                        <h6 class=" text-center"> <br><br>Wir  arbeiten  auf  Grund  der  Allgem.  Deutschen  Spediteursbedingungen  (ADSp)  neueste  Fassung
                                            <br>Handelsregister:  HRB 67668  –  Gerichtsstand:  München  –  Sitz  der  Gesellschaft:  München <br>Geschäftsführer:  Wolfgang - Hess  
                                            UST ID-Nr. DE 129 496 426
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="button" class="btn btn-primary mb-1" onclick="javascript:window.print();"><i class="si si-wallet"></i> Save Driver authorization</button>
                        </div>
                    </div>
                </div>
                <!-- COL-END -->
            </div>   
        </div>
    </div>
</div>
@endsection
