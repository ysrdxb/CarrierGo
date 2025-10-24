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
                                            <p class="mb-1"> {{ $order->reference->agent->company_name }} (agent)</p>
                                            <p class="mb-1"> {{ $order->reference->agent->street . ' ' . $order->reference->agent->street_no }}</p>
                                            <p class="mb-1"> {{ $order->reference->agent->zip_code . ' ' . $order->reference->agent->city }}</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 text-end">
                                        <a class="header-brand" href="{{ route('dashboard.index') }}">
                                            <img style="" src="{{ asset('admin/images/brand/logo-2.jpeg') }}" class="header-brand-img logo-3" alt="Sash logo">
                                        </a>
                                    </div>
                                </div>

                                <div class="row pt-6">  
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div>
                                                <h3 style="text-transform: uppercase">Shipping Instructions: - {{ $order->created_at->format('d-m-Y') }}</h3>
                                                <br>
                                                <div style="margin-bottom:20px">
                                                    <h3>Shipper</h3>
                                                    <p class="mb-1">
                                                        {{ $order->loading_company_name }}
                                                    </p>
                                                    <p class="mb-1">{{ $order->loading_street . ' ' .$order->loading_zip_city }}</p>
                                                    <p class="mb-1">{{ $order->loading_contact_phone }}</p>
                                                    @php
                                                        $company = \App\Models\Setting::first();
                                                    @endphp
                                                    <p class="mb-1"> {{ $company->company_name }} <br>{{ $company->address }}<br>{{ $company->zip_code }} {{ $company->city }}<br>REF: {{ $order->reference->reference_no }} as agents only</p>
                                                </div>    
                                                <div>
                                                    <h3> Consignee </h3>
                                                        <p class="mb-1"> {{ $order->reference->consignee->firstname . ' ' . $order->reference->consignee->lastname }} </p>
                                                        <p class="mb-1"> {{ $order->reference->consignee->street . ' ' . $order->reference->consignee->street_no . ' ' . $order->reference->consignee->zip_code }}</p>
                                                        <p class="mb-1"> {{ $order->reference->consignee->city }}</p>
                                                        <p class="mb-1"> {{ $order->reference->consignee->country }}</p>
                                                        <p class="mb-1"> {{ $order->reference->consignee->email }}</p><br>                                                 
                                                </div>                                           
                                            </div>
                                        </div>
                                                                       
                                    </div>                                
                                </div>
								
                        
                                <div class="table-responsive push">
                                    <table class="table table-bordered table-hover mb-0 text-nowrap">
                                        <tbody>
                        
                                            @php $i=1; @endphp
                                            @foreach($order->reference->freights as $freight)
                                            <tr>
                                                <td>{{ $i++ }}</td>
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
                                  
                                    <div class="pt-6">
                                        <div class="col-lg-12">
                                            <h3>Freighttyp:</h3>
                                            <p class="mb-1"> {{ $order->reference->freights->first()->freightType->name }}</p>                                            
                                            <h3 class="" style="margin-bottom:10px "> Destination: </h3>
                                            <p class="mb-1"> {{ $order->reference->freights->first()->destination->name }}</p>
                                            <h3 class="" style="margin-bottom: 10px"> Bill of Lading:</h3>
                                            <p class="mb-1">{{ $order->bill_of_lading }}</p>
                                        </div>
                                    </div>                                
                        
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