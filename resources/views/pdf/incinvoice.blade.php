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
        <div class="side-app ">

            <!-- CONTAINER -->
            <div class="main-container container">

                <!-- ROW-1 OPEN -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                
                                <div class="row">
                                    <div class="col-lg-8">
                                        <address class="pt-3">
                                            NMH Cargo & Shipping L.L.C<br>
                                            ان ام اتش الشحن و المكحة ش ذ.م.م
                                            <br>P.O. Box 415073 - Al Saqr Business Tower<br>
                                            Sheikh Zayed Road - Dubai - UAE<br>
                                            email: nmhshipping@gmail.com<br>
                                            phone: + 971 50 461 0645 / +971 56 245 2386<br>
                                        </address>
                                    </div>
                                    <div class="col-lg-4 text-end">
                                        <a class="header-brand" href="{{ route('dashboard.index') }}">
                                            <img style="max-width: 150px" src="{{ asset('admin/images/brand/logo-main.png') }}" class="header-brand-img logo" alt="logo">
                                        </a>
                                    </div>									
                                </div>
                                <div class="row pt-5">
                                    <div class="col-lg-6">
                                        <p class="h3">Invoice:</p>
                                        <p class="fs-18 fw-semibold mb-0">{{ $invoice->client->company_name }}</p>
                                        <address>
                                            {{ $invoice->client->street_no }} {{ $invoice->client->street }}<br>
                                            {{ $invoice->client->city }}<br>
                                            {{ $invoice->client->country }}<br>
                                            {{ $invoice->client->email }}
                                        </address>
                                    </div>
                                </div>
                                <div class="table-responsive push">
                                    <table class="table table-bordered table-hover mb-0 text-nowrap">
                                        <tbody>
                                            <tr class=" ">
                                                <th></th>
                                                <th>DESCRIPTION</th>
                                                <th>QTY</th>
                                                <th class="text-end">UNIT PRICE</th>
                                                <th class="text-end">SUB TOTAL</th>
                                            </tr>

                                            @php $subtotal = 0; $counter = 1; @endphp
                                            @foreach($invoice->items as $item)
                                                @php $subtotal += $item->quantity * $item->price; @endphp
                                                <tr data-bs-toggle="modal" data-bs-target="#addServiceModal" wire:click="editItem({{ $item->id }})">
                                                    <td>{{ $counter++ }}</td>
                                                    <td>
                                                        <p class="font-w600 mb-1">{{ $item->name }}</p>
                                                        <div class="text-muted">
                                                            <div class="text-muted">1{{ $item->description }}</div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $item->quantity }}</td>
                                                    <td class="text-end">{{ session()->has('currency') ? session('currency') : '€' }} {{ $item->price }}</td>
                                                    <td class="text-end">{{ session()->has('currency') ? session('currency') : '€' }} {{ $item->quantity * $item->price }}</td>
                                                </tr>
                                            @endforeach                                                                                              
                                            <tr>
                                                <td colspan="4" class="fw-bold text-uppercase text-end">SUBTOTAL</td>
                                                <td class="fw-bold text-end ">{{ session()->has('currency') ? session('currency') : '€' }} {{ $subtotal }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="font-w600 mb-1 text-uppercase text-end">TAX RATE</td>
                                                <td class="font-w600 mb-1 text-end ">0.00%</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="font-w600 mb-1 text-uppercase text-end">TOTAL TAX</td>
                                                <td class="font-w600 mb-1 text-end ">{{ session()->has('currency') ? session('currency') : '€' }} 0.00</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="fw-bold text-uppercase text-end">Invoice amount  </td>
                                                <td class="fw-bold text-end ">{{ session()->has('currency') ? session('currency') : '€' }} {{ number_format($subtotal) }}</td>
                                            </tr>
                                            
                                        </tbody>
                                    </table><br><div>Amount is due immediately!</div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button type="button" class="btn btn-primary mb-1 d-print-none" onclick="javascript:window.print();"><i class="si si-wallet"></i> Save Invoice</button>
                            </div>
                        </div>
                    </div>
                    <!-- COL-END -->
                </div>
                <!-- ROW-1 CLOSED -->

            </div>
            <!-- CONTAINER CLOSED -->
        </div>
    </div>
@endsection