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
            <h3>Agent:</h3>
            <p>{{ $order->reference->agent->company_name }}</p>
            <p>{{ $order->reference->agent->street }} {{ $order->reference->agent->street_no }}</p>
            <p>{{ $order->reference->agent->zip_code }} {{ $order->reference->agent->city }}</p>
            <br>
            <h3>Shipper:</h3>
            <p>{{ $order->loading_company_name }}</p>
            <p>{{ $order->loading_street }} {{ $order->loading_zip_city }}</p>
            <p>{{ $order->loading_contact_phone }}</p>
            <br>
            <h3>Consignee:</h3>
            <p>{{ $order->reference->consignee->firstname }} {{ $order->reference->consignee->lastname }}</p>
            <p>{{ $order->reference->consignee->street }} {{ $order->reference->consignee->street_no }} {{ $order->reference->consignee->zip_code }}</p>
            <p>{{ $order->reference->consignee->city }}</p>
            <p>{{ $order->reference->consignee->country }}</p>
            <p>{{ $order->reference->consignee->email }}</p>
            <br>
            <h3>Freight Details:</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Vehicle Model</th>
                        <th>Vehicle FIN</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->reference->freights as $freight)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $freight->vehicle_model }}</td>
                            <td>{{ $freight->vehicle_fin }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <br>
            <h3>Freight Type:</h3>
            <p>{{ $order->reference->freights->first()->type }}</p>
            <h3>Destination:</h3>
            <p>{{ $order->reference->freights->first()->destination->name }}</p>
            <h3>Bill of Lading:</h3>
            <p>{{ $order->bill_of_lading }}</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} NMH Cargo & Shipping L.L.C. All rights reserved.</p>
        </div>
    </div>
</body>
</html>