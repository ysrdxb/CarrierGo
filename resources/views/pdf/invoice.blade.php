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

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                
                                <div class="row">
                                        <div class="col-lg-6">
                                            <address class="pt-3">
                                                NMH Cargo & Shipping L.L.C<br>
                                                ان ام اتش الشحن و المكحة ش ذ.م.م
                                                <br>P.O. Box 415073 - Al Saqr Business Tower<br>
                                                Sheikh Zayed Road - Dubai - UAE<br>
                                                email: nmhshipping@gmail.com<br>
                                                phone: + 971 50 461 0645 / +971 56 245 2386<br>
                                            </address>
                                        </div>									
                                    <div class="col-lg-6" style="text-align:right">
                                        <a class="header-brand" href="{{ route('dashboard.index') }}">
                                            <img style="max-width: 150px" src="{{ asset('admin/images/brand/logo-main.png') }}" class="header-brand-img logo" alt="logo">
                                        </a>
									</div>
                                </div>
                                <div class="row pt-5">
                                    <div class="col-lg-6">
                                        <p class="h5">{{ optional($invoice->payer)->company_name }}</p>
                                        <address>
                                            {{ optional($invoice->payer)->street_no }} {{ optional($invoice->payer)->street }}<br>
                                            {{ optional($invoice->payer)->city }}<br>
                                            {{ optional($invoice->payer)->country }}<br>
                                            {{ optional($invoice->payer)->email }}
                                        </address>
                                    </div>
                                    <div class="col-lg-6 text-end">
                                        <p class="h5 fw-semibold">Bank details:</p>
                                        <p class="mb-1">Receiver: {{ optional($invoice->bank)->company_name }}</p>
                                        <p class="mb-1">IBAN: {{ optional($invoice->bank)->iban }}</p>
                                        <p class="mb-1">SWIFT CODE: {{ optional($invoice->bank)->swift_code }}</p>
                                        <p>Bank: {{ optional($invoice->bank)->bank_name }}</p>
                                    </div>
                                </div>

                                <div class="row pt-5">
                                    <div class="col-lg-6">
                                        <p class="h3">Invoice</p>
                                        <p class="mb-1">INV. No. {{ $invoice->invoice_number }}</p>
                                        <p class="mb-1">REF. No. {{ $invoice->reference->reference_no }}</p>
                                    </div>
                                    <div class="col-lg-6 text-end">
                                        <p class="mb-1">{{ \Carbon\Carbon::parse($invoice->created_at)->format('d-m-Y') }}</p>
                                    </div>
                                </div>

                                <div class="table-responsive push">
                                    <table class="table table-bordered table-hover mb-0 text-nowrap">
                                        <tbody>
                                            <tr class=" ">
                                                <th>#</th>
                                                <th>DESCRIPTION</th>
                                                <th>QTY</th>
                                                <th class="text-end">UNIT PRICE</th>
                                                <th class="text-end">SUB TOTAL</th>
                                            </tr>
                                            @if($invoice->reference->freights->first())
                                            <tr>
                                                <td>1</td>
                                                <td>
                                                    @foreach($invoice->reference->freights as $freight)
                                                    <p class="font-w600 mb-1">{{ optional($freight->freightType)->name }}</p>
                                                    <div class="text-muted">
                                                        <div class="text-muted">{{ $freight->vehicle_fin }}</div>
                                                    </div>                                                    
                                                    @endforeach
                                                </td>
                                                <td>1</td>
                                                <td>{{ session()->has('currency') ? session('currency') : '€' }} {{ number_format($invoice->reference->price, 2) }}</td>
                                                <td>{{ session()->has('currency') ? session('currency') : '€' }} {{ number_format($invoice->reference->price, 2) }}</td>
                                            </tr>
                                            @php $i =  2; @endphp
                                            @else 
                                            @php $i =  1; @endphp
                                            @endif
                                            @php 
                                                $subtotal = $invoice->reference->price; 
                                            @endphp
                                            @foreach($invoice->items as $item)
                                            @php 
                                                $subtotal += $item->price * $item->quantity; 
                                            @endphp
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>
                                                    <p class="font-w600 mb-1">{{ $item->name }}</p>
                                                    <div class="text-muted">
                                                        <div class="text-muted">{{ $item->description }}</div>
                                                    </div>
                                                </td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>{{ session()->has('currency') ? session('currency') : '€' }} {{ number_format($item->price, 2) }}</td>
                                                <td>{{ session()->has('currency') ? session('currency') : '€' }} {{ number_format($item->price * $item->quantity, 2) }}</td>
                                            </tr>
                                            @endforeach
                                            @if($invoice->reference->extra_fees_eur > 0)
                                                <tr>
                                                    <td>{{ $i++ }}</td>
                                                    <td>{{ $invoice->reference->extra_fees }}</td>
                                                    <td>1</td>
                                                    <td>{{ session()->has('currency') ? session('currency') : '€' }} {{ number_format($invoice->reference->extra_fees_eur, 2) }}</td>
                                                    <td>{{ session()->has('currency') ? session('currency') : '€' }} {{ number_format($invoice->reference->extra_fees_eur, 2) }}</td>
                                                </tr>
                                                
                                                @php 
                                                    $subtotal += $invoice->reference->extra_fees_eur;  
                                                @endphp
                                            @endif                                            
                                            @if($invoice->reference->additionalFees)
                                                @foreach($invoice->reference->additionalFees as $row)
                                                    <tr>
                                                        <td>{{ $i++ }}</td>
                                                        <td>{{ $row->name }}</td>
                                                        <td>1</td>
                                                        <td>{{ session()->has('currency') ? session('currency') : '€' }} {{ number_format($row->amount, 2) }}</td>
                                                        <td>{{ session()->has('currency') ? session('currency') : '€' }} {{ number_format($row->amount, 2) }}</td>
                                                    </tr>
                                                @php 
                                                    $subtotal += $row->amount;  
                                                @endphp                                                    
                                                @endforeach
                                            @endif

                                            <tr>
                                                <td colspan="4" class="fw-bold text-uppercase text-end">SUBTOTAL</td>
                                                <td class="fw-bold text-end ">{{ session()->has('currency') ? session('currency') : '€' }} {{ number_format($subtotal, 2) }}</td>
                                            </tr>
                                            @php
                                                $tax_amount = $subtotal * ($invoice->tax_rate / 100);  
                                                $total_amount = $subtotal + $tax_amount;                                            
                                            @endphp
                                            <tr>
                                                <td colspan="4" class="font-w600 mb-1 text-uppercase text-end">TAX RATE</td>
                                                <td class="font-w600 mb-1 text-end ">{{ $invoice->tax_rate }}%</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="font-w600 mb-1 text-uppercase text-end">TOTAL TAX</td>
                                                <td class="font-w600 mb-1 text-end ">{{ session()->has('currency') ? session('currency') : '€' }} {{ number_format($tax_amount, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="fw-bold text-uppercase text-end">INVOICE AMOUNT</td>
                                                <td class="fw-bold text-end ">{{ session()->has('currency') ? session('currency') : '€' }} {{ number_format($total_amount, 2) }}</td>
                                            </tr>
                                        </tbody>                                   
                                    </table>
                                    <br><div>Amount is due immediately!</div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button type="button" class="btn btn-primary mb-1 d-print-none" onclick="javascript:window.print();"><i class="si si-wallet"></i> Save Invoice</button>
                            </div>
                        </div>
                    </div>
                    <!-- COL-END -->
                </div>

            </div>
            <!-- CONTAINER CLOSED -->
        </div>
    </div>
<script>
    //document.addEventListener('DOMContentLoaded', function() {
        //function savePDF() {
            //window.print();
        //}

       // savePDF();
    //});
</script>
@endsection