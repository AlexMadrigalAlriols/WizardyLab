<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{$invoice->number}}</title>
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
            font-size: 15px;
        }
        .addresses {
            margin-bottom: 20px;
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
        .p-4 {
            padding-left: 1.25rem;
            padding-top: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @if ((bool) $invoice->data['show_logo'])
                <img src="data:image/png;base64,{{ $logoBase64 }}" alt="Logo">
            @endif
        </div>
        <div class="invoice-info">
            <h1>Factura #{{ $invoice->number }}</h1>
            <p>Fecha de emisión: {{ $invoice->issue_date }}</p>
            <p>Forma de pago: Transferencia bancaria</p>
            <p>Cuenta bancaria: <b>{{ $billingClient->account_number ?? '' }}</b></p>
        </div>
        <div class="addresses">
            <div>
                <h3>Datos del emisor:</h3>
                <p>{{ $billingClient->name }}</p>
                <p>{{ $billingClient->vat_number ?? '' }}</p>
                <p>{{ $billingClient->address }}</p>
                <p>{{ $billingClient->zip }}, {{ $billingClient->city }}. {{ $billingClient->state }}</p>
                <p>{{ $billingClient->email }}</p>
                <p>{{ $billingClient->phone }}</p>
            </div>
            <div>
                <h3>Datos del cliente:</h3>
                <p>{{ $invoice->client->name }}</p>
                <p>{{ $invoice->client->vat_number ?? '' }}</p>
                <p>{{ $invoice->client->address }}</p>
                <p>{{ $invoice->client->city }}, {{ $invoice->client->state }}</p>
                <p>{{ $invoice->client->email }}</p>
                <p>{{ $invoice->client->phone }}</p>
            </div>
        </div>
        <table class="tasks">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Horas/Cantidad</th>
                    <th>Precio</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks ?? [] as $task)
                    <tr>
                        <td>{{$task->title}}</td>
                        <td>{{$task->total_hours}}h</td>
                        <td>{{$price_per_hour}} {{$invoice->client->currency->symbol}}</td>
                        <td>{{$task->total_hours * $price_per_hour}} {{$invoice->client->currency->symbol}}</td>
                    </tr>
                @endforeach

                @foreach ($items ?? [] as $item)
                    <tr>
                        <td>{{$item['name']}}</td>
                        <td>{{$item['quantity']}}</td>
                        <td>{{ number_format($item['amount'], 2) }} {{$invoice->client->currency->symbol}}</td>
                        <td>{{ number_format($item['total'], 2) }} {{$invoice->client->currency->symbol}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table class="totals">
            <tr>
                <th>Subtotal:</th>
                <td>{{ number_format($invoice->amount, 2) }} {{$invoice->client->currency->symbol}}</td>
            </tr>
            <tr>
                @if ($invoice->data['include_tax'] ?? true)
                    <th>IVA (21%):</th>
                    <td>{{ number_format($invoice->tax, 2) }} {{$invoice->client->currency->symbol}}</td>
                @else
                    <th>IVA (0%):</th>
                    <td>{{number_format('0', 2)}} {{$invoice->client->currency->symbol}}</td>
                @endif
            </tr>
            <tr>
                <th>Total:</th>
                @if ($invoice->data['include_tax'] ?? true)
                    <td class="total">{{ number_format($invoice->total, 2) }} {{$invoice->client->currency->symbol}}</td>
                @else
                    <td class="total">{{ number_format($invoice->amount, 2) }} {{$invoice->client->currency->symbol}}</td>
                @endif
            </tr>
        </table>

        <div class="p-4">
            <p>Firma:</p>
            <p>{{ $billingClient->name }}</p>
        </div>
    </div>
</body>
</html>
