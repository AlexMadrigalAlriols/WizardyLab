<!DOCTYPE html>
<html>

@php
    use Jenssegers\Agent\Facades\Agent;
    use App\Helpers\SubdomainHelper;

    $isPhone = Agent::isMobile();

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

    <title>{{ $portal->name }} | {{ trans('global.' . strtolower($section)) }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/2.0.3/css/select.dataTables.css">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/board.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/navbar.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/dragula.min.css') }}" rel="stylesheet" />
    <link href="{{asset('vendor/spectrum/spectrum.min.css')}}" rel="stylesheet">

    <style>
        :root {
            --primary-color: {{ $portal->data['primary_color'] }};
            --secondary-color: {{ $portal->data['secondary_color'] }};
            --secondary-color-light: {{ $portal->secondary_light }};
            --btn-text: {{ $portal->data['btn_text_color'] }};
            --menu-text-color: {{ $portal->data['menu_text_color'] }};
        }
    </style>
    @yield('styles')
</head>

<body class="{{$isPhone ? '' : 'body-pd'}}" id="body-pd">
    @include('sweetalert::alert')
    <header class="header {{$isPhone ? '' : 'header-pd body-pd'}}" id="header">
        <div class="header_toggle {{$isPhone ? '' : 'show'}}" id="header-toggle">
            <i class='bx {{$isPhone ? 'bx-menu' : 'bx-menu bx-x '}}' id="header-toggle-icon"></i>

            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="d-inline-block ms-3 mt-1 bc-header-text">
                <ol class="breadcrumb mb-0 fs-5">
                    @foreach ($breadcrumbs ?? [['label' => $section, 'url' => '']] as $idx => $bc)
                        @if ($idx == 0)
                            <li class="breadcrumb-item fs-4">
                                <a href="{{ $bc['url'] }}" class="text-decoration-none text-dark"><b>{{ $bc['label'] }}</b></a>
                            </li>
                        @else
                            <li class="breadcrumb-item mt-1">
                                <a href="{{ $bc['url'] }}" class="text-decoration-none text-muted"><b>{{ $bc['label'] }}</b></a>
                            </li>
                        @endif
                    @endforeach
                </ol>
            </nav>
        </div>
        <div>
            PROFILE USER
            <a href="{{route('logout')}}" class="text-dark me-1"><i class="bx bx-power-off" style="font-size: 23px;"></i></a>
        </div>
    </header>
    <div class="l-navbar {{$isPhone ? '' : 'show'}}" id="nav-bar">
        <nav class="nav">
            <div class="scrollbar">
                <a href="{{route('dashboard.index')}}" class="nav_logo"><img src="{{ $portal->logo }}" width="175px"></a>
                <div class="nav_list">
                    <hr>
                    <a href="{{route('dashboard.index')}}" class="nav_link has_submenu">
                        <div>
                            <i class='bx bx-objects-horizontal-left nav_icon'></i>
                            <span class="nav_name">ERP</span>
                        </div>
                        <i class='bx bx-link-external'></i>
                    </a>
                    <hr>
                    <a href="{{route('dashboard.index')}}" class="nav_link {{ $section == 'Dashboard' ? 'active' : ''}}">
                        <i class='bx bx-home-alt nav_icon'></i>
                        <span class="nav_name">{{__('global.dashboard')}}</span>
                    </a>
                    <hr>
                    <div class="nav_item has-treeview">
                        <a href="#" class="nav_link has_submenu {{ $section == 'Leads' ? 'active' : ''}}">
                            <div>
                                <i class='bx bx-dollar-circle nav_icon' ></i>
                                <span class="nav_name">{{__('global.sales')}}</span>
                            </div>
                            <i class='bx bx-chevron-right toggler'></i>
                        </a>
                        <div class="treeview {{ $section == 'Leads' ? 'active' : ''}}">
                            <a href="{{route('dashboard.clients.index')}}" class="nav_link {{ $section == 'Leads' ? 'active' : ''}}">
                                <i class='bx bx-user-circle nav_icon' ></i>
                                <span class="nav_name">{{__('crud.leads.title')}}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>

    @yield('content_with_padding')
    <div class="loader-overlay" id="loader-overlay">
        <div class="loader"></div>
    </div>
    <div class="content height-100">
        <div class="main" id="app">
            @yield('content')
        </div>
    </div>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script src="{{asset('vendor/spectrum/spectrum.min.js')}}"></script>
<script src='{{asset('js/dragula.min.js')}}'></script>
<script src="{{ asset('js/select2.full.min.js')}}"></script>
<script src="{{ asset('js/main.js')}}"></script>
<script src="{{ mix('js/app.js') }}"></script>
@include('partials.datatables.trans_script')

{{-- DataTables --}}
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js" crossorigin="anonymous"></script>
<script src="{{asset('js/datatables/buttons.html5.min.js')}}"></script>
<script src="{{asset('js/datatables/dataTables.buttons.min.js')}}"></script>
<script src="https://cdn.datatables.net/buttons/1.1.0/js/buttons.flash.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.1.0/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="{{asset('js/datatables/dataTables.select.min.js')}}"></script>

@include('partials.attendance_script')
@include('partials.datatables.scripts')
@yield('scripts')
</html>
