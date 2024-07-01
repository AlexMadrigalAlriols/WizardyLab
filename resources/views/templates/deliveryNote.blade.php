<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{$deliveryNote->number}}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            width: 150px;
            float: right
        }
        .invoice-info {
            margin-bottom: 20px;
        }
        .invoice-info h1 {
            margin: 0;
            font-size: 24px;
        }
        .invoice-info p {
            margin: 5px 0;
            font-size: 14px;
        }
        .addresses {
            margin-bottom: 20px;
            background-color: #f2f2f2;
            padding: 0.75rem;
        }
        .addresses div {
            width: 45%;
            display: inline-block;
            vertical-align: top;
        }
        .addresses div p {
            margin: 5px 0;
            font-size: 14px;
        }
        .signs div {
            width: 45%;
            display: inline-block;
            vertical-align: top;
        }
        .tasks {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .tasks th, .tasks td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .tasks th {
            background-color: #f2f2f2;
        }
        .totals {
            width: 100%;
            margin-bottom: 20px;
            margin-top: 3rem;
        }
        .totals th, .totals td {
            padding: 8px;
            text-align: left;
        }
        .totals th {
            text-align: right;
            background-color: #f2f2f2;
        }
        .totals .total {
            font-weight: bold;
        }
        .totals tr td {
            width: 9rem;
        }
        .footer {
            text-align: center;
            font-size: 12px;
        }
        .p-5 {
            padding: 3rem !important;
        }
        .p-2 {
            padding: 0.5rem !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="data:image/png;base64,{{ $logoBase64 }}" alt="Logo">
        </div>
        <div class="invoice-info">
            <h1>Albaran #{{ $deliveryNote->number }}</h1>
            <p>Fecha: {{ $deliveryNote->issue_date }}</p>
        </div>
        <div class="addresses">
            <div>
                <h3>Entregar a:</h3>
                <p><b>Cliente:</b> {{ $deliveryNote->client->name }}</p>
                <p><b>Address:</b> {{ $deliveryNote->client->address }}</p>
                <p><b>City:</b> {{ $deliveryNote->client->city }}</p>
                <p><b>State:</b> {{ $deliveryNote->client->state }}</p>
                <p><b>Zip:</b> {{ $deliveryNote->client->zip }}</p>
                <p><b>Country:</b> {{ $deliveryNote->client->country->name }}</p>
                <p><b>Email:</b> {{ $deliveryNote->client->email }}</p>
                <p><b>Phone:</b> {{ $deliveryNote->client->phone }}</p>
                <p><b>NIF:</b> {{ $deliveryNote->client->vat_number ?? '' }}</p>
            </div>
            <div>
                <h3>Remitente:</h3>
                <p><b>Cliente:</b> {{ $billingClient->name }}</p>
                <p><b>Address:</b> {{ $billingClient->address }}</p>
                <p><b>City:</b> {{ $billingClient->city }}</p>
                <p><b>State:</b> {{ $billingClient->state }}</p>
                <p><b>Zip:</b> {{ $billingClient->zip }}</p>
                <p><b>Country:</b> {{ $billingClient->country->name }}</p>
                <p><b>Email:</b> {{ $billingClient->email }}</p>
                <p><b>Phone:</b> {{ $billingClient->phone }}</p>
                <p><b>NIF:</b> {{ $billingClient->vat_number ?? '' }}</p>
            </div>
        </div>
        <table class="tasks">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Cantidad</th>
                    @if ($deliveryNote->data['type'] == 'valued')
                        <th>Precio</th>
                        <th>Total</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($items ?? [] as $item)
                    <tr>
                        <td>{{$item['name']}}</td>
                        <td>{{$item['quantity']}}</td>
                        @if ($deliveryNote->data['type'] == 'valued')
                            <td>{{$item['amount']}} {{$deliveryNote->client->currency->symbol}}</td>
                            <td>{{$item['total']}} {{$deliveryNote->client->currency->symbol}}</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if ($deliveryNote->data['type'] == 'valued')
            <table class="totals">
                <tr>
                    <th>Subtotal:</th>
                    <td>{{ $deliveryNote->amount }} {{$deliveryNote->client->currency->symbol}}</td>
                </tr>
                <tr>
                    <th>IVA (21%):</th>
                    <td>{{ $deliveryNote->tax }} {{$deliveryNote->client->currency->symbol}}</td>
                </tr>
                <tr>
                    <th>Total:</th>
                    <td class="total">{{ $deliveryNote->total }} {{$deliveryNote->client->currency->symbol}}</td>
                </tr>
            </table>
        @endif

        <div class="signs">
            <div style="border-style: solid; border-color: black; border-width: 1px; height: 150px; vertical-align: middle; margin-top: 1rem;">
                <p><b>Sign:</b></p>
            </div>

            <div style="border-style: solid; border-color: black; border-width: 1px; height: 150px; vertical-align: middle; margin-top: 1rem;">
                <p style="padding: 0.5rem;"><b>Observations:</b> <span style="margin-left: 1rem; word-wrap: break-word;">{{ $deliveryNote->data['notes'] ?? '' }}</span></p>
            </div>
        </div>
    </div>
</body>
</html>
