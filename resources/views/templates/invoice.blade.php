<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Factura</title>
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
        }
        .totals th, .totals td {
            padding: 8px;
            text-align: left;
        }
        .totals th {
            text-align: right;
        }
        .totals .total {
            font-weight: bold;
        }
        .footer {
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="data:image/png;base64,{{ $logoBase64 }}" alt="Logo">
        </div>
        <div class="invoice-info">
            <h1>Factura #{{ $invoice->number }}</h1>
            <p>Fecha: {{ $invoice->issue_date }}</p>
            <p>Cliente: {{ $invoice->client?->name }} ({{$invoice->client?->company?->name}})</p>
        </div>
        <div class="addresses">
            <div>
                <h3>De:</h3>
                <p>WizardyLab S.L</p>
                <p>Carcel de Guantánamo</p>
                <p>Cuba</p>
                <p>Cuba</p>
                <p>info@wizardylab.com</p>
            </div>
            <div>
                <h3>Para:</h3>
                <p>{{ $invoice->client->name }}</p>
                <p>{{ $invoice->client->address }}</p>
                <p>{{ $invoice->client->city }}</p>
                <p>{{ $invoice->client->state }}</p>
                <p>{{ $invoice->client->email }}</p>
            </div>
        </div>
        <table class="tasks">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Horas</th>
                    <th>Precio/hora</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $task)
                    <tr>
                        <td>{{$task->title}}</td>
                        <td>{{$task->total_hours}}h</td>
                        <td>{{$price_per_hour}} {{$invoice->client->currency->symbol}}</td>
                        <td>{{$task->total_hours * $price_per_hour}} {{$invoice->client->currency->symbol}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table class="totals">
            <tr>
                <th>Subtotal:</th>
                <td>{{ $invoice->amount }} {{$invoice->client->currency->symbol}}</td>
            </tr>
            <tr>
                <th>IVA (21%):</th>
                <td>{{ $invoice->tax }} {{$invoice->client->currency->symbol}}</td>
            </tr>
            <tr>
                <th>Total:</th>
                <td class="total">{{ $invoice->total }} {{$invoice->client->currency->symbol}}</td>
            </tr>
        </table>
    </div>
</body>
</html>
