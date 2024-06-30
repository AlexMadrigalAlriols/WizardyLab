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
            <button class="btn {{ auth()->user()->is_clock_in ? 'btn-primary' : 'btn-secondary' }} me-3 d-inline-block timer" id="timer" data-bs-toggle="tooltip" data-bs-title="Attendance" data-bs-placement="bottom">
                <i class='bx bx-timer'></i> <span id="timerValue">{{ auth()->user()->timer }}</span>
            </button>
            <div class="d-inline-block align-middle">
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
                                                    <img src="{{asset($notification->user->profile_url)}}" class="img-fluid rounded-circle border" width="50" alt="" style="height: 45px; width: 45px;">
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
                <a href="{{route('dashboard.index')}}" class="nav_logo"><img src="{{ $portal->logo }}" width="175px"></a>
                <div class="nav_list">
                    <hr>
                    <a href="{{route('dashboard.index')}}" class="nav_link {{ $section == 'Dashboard' ? 'active' : ''}}">
                        <i class='bx bx-home-alt nav_icon'></i>
                        <span class="nav_name">{{__('global.dashboard')}}</span>
                    </a>
                    @canany(['client_view', 'company_view', 'invoice_view'])
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
                                @can('client_view')
                                    <a href="{{route('dashboard.clients.index')}}" class="nav_link {{ $section == 'Clients' ? 'active' : ''}}">
                                        <i class='bx bx-buildings nav_icon'></i>
                                        <span class="nav_name">Clients</span>
                                    </a>
                                @endcan
                                @can('company_view')
                                    <hr>
                                    <a href="{{route('dashboard.companies.index')}}" class="nav_link {{ $section == 'Companies' ? 'active' : ''}}">
                                        <i class='bx bx-building-house nav_icon'></i>
                                        <span class="nav_name">{{__('crud.companies.title')}}</span>
                                    </a>
                                @endcan
                                @can('invoice_view')
                                    <hr>
                                    <a href="{{route('dashboard.invoices.index')}}" class="nav_link {{ $section == 'Invoices' ? 'active' : ''}}">
                                        <i class='bx bx-file nav_icon'></i>
                                        <span class="nav_name">{{__('crud.invoices.title')}}</span>
                                    </a>
                                @endcan
                            </div>
                        </div>
                    @endcan
                    @canany(['leave_view', 'attendance_view', 'user_view', 'holiday_view', 'document_view'])
                        <hr>
                        <div class="nav_item has-treeview">
                            <a href="#" class="nav_link has_submenu {{ $section == 'Leaves' || $section == 'Attendance' || $section == 'Users' || $section == 'Holiday' || $section == 'Documents' ? 'active' : ''}}">
                                <div>
                                    <i class='bx bx-group nav_icon'></i>
                                    <span class="nav_name ms-4">HR</span>
                                </div>
                                <i class='bx bx-chevron-right toggler'></i>
                            </a>
                            <div class="treeview {{ $section == 'Leaves' || $section == 'Attendance' || $section == 'Users' || $section == 'Documents' ? 'active' : ''}}">
                                @can('leave_view')
                                    <a href="{{route('dashboard.leaves.index')}}" class="nav_link {{ $section == 'Leaves' ? 'active' : ''}}">
                                        <i class='bx bxs-plane-take-off nav_icon'></i>
                                        <span class="nav_name">Leaves</span>
                                    </a>
                                    <hr>
                                @endcan
                                @can('attendance_view')
                                    <a href="{{route('dashboard.attendance.index')}}" class="nav_link {{ $section == 'Attendance' ? 'active' : ''}}">
                                        <i class='bx bx-timer nav_icon'></i>
                                        <span class="nav_name">Attendance</span>
                                    </a>
                                    <hr>
                                @endcan
                                @can('holiday_view')
                                    <a href="{{route('dashboard.holiday.index')}}" class="nav_link {{ $section == 'Holiday' ? 'active' : ''}}">
                                        <i class='bx bx-user-plus nav_icon'></i>
                                        <span class="nav_name">Holiday</span>
                                    </a>
                                    <hr>
                                @endcan
                                @can('document_view')
                                    <a href="{{route('dashboard.documents.index')}}" class="nav_link {{ $section == 'Documents' ? 'active' : ''}}">
                                        <i class='bx bx-file nav_icon'></i>
                                        <span class="nav_name">Documents</span>
                                    </a>
                                    <hr>
                                @endcan
                                @can('user_view')
                                    <a href="{{route('dashboard.users.index')}}" class="nav_link {{ $section == 'Users' ? 'active' : ''}}">
                                        <i class='bx bx-user-plus nav_icon'></i>
                                        <span class="nav_name">{{__('crud.users.title')}}</span>
                                    </a>
                                @endcan
                            </div>
                        </div>
                    @endcan
                    @canany(['task_view', 'project_view'])
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
                                @can('project_view')
                                    <a href="{{route('dashboard.projects.index')}}" class="nav_link {{ $section == 'Projects' ? 'active' : ''}}">
                                        <i class='bx bxs-dashboard nav_icon'></i>
                                        <span class="nav_name">Projects</span>
                                    </a>
                                    <hr>
                                @endcan
                                @can('task_view')
                                    <a href="{{route('dashboard.tasks.index')}}" class="nav_link {{ $section == 'Tasks' ? 'active' : ''}}">
                                        <i class='bx bx-clipboard nav_icon'></i>
                                        <span class="nav_name">{{__('crud.tasks.title')}}</span>
                                    </a>
                                @endcan
                            </div>
                        </div>
                    @endcan
                    @canany(['assignment_view', 'item_view', 'expense_view'])
                        <hr>
                        <div class="nav_item has-treeview">
                            <a href="#" class="nav_link has_submenu {{ $section == 'Assignments' || $section == 'Items' || $section == 'Expenses' ? 'active' : ''}}">
                                <div>
                                    <i class='bx bx-package nav_icon'></i>
                                    <span class="nav_name ms-4">Inventory</span>
                                </div>
                                <i class='bx bx-chevron-right toggler'></i>
                            </a>
                            <div class="treeview {{ $section == 'Assignments' || $section == 'Items' || $section == 'Expenses' ? 'active' : ''}}">
                                @can('item_view')
                                    <a href="{{route('dashboard.items.index')}}" class="nav_link {{ $section == 'Items' ? 'active' : ''}}">
                                        <i class='bx bx-desktop nav_icon'></i>
                                        <span class="nav_name">Items</span>
                                    </a>
                                    <hr>
                                @endcan
                                @can('assignment_view')
                                    <a href="{{route('dashboard.assignments.index')}}" class="nav_link {{ $section == 'Assignments' ? 'active' : ''}}">
                                        <i class='bx bx-book-add nav_icon'></i>
                                        <span class="nav_name">Assignments</span>
                                    </a>
                                    <hr>
                                @endcan
                                @can('expense_view')
                                    <a href="{{route('dashboard.expenses.index')}}" class="nav_link {{ $section == 'Expenses' ? 'active' : ''}}">
                                        <i class='bx bx-dollar nav_icon'></i>
                                        <span class="nav_name">Expenses</span>
                                    </a>
                                @endcan
                            </div>
                        </div>
                    @endcan
                    @canany(['status_view', 'label_view', 'role_view', 'department_view', 'leaveType_view', 'attendanceTemplate_view', 'configuration_view'])
                        <hr>
                        <div class="nav_item has-treeview">
                            <a href="#" class="nav_link has_submenu {{ $section == 'Statuses' || $section == 'Labels' || $section == 'AttendanceTemplates' || $section == 'Leave_Types' || $section == 'Roles' || $section == 'Departments' || $section == 'GlobalConfigurations' ? 'active' : ''}}">
                                <div>
                                    <i class='bx bx-cog nav_icon'></i>
                                    <span class="nav_name ms-4">Configuration</span>
                                </div>
                                <i class='bx bx-chevron-right toggler'></i>
                            </a>
                            <div class="treeview {{ $section == 'Statuses' || $section == 'Labels' || $section == 'Roles' || $section == 'Departments' || $section == 'AttendanceTemplates' || $section == 'Leave_Types' || $section == 'GlobalConfigurations' ? 'active' : ''}}">
                                @can('configuration_view')
                                    <a href="{{route('dashboard.global-configurations.index')}}" class="nav_link {{ $section == 'GlobalConfigurations' ? 'active' : ''}}">
                                        <i class='bx bx-cog nav_icon'></i>
                                        <span class="nav_name">Configuration</span>
                                    </a>
                                    <hr>
                                @endcan
                                @can('status_view')
                                    <a href="{{route('dashboard.statuses.index')}}" class="nav_link {{ $section == 'Statuses' ? 'active' : ''}}">
                                        <i class='bx bx-cylinder nav_icon'></i>
                                        <span class="nav_name">{{__('crud.status.title')}}</span>
                                    </a>
                                    <hr>
                                @endcan
                                @can('role_view')
                                    <a href="{{route('dashboard.roles.index')}}" class="nav_link {{ $section == 'Roles' ? 'active' : ''}}">
                                        <i class='bx bx-medal nav_icon'></i>
                                        <span class="nav_name">Roles</span>
                                    </a>
                                    <hr>
                                @endcan
                                @can('department_view')
                                    <a href="{{route('dashboard.departments.index')}}" class="nav_link {{ $section == 'Departments' ? 'active' : ''}}">
                                        <i class='bx bx-folder-open nav_icon'></i>
                                        <span class="nav_name">Departments</span>
                                    </a>
                                    <hr>
                                @endcan
                                @can('label_view')
                                    <a href="{{route('dashboard.labels.index')}}" class="nav_link {{ $section == 'Labels' ? 'active' : ''}}">
                                        <i class='bx bx-label nav_icon'></i>
                                        <span class="nav_name">{{__('crud.labels.title')}}</span>
                                    </a>
                                    <hr>
                                @endcan
                                @can('leaveType_view')
                                    <a href="{{route('dashboard.leaveTypes.index')}}" class="nav_link {{ $section == 'Leave_Types' ? 'active' : ''}}">
                                        <i class='bx bxs-plane-alt nav_icon'></i>
                                        <span class="nav_name">{{__('crud.leaveTypes.title')}}</span>
                                    </a>
                                    <hr>
                                @endcan
                                @can('attendanceTemplate_view')
                                    <a href="{{route('dashboard.attendanceTemplates.index')}}" class="nav_link {{ $section == 'AttendanceTemplates' ? 'active' : ''}}">
                                        <i class='bx bx-briefcase-alt nav_icon'></i>
                                        <span class="nav_name">Jornadas</span>
                                    </a>
                                @endcan
                            </div>
                        </div>
                    @endcanany
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
