<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="locale" content="{{ app()->getLocale() }}">

    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('img/favicons/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('img/favicons/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('img/favicons/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('img/favicons/site.webmanifest')}}">
    <link rel="mask-icon" href="{{asset('img/favicons/safari-pinned-tab.svg')}}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <title>{{ trans('global.site_title') }} | {{ trans('global.' . strtolower($section)) }}</title>
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

    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/board.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/navbar.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/dragula.min.css') }}" rel="stylesheet" />
    <link href="{{asset('vendor/spectrum/spectrum.min.css')}}" rel="stylesheet">
    @yield('styles')


    <script src="{{ mix('js/app.js') }}"></script>
</head>

@php
    use Jenssegers\Agent\Facades\Agent;

    $isPhone = Agent::isMobile();
@endphp

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
            <button class="btn {{ auth()->user()->is_clock_in ? 'btn-primary' : 'btn-secondary' }} me-3 d-inline-block timer" id="timer" data-bs-toggle="tooltip" data-bs-title="Attendance" data-bs-placement="bottom">
                <i class='bx bx-timer'></i> {{ auth()->user()->timer }}
            </button>
            <div class="d-inline-block align-middle">
                <a href="#" class="text-dark me-4 navIconBtn"><i class="bx bx-search" style="font-size: 23px;"></i></a>
                <div class="dropdown d-inline-block navIconBtn">
                    <a class="text-dark text-decoration-none me-4 position-relative" href="#" role="button" id="dropdownTimers" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bx bxs-time-five" style="font-size: 23px;"></i>

                        @if (auth()->user()->activeTaskTimers()->count() > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ auth()->user()->activeTaskTimers()->count() }}
                            </span>
                        @endif
                    </a>

                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end" aria-labelledby="dropdownTimers">
                        <div class="py-2 border-bottom">
                            <span class="px-3 h6"><i class="bx bxs-time-five"></i> Active Timers </span>
                        </div>

                        @foreach (auth()->user()->activeTaskTimers as $timer)
                                <div class="px-2 card-notification">
                                    <div style="font-size: 13px;">
                                        <div class="row pt-3">
                                            <div class="col-9">
                                                <a href="{{route('dashboard.tasks.show', $timer->task_id)}}" class="text-decoration-none text-dark">
                                                <span class="h6"><b>{{$timer->task->title}}</b></span>

                                                <p class="mt-1"><b>{{$timer->created_at->format('h:i A')}}</b> <span class="text-muted">{{$timer->created_at->format('F j, Y')}}</span></p>
                                            </a>
                                            </div>
                                            <div class="col-3 text-center">
                                                <a href="{{route('dashboard.task-clock-out', $timer->task_id)}}" class="btn btn-outline-secondary d-inline-block">
                                                    <i class='bx bx-stop-circle align-middle'></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        @endforeach

                        @if (auth()->user()->activeTaskTimers()->count() == 0)
                            <div class="text-center py-3">
                                <span class="text-muted">No active timers</span>
                            </div>
                        @endif
                    </ul>
                </div>
                <a href="{{route('dashboard.notes.index')}}" class="text-dark me-4 navIconBtn"><i class="bx bxs-note" style="font-size: 23px;"></i></a>
                <div class="dropdown d-inline-block navIconBtn">
                    <a class="text-dark text-decoration-none me-4 position-relative" href="#" role="button" id="dropdownNotifications" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bx bxs-bell" style="font-size: 23px;"></i>
                        @if (auth()->user()->notifications()->unread()->count() > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ auth()->user()->notifications()->unread()->count() }}
                            </span>
                        @endif
                    </a>

                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end" aria-labelledby="dropdownNotifications">
                        <div class="py-2 border-bottom">
                            <span class="px-3 h6"><i class="bx bxs-bell"></i> Notifications </span>
                        </div>

                        <div class="scrollbar" style="max-height: 300px;">
                            @foreach (auth()->user()->notifications()->unread()->orderBy('created_at', 'desc')->get() as $notification)
                                <a href="{{$notification->routeUrl}}" class="text-decoration-none text-dark">
                                    <div class="px-2 card-notification">
                                        <div style="font-size: 13px;">
                                            <div class="row pt-3">
                                                <div class="col-md-3 text-center">
                                                    <img src="{{asset($notification->user->profile_img)}}" class="img-fluid rounded-circle border" width="50" alt="" style="height: 45px; width: 45px;">
                                                </div>
                                                <div class="col-md-9">
                                                    <span class="h6"><b>{{$notification->user->name}}</b></span>
                                                    <p class="text-muted mb-0"><i class="bx {{$notification->icon}}"></i> {{$notification->title}}</p>
                                                    <p class="mt-1"><b>{{$notification->created_at->format('h:i A')}}</b> <span class="text-muted">{{$notification->created_at->format('F j, Y')}}</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        @if (auth()->user()->notifications()->unread()->count() == 0)
                            <div class="text-center py-3">
                                <span class="text-muted">No notifications</span>
                            </div>
                        @else
                            <div class="text-center border-top pt-2">
                                <a href="{{route('dashboard.notifications.read')}}" class="text-decoration-none">
                                    <span>Mark all as read</span>
                                </a>
                            </div>
                        @endif
                    </ul>
                </div>
                <a href="{{route('logout')}}" class="text-dark me-1"><i class="bx bx-power-off" style="font-size: 23px;"></i></a>
            </div>
        </div>
    </header>
    <div class="l-navbar {{$isPhone ? '' : 'show'}}" id="nav-bar">
        <nav class="nav">
            <div class="scrollbar">
                <a href="#" class="nav_logo"><img src="{{asset('img/LogoLetters.png')}}" width="175px"></a>
                <div class="nav_list">
                    <hr>
                    <a href="{{route('dashboard.index')}}" class="nav_link {{ $section == 'Dashboard' ? 'active' : ''}}">
                        <i class='bx bx-home-alt nav_icon'></i>
                        <span class="nav_name">{{__('global.dashboard')}}</span>
                    </a>
                    <hr>
                    <div class="nav_item has-treeview">
                        <a href="#" class="nav_link has_submenu {{ $section == 'Clients' || $section == 'Companies' ? 'active' : ''}}">
                            <div>
                                <i class='bx bx-buildings nav_icon'></i>
                                <span class="nav_name">Clients</span>
                            </div>
                            <i class='bx bx-chevron-right toggler'></i>
                        </a>
                        <div class="treeview {{ $section == 'Clients' || $section == 'Companies' || $section == 'Invoices' ? 'active' : ''}}">
                            <a href="{{route('dashboard.clients.index')}}" class="nav_link {{ $section == 'Clients' ? 'active' : ''}}">
                                <i class='bx bx-buildings nav_icon'></i>
                                <span class="nav_name">Clients</span>
                            </a>
                            <hr>
                            <a href="{{route('dashboard.companies.index')}}" class="nav_link {{ $section == 'Companies' ? 'active' : ''}}">
                                <i class='bx bx-building-house nav_icon'></i>
                                <span class="nav_name">{{__('crud.companies.title')}}</span>
                            </a>
                            <hr>
                            <a href="{{route('dashboard.invoices.index')}}" class="nav_link {{ $section == 'Invoices' ? 'active' : ''}}">
                                <i class='bx bx-file nav_icon'></i>
                                <span class="nav_name">{{__('crud.invoices.title')}}</span>
                            </a>
                        </div>
                    </div>
                    <hr>
                    <div class="nav_item has-treeview">
                        <a href="#" class="nav_link has_submenu {{ $section == 'Leaves' || $section == 'Attendance' || $section == 'Holiday' || $section == 'Hours_Reports' ? 'active' : ''}}">
                            <div>
                                <i class='bx bx-group nav_icon'></i>
                                <span class="nav_name ms-4">HR</span>
                            </div>
                            <i class='bx bx-chevron-right toggler'></i>
                        </a>
                        <div class="treeview {{ $section == 'Leaves' || $section == 'Attendance' ? 'active' : ''}}">
                            <a href="{{route('dashboard.leaves.index')}}" class="nav_link {{ $section == 'Leaves' ? 'active' : ''}}">
                                <i class='bx bxs-plane-take-off nav_icon'></i>
                                <span class="nav_name">Leaves</span>
                            </a>
                            <hr>
                            <a href="#" class="nav_link {{ $section == 'Attendance' ? 'active' : ''}}">
                                <i class='bx bx-user-plus nav_icon'></i>
                                <span class="nav_name">Attendance</span>
                            </a>
                            <hr>
                            <a href="#" class="nav_link {{ $section == 'Holiday' ? 'active' : ''}}">
                                <i class='bx bx-user-plus nav_icon'></i>
                                <span class="nav_name">Holiday</span>
                            </a>
                            <hr>
                            <a href="#" class="nav_link {{ $section == 'Hours_Reports' ? 'active' : ''}}">
                                <i class='bx bx-user-plus nav_icon'></i>
                                <span class="nav_name">Hours Reports</span>
                            </a>
                        </div>
                    </div>
                    <hr>
                    <div class="nav_item has-treeview">
                        <a href="#" class="nav_link has_submenu {{ $section == 'Tasks' || $section == 'Projects' ? 'active' : ''}}">
                            <div>
                                <i class='bx bx-briefcase nav_icon'></i>
                                <span class="nav_name ms-4">Work</span>
                            </div>
                            <i class='bx bx-chevron-right toggler'></i>
                        </a>
                        <div class="treeview {{ $section == 'Tasks' || $section == 'Projects' ? 'active' : ''}}">
                            <a href="{{route('dashboard.projects.index')}}" class="nav_link {{ $section == 'Projects' ? 'active' : ''}}">
                                <i class='bx bxs-dashboard nav_icon'></i>
                                <span class="nav_name">Projects</span>
                            </a>
                            <hr>
                            <a href="{{route('dashboard.tasks.index')}}" class="nav_link {{ $section == 'Tasks' ? 'active' : ''}}">
                                <i class='bx bx-clipboard nav_icon'></i>
                                <span class="nav_name">{{__('crud.tasks.title')}}</span>
                            </a>
                        </div>
                    </div>
                    <hr>
                    <div class="nav_item has-treeview">
                        <a href="#" class="nav_link has_submenu {{ $section == 'Assignments' || $section == 'Items' ? 'active' : ''}}">
                            <div>
                                <i class='bx bx-package nav_icon'></i>
                                <span class="nav_name ms-4">Inventory</span>
                            </div>
                            <i class='bx bx-chevron-right toggler'></i>
                        </a>
                        <div class="treeview {{ $section == 'Assignments' || $section == 'Items' ? 'active' : ''}}">
                            <a href="{{route('dashboard.inventories.index')}}" class="nav_link {{ $section == 'Items' ? 'active' : ''}}">
                                <i class='bx bx-desktop nav_icon'></i>
                                <span class="nav_name">Items</span>
                            </a>
                            <hr>
                            <a href="{{route('dashboard.assignments.index')}}" class="nav_link {{ $section == 'Assignments' ? 'active' : ''}}">
                                <i class='bx bx-book-add nav_icon'></i>
                                <span class="nav_name">Assignments</span>
                            </a>
                        </div>
                    </div>
                    <hr>
                    <div class="nav_item has-treeview">
                        <a href="#" class="nav_link has_submenu {{ $section == 'Statuses' || $section == 'Labels' || $section == 'Leave_Types' ? 'active' : ''}}">
                            <div>
                                <i class='bx bx-cog nav_icon'></i>
                                <span class="nav_name ms-4">Configuration</span>
                            </div>
                            <i class='bx bx-chevron-right toggler'></i>
                        </a>
                        <div class="treeview {{ $section == 'Statuses' || $section == 'Labels' || $section == 'Leave_Types' || $section == 'GlobalConfigurations' ? 'active' : ''}}">
                            <a href="{{route('dashboard.global-configurations.index')}}" class="nav_link {{ $section == 'GlobalConfigurations' ? 'active' : ''}}">
                                <i class='bx bx-globe nav_icon'></i>
                                <span class="nav_name">{{__('crud.globalConfigurations.title')}}</span>
                            </a>
                            <hr>
                            <a href="{{route('dashboard.statuses.index')}}" class="nav_link {{ $section == 'Statuses' ? 'active' : ''}}">
                                <i class='bx bx-cylinder nav_icon'></i>
                                <span class="nav_name">{{__('crud.status.title')}}</span>
                            </a>
                            <hr>
                            <a href="{{route('dashboard.labels.index')}}" class="nav_link {{ $section == 'Labels' ? 'active' : ''}}">
                                <i class='bx bx-label nav_icon'></i>
                                <span class="nav_name">{{__('crud.labels.title')}}</span>
                            </a>
                            <hr>
                            <a href="{{route('dashboard.leaveTypes.index')}}" class="nav_link {{ $section == 'Leave_Types' ? 'active' : ''}}">
                                <i class='bx bxs-plane-alt nav_icon'></i>
                                <span class="nav_name">{{__('crud.leaveTypes.title')}}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>

    @yield('content_with_padding')
    <div class="content height-100">
        <div class="main" id="app">
            @yield('content')
        </div>
    </div>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<script src='{{asset('js/dragula.min.js')}}'></script>
<script
src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
</script>
@include('partials.attendance_script')
<script src="{{ asset('js/select2.full.min.js')}}"></script>
<script src="{{ asset('js/main.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script src="{{asset('vendor/spectrum/spectrum.min.js')}}"></script>
@yield('scripts')
</html>
