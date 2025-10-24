<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <style>
        body {
            font-family: Arial, sans-serif; 
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
        }
        .header, .footer {
            text-align: center;
            padding: 10px;
            background-color: #f8f8f8;
        }
        .header {
            text-align: left !important;
        }
        .content {
            padding: 20px;
            background-color: #fff;
        }
        .content h3 {
            margin-top: 0;
        }
        .content p {
            margin: 5px 0;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="row">
                <div class="col-lg-6">
                    <a class="header-brand" href="{{ url('/') }}">
                        <img style="max-width: 150px" src="{{ url('https://carriergo.de/nmh/public/admin/images/brand/logo-main.png') }}" class="header-brand-img logo" alt="logo">
                    </a>
                    <div>
                        <address class="pt-3">
                            NMH Cargo & Shipping L.L.C<br>
                            ان ام اتش الشحن و المكحة ش ذ.م.م
                            <br>P.O. Box 415073 - Al Saqr Business Tower<br>
                            Sheikh Zayed Road - Dubai - UAE<br>
                            email: nmhshipping@gmail.com<br>
                            phone: + 971 50 461 0645 / +971 56 245 2386<br>
                        </address>
                    </div>
                </div>
                <div class="col-lg-6 text-end border-bottom">
                </div>
            </div>
        </div>
        <div class="content">
            <h4 class="h3">{{ optional($invoice->payer)->company_name }}</h4>
            <p>{{ optional($invoice->payer)->street_no }} {{ optional($invoice->payer)->street }}</p>
            <p>{{ optional($invoice->payer)->city }}</p>
            <p>{{ optional($invoice->payer)->country }}</p>
            <p>{{ optional($invoice->payer)->email }}</p>
            <br>
            <h4 class="h4 fw-semibold">Bank details:</h4>
            <p class="mb-1">Receiver: {{ optional($invoice->bank)->company_name }}</p>
            <p class="mb-1">IBAN: {{ optional($invoice->bank)->iban }}</p>
            <p class="mb-1">SWIFT CODE: {{ optional($invoice->bank)->swift_code }}</p>
            <p>Bank: {{ optional($invoice->bank)->bank_name }}</p>
            <br>
            <h4 class="h3">Invoice</h4>
            <p class="mb-1">INV. No. {{ $invoice->invoice_number }}</p>
            <p class="mb-1">REF. No. {{ $invoice->reference->reference_no }}</p>
            <p>Invoice Date: {{ \Carbon\Carbon::parse($invoice->created_at)->format('d-m-Y') }}</p>
            <br>
            <table class="table table-bordered table-hover mb-0 text-nowrap">
                <tbody>
                    <tr class=" ">
                        <th class="text-center">#</th>
                        <th>DESCRIPTION</th>
                        <th class="text-center">QTY</th>
                        <th class="text-end">UNIT PRICE</th>
                        <th class="text-end">SUB TOTAL</th>
                    </tr>
                    @if($invoice->reference->freights->first())
                    <tr>
                        <td class="text-center">1</td>
                        <td class="text-center">
                            @foreach($invoice->reference->freights as $freight)
                            <p class="font-w600 mb-1">{{ optional($freight->freightType)->name }}</p>
                            <div class="text-muted">
                                <div class="text-muted">{{ $freight->vehicle_fin }}</div>
                            </div>                                                    
                            @endforeach
                        </td>
                        <td class="text-center">1</td>
                        <td class="text-center">€{{ number_format($invoice->reference->price, 2) }}</td>
                        <td class="text-center">€{{ number_format($invoice->reference->price, 2) }}</td>
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
                        <td class="text-center">{{ $i++ }}</td>
                        <td class="text-center">
                            <p class="font-w600 mb-1">{{ $item->name }}</p>
                            <div class="text-muted">
                                <div class="text-muted">{{ $item->description }}</div>
                            </div>
                        </td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-center">€{{ number_format($item->price, 2) }}</td>
                        <td class="text-center">€{{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                    @endforeach
                    @if($invoice->reference->extra_fees_eur > 0)
                        <tr>
                            <td class="text-center">{{ $i++ }}</td>
                            <td class="text-center">{{ $invoice->reference->extra_fees }}</td>
                            <td class="text-center">1</td>
                            <td class="text-center">€{{ number_format($invoice->reference->extra_fees_eur, 2) }}</td>
                            <td class="text-center">€{{ number_format($invoice->reference->extra_fees_eur, 2) }}</td>
                        </tr>
                        
                        @php 
                            $subtotal += $invoice->reference->extra_fees_eur;  
                        @endphp
                    @endif                                            
                    @if($invoice->reference->additionalFees)
                        @foreach($invoice->reference->additionalFees as $row)
                            <tr>
                                <td class="text-center">{{ $i++ }}</td>
                                <td class="text-center">{{ $row->name }}</td>
                                <td class="text-center">1</td>
                                <td class="text-center">€{{ number_format($row->amount, 2) }}</td>
                                <td class="text-center">€{{ number_format($row->amount, 2) }}</td>
                            </tr>
                        @php 
                            $subtotal += $row->amount;  
                        @endphp                                                    
                        @endforeach
                    @endif

                    <tr>
                        <td colspan="4" class="fw-bold text-uppercase text-end">SUBTOTAL</td>
                        <td class="fw-bold text-end ">€{{ number_format($subtotal, 2) }}</td>
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
                        <td class="font-w600 mb-1 text-end ">€{{ number_format($tax_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="fw-bold text-uppercase text-end">INVOICE AMOUNT</td>
                        <td class="fw-bold text-end ">€{{ number_format($total_amount, 2) }}</td>
                    </tr>
                </tbody>                                   
            </table>
        </div>
        <div class="footer">
            @php
            $company = \App\Models\Setting::first();
        @endphp             
            <p>&copy; {{ date('Y') }} {{ $company->company_name }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>