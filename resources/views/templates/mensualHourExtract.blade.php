
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Mensual Extract</title>
    <style>
        :root {
            --primary-color: {{ $portal->data['primary_color'] }};
            --secondary-color: {{ $portal->data['secondary_color'] }};
            --secondary-color-light: {{ $portal->secondary_light }};
            --btn-text: {{ $portal->data['btn_text_color'] }};
            --menu-text-color: {{ $portal->data['menu_text_color'] }};
        }
        body {
            font-family: Poppins, sans-serif;
        }
        .total-background {
            background-color: #f2f2f2;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            padding-bottom: 15px;
            padding-top: 15px;
            font-size: 12px !important;
        }

        th, td {
            padding-left: 15px;
            padding-right: 15px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .p-4 {
            padding: 1.5rem !important;
        }

        .p-5 {
            padding: 3rem !important;
        }

        .py-1 {
            padding-top: 0.25rem !important;
            padding-bottom: 0.25rem !important;
        }

        .px-4 {
            padding-right: 1.5rem !important;
            padding-left: 1.5rem !important;
        }

        .mb-0 {
            margin-bottom: 0 !important;
        }

        .mb-3 {
            margin-bottom: 1rem !important;
        }

        .h1, h1 {
            font-size: 2.5rem;
        }

        .h6, h6 {
            font-size: 1rem;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -0.75rem;
            margin-left: -0.75rem;
            width: 100%;
        }

        .col-md-8, .col-md-4 {
            position: relative;
            padding-right: 0.75rem;
            padding-left: 0.75rem;
            display: inline-block;
        }

        @media (min-width: 768px) {
            .col-md-8 {
                flex: 0 0 auto;
                width: 59.666667%;
            }

            .col-md-4 {
                flex: 0 0 auto;
                width: 33%;
            }
        }

        .text-end {
            text-align: end !important;
        }

        .badge {
            display: inline-block;
            padding: 0.25em 0.4em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.375rem;
        }

        .bg-success {
            background-color: #198754 !important;
            color: white;
        }

        .bg-danger {
            background-color: #dc3545 !important;
            color: white;
        }

        .bg-warning {
            background-color: #ffc107 !important;
        }

        .ms-2 {
            margin-left: 0.5rem !important;
        }

        .align-middle {
            vertical-align: middle !important;
        }
    </style>
</head>
<body>
    <div class="p-4">
        <div class="row">
            <div class="col-md-8">
                <h3>Monthly Hours Extract</h1>
                <p class="h6 mb-0">Employee: {{ $user->name }} ({{ $user->code }})</p>
                <p class="h6 mb-0">Month: {{ $month->format('F') }}</p>
                <p class="h6 mb-3">Year: {{ $year }}</p>
            </div>
            <div class="col-md-4" style="float: right;">
                <img src="data:image/png;base64,{{ $logoBase64 }}" alt="Logo" style="max-width: 250px; max-height: 300px">
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Day</th>
                    <th>Time</th>
                    <th>Expected Time</th>
                    <th>Productivity</th>
                    <th>Incidence</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dates as $date)
                    <tr>
                        <td>{{ $date['day']->format('d/m/y'); }}</td>
                        <td>{{ $date['worked_hours'] }}</td>
                        <td>{{ $date['hours_per_day'] }}</td>
                        <td>
                            <p>{{ $date['productivity'] }}</p>
                            <span class="badge {{ (int) $date['productivity_percentage'] >= 80 ? 'bg-success' : 'bg-danger'}} {{ (int) $date['productivity_percentage'] < 80 && (int) $date['productivity_percentage'] > 50  ? 'bg-warning' : '' }}">{{ $date['productivity_percentage']}}</span>
                        </td>
                        <td>
                            @if ($date['excess_time'])
                                <span class="badge {{ strpos($date['excess_time'] , '-') !== false ? 'bg-danger' : 'bg-warning'}} ms-2">{{$date['excess_time']}}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-4 py-1">
            <div class="total-background row">
                <table>
                    <tr>
                        <td style="margin-bottom: 0;  padding-bottom: 0;">Total worked hours:</td>
                        <td style="text-align: right; margin-bottom: 0; padding-bottom: 0;">{{ $totals['worked_hours'] }}</td>
                    </tr>
                    <tr>
                        <td style="margin-bottom: 0;  padding-bottom: 0;">Total estimated:</td>
                        <td style="text-align: right; margin-bottom: 0; padding-bottom: 0;">{{ $totals['estimated_hours'] }}</td>
                    </tr>
                    <tr>
                        <td style="margin-bottom: 0;  padding-bottom: 0;">Total excess:</td>
                        <td style="text-align: right; margin-bottom: 0; padding-bottom: 0;">{{ $totals['balance'] }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div style="border-style: solid; border-color: black; border-width: 1px; vertical-align: middle;">
            <p class="p-5 align-middle"><b>Employee Sign:</b></p>
        </div>
    </div>
</body>
</html>
