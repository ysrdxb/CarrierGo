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
            <h4>To:</h4>
            <p class="mb-1"> {{ $transportOrder->reference->carrier->company_name }} </p>
            <p class="mb-1"> {{ $transportOrder->reference->carrier->street_no }} {{ $transportOrder->reference->carrier->street }}</p>
            <p class="mb-1"> {{ $transportOrder->reference->carrier->zip_code }} {{ $transportOrder->reference->carrier->city }} </p>
            <br>

            <h4>Loading Address</h4>
            @if(!empty($transportOrder->loading_company_name))
                <div class="pt-0">
                    <p>{{ $transportOrder->loading_company_name }}</p>
                    <p>{{ $transportOrder->loading_street }}</p>
                    <p>{{ $transportOrder->loading_zip_city }}</p>
                    <p>{{ $transportOrder->loading_contact_name }} {{ $transportOrder->loading_contact_phone }}</p>
                </div>
            @else
                <div class="pt-0">
                    <p>{{ optional($transportOrder->merchant)->company_name }}</p>
                    <p>{{ optional($transportOrder->merchant)->street }}</p>
                    <p>{{ optional($transportOrder->merchant)->zip_code }} {{ optional($transportOrder->merchant)->city }}</p>
                    <p>{{ optional($transportOrder->merchant)->firstname }} {{ optional($transportOrder->merchant)->lastname }} {{ optional($transportOrder->merchant)->phone }}</p>
                </div>
            @endif
            <br>
            <h4>Unloading Address</h4>
            <div class="pt-0">
                <p>{{ $transportOrder->unloadingAddress->company_name }}</p>
                <p>{{ $transportOrder->unloadingAddress->street }}</p>
                <p>{{ $transportOrder->unloadingAddress->zip_city }}</p>
                <p>{{ $transportOrder->unloadingAddress->contact_name }} {{ $transportOrder->unloadingAddress->contact_phone }}</p>
            </div>
            <br>
            <p class="h4 fw-semibold">Power of attorney to pick up a vehicle </p>
            <p>Date :  {{ \Carbon\Carbon::parse($transportOrder->add_date)->format('d-m-Y') }}</p>
                                     
            <table class="table table-bordered table-hover mb-0 text-nowrap">
                <tbody>
                    @php $counter = 1; @endphp
                    @foreach($transportOrder->reference->freights as $freight)
                    <tr>
                        <td class="text-center">{{ $counter++ }}</td>
                        <td>
                            <p class="font-w600 mb-1">{{ $freight->vehicle_model }}</p>
                            <div class="text-muted">
                                <div class="text-muted">{{ $freight->vehicle_fin }}</div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                   
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