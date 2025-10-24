<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Authorizations</title>
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
        .content h4 {
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
            <div style="display: flex; justify-content: space-between;">
                <div>
                    <a class="header-brand" href="{{ url('/') }}">
                        <img style="max-width: 150px" src="{{ url('https://carriergo.de/nmh/public/admin/images/brand/logo-main.png') }}" class="header-brand-img logo" alt="logo">
                    </a>
                    <address style="margin-top: 10px;">
                        NMH Cargo & Shipping L.L.C<br>
                        ان ام اتش الشحن و المكحة ش ذ.م.م
                        <br>P.O. Box 415073 - Al Saqr Business Tower<br>
                        Sheikh Zayed Road - Dubai - UAE<br>
                        email: nmhshipping@gmail.com<br>
                        phone: + 971 50 461 0645 / +971 56 245 2386<br>
                    </address>
                </div>
                <div></div>
            </div>
        </div>
        <div class="content">
            <p class="mb-1"> {{ $data->reference->merchant->company_name }} </p>
            <p class="mb-1"> {{ $data->reference->merchant->street_no }} {{ $data->reference->merchant->street }}</p>
            <p class="mb-1"> {{ $data->reference->merchant->zip_code }} {{ $data->reference->merchant->city }} </p>

            <h4><br> Power of attorney to pick up a vehicle </h4>
            <p class="mb-1"><br>{{ $data->created_at->format('d-m-Y') }}</p>
            <br>
            <p class="mb-1"> Dear Sir or Madam, <br><br>{{ $data->driver_name }} with registration number {{ $data->plate_no }} has been commissioned by us to take over the following vehicle from you:</p>
            <table class="table">
                <tbody>
                    @php $counter = 1; @endphp
                    @foreach($data->reference->freights as $freight)
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
            <br>
            @php
                $company = \App\Models\Setting::first();
            @endphp 
            Best regards, <br><br>{{ $company->company_name }} <br>{{ $company->address }}        
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ $company->company_name }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
