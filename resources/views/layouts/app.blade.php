<!DOCTYPE html>
<html>
@php
    use App\Helpers\SubdomainHelper;

    $portal = SubdomainHelper::getPortal(request());
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="locale" content="{{ app()->getLocale() }}">
    <meta name="robots" content="noindex, nofollow">

    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('img/favicons/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('img/favicons/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('img/favicons/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('img/favicons/site.webmanifest')}}">
    <link rel="mask-icon" href="{{asset('img/favicons/safari-pinned-tab.svg')}}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <title>{{ $portal->name }} | {{ __('global.auth.' . strtolower($section) . '_box_title') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet" />

    <style>
        :root {
            --primary-color: {{ $portal->data['primary_color'] }};
            --middle-color: {{ $portal->data['primary_color'] }};
            --secondary-color: {{ $portal->data['primary_color'] }};
            --btn-text: {{ $portal->data['btn_text_color'] }};
        }
    </style>
    @yield('styles')
</head>

<body class="login-page">
    @yield('content')
</body>
<script src="https://code.jquery.com/jquery-3.7.0.min.js" crossorigin="anonymous"></script>
@yield('scripts')
</html>
