@extends('layouts.dashboard', ['section' => 'Dashboard'])

@section('styles')
    @parent
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" crossorigin=""/>
@endsection

@section('content')
    <div class="pb-4">
        <div class="row">
            <div class="col-md-8 col-sm-12">
                <h3 class=""><span class="text-muted">{{__('global.welcome')}}</span><b> {{ $user->name }}</b></h3>
            </div>

            <div class="col-md-4 col-sm-12">
                <div class="row mt-3">
                    <div class="col-6 col-md-7 week-time-container">
                        <div class="d-inline-block align-middle text-center me-4">
                            <span class="h5"><b>{{ $now_hour }}</b></span><br>
                            <span>{{ $weekday }}</span>
                        </div>
                    </div>
                    <div class="col-6 col-md-5 clock-in-container">
                        @if ($user->is_clock_in)
                            <a class="btn btn-danger btn-lg" href="{{ route('dashboard.user-clock-out') }}">
                                <i class='bx bx-log-out-circle'></i> {{__('crud.dashboard.fields.clock_out')}}
                            </a>
                        @else
                            <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#clockIn-modal">
                                <i class='bx bx-log-in-circle'></i> {{__('crud.dashboard.fields.clock_in')}}
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-5 col-md-4 col-lg-3 text-center">
                                            <img src="{{ $user->profile_url }}" style="object-fit:cover" alt="" class="rounded border" width="100px"
                                                height="100px">
                                        </div>
                                        <div class="col-7 col-md-8 col-lg-9">
                                            <h5 class="mb-0"><b>{{ $user->name }}</b> <span class="text-muted">ID:  {{ $user->code }}</span></h5>

                                            @if ($user->attendanceTemplate)
                                                <span class="badge" style="{{$user->attendanceTemplate->styles}}">{{$user->attendanceTemplate->name}}</span>
                                            @endif

                                            <p class="mt-1">{{ $user->role?->name }} - {{ $user->department?->name }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="d-flex">
                                                <i class='bx bx-clipboard text-primary' style="font-size: 35px"></i>
                                                <div class="ms-2">
                                                    <div class="d-flex align-items-end">
                                                        <h2 class="mb-0 me-2">{{ $counters['tasks']['pending'] }}</h2>
                                                        <span class="fs-7 fw-semibold text-body">{{__('global.pending')}}</span>
                                                    </div>
                                                    <p class="text-body-secondary fs-9 mb-0">{{__('global.tasks')}}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="d-flex">
                                                <i class='bx bxs-calendar-x text-danger' style="font-size: 35px"></i>
                                                <div class="ms-2">
                                                    <div class="d-flex align-items-end text-center">
                                                        <h2 class="mb-0 me-2">{{ $counters['tasks']['overdue'] }}</h2>
                                                        <span class="fs-7 fw-semibold text-body">{{__('global.overdue')}}</span>
                                                    </div>
                                                    <p class="text-body-secondary fs-9 mb-0">{{__('global.tasks')}}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="d-flex">
                                                <i class='bx bxs-dashboard text-warning' style="font-size: 35px"></i>
                                                <div class="ms-2">
                                                    <div class="d-flex align-items-end text-center">
                                                        <h2 class="mb-0 me-2">{{ $counters['projects']['active'] }}</h2>
                                                        <span class="fs-7 fw-semibold text-body">{{__('global.pending')}}</span>
                                                    </div>
                                                    <p class="text-body-secondary fs-9 mb-0">{{__('global.projects')}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-6">
                                        <div class="card dashboard-card mt-3">
                                            <div class="card-body" style="position: relative;">
                                                <p class="mb-0">{{__('global.tasks')}}</p>
                                                <div class="mt-3">
                                                    <div class="d-inline-block me-4">
                                                        <h3 class="mb-0 text-primary"><b>{{ $counters['tasks']['pending'] }}</b></h3>
                                                        <span class="text-muted">{{__('global.pending')}}</span>
                                                    </div>
                                                    <div class="d-inline-block ms-5">
                                                        <h3 class="mb-0 text-danger"><b>{{ $counters['tasks']['overdue'] }}</b></h3>
                                                        <span class="text-muted">{{__('global.overdue')}}</span>
                                                    </div>
                                                </div>
                                                <span class="icon"><i class='bx bx-clipboard'></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card dashboard-card mt-3">
                                            <div class="card-body" style="position: relative;">
                                                <p class="mb-0">{{__('global.projects')}}</p>
                                                <div class="mt-3">
                                                    <div class="d-inline-block me-4">
                                                        <h3 class="mb-0 text-primary"><b>{{ $counters['projects']['active'] }}</b></h3>
                                                        <span class="text-muted">{{__('global.pending')}}</span>
                                                    </div>
                                                    <div class="d-inline-block ms-5">
                                                        <h3 class="mb-0 text-danger"><b>{{ $counters['projects']['overdue'] }}</b></h3>
                                                        <span class="text-muted">{{__('global.overdue')}}</span>
                                                    </div>
                                                </div>
                                                <span class="icon"><i class='bx bxs-dashboard'></i></span>
                                            </div>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-5 col-sm-12">
                <div class="row mt-3">
                    <div class="card px-0">
                        <div class="card-header p-4 border-0">
                            <div class="row">
                                <div class="col-12">
                                    <div>
                                        <h4 class="mb-0"><b><i class='bx bx-time-five' ></i> Week Schedule</b></h4>
                                        <p class="text-muted mb-0">Weekdays schedule times</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            @foreach ($weekdays as $weekday)
                                <div class="row py-2 border-bottom px-4">
                                    <div class="col-4 mt-2">
                                        <span>{{ $weekday }}</span>
                                    </div>
                                    <div class="col-4 ">
                                        @php
                                            $schedule = $user->attendanceTemplate ? $user->attendanceTemplate->getDaySchedule($weekday) : '00:00 - 00:00';
                                        @endphp
                                        <span class="badge py-2 px-3 mt-1 ms-2 text-dark bg-white">{{$schedule}}</span>
                                    </div>
                                    <div class="col-4 mt-1 text-end">
                                        @if ($user->attendanceTemplate && $schedule != '00:00 - 00:00')
                                            <span class="badge" style="{{$user->attendanceTemplate->styles}}">{{$user->attendanceTemplate->name}}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="card px-0">
                        <div class="card-header p-4 border-0">
                            <div class="row">
                                <div class="col-12">
                                    <div>
                                        <h4 class="mb-0"><b><i class='bx bxs-plane-take-off'></i> On Leave Today</b></h4>
                                        <p class="text-muted mb-0">All employee today leaves</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            @foreach ($leaves as $leave)
                            <div class="row border-bottom">
                                <div class="col-6">
                                    @include('partials.users.details', ['user' => $leave->user, 'limit' => 15])
                                </div>
                                <div class="col-6">
                                    <span class="badge py-2 px-3 mt-4 ms-2" style="{{$leave->leaveType->styles}}">{{$leave->leaveType->name}}</span>
                                </div>
                            </div>
                            @endforeach
                            @if (!count($leaves))
                                <div class="row">
                                    <div class="col-12 text-center py-5">
                                        <span class="text-muted">No Leaves today!</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="card px-0">
                        <div class="card-header p-4 border-0">
                            <div class="row">
                                <div class="col-12">
                                    <div>
                                        <h4 class="mb-0"><b><i class='bx bx-cake'></i> Birthdays</b></h4>
                                        <p class="text-muted mb-0">All employee birthdays</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @foreach ($birthdays as $birthday)
                            <div class="row border-bottom">
                                <div class="col-7">
                                    @include('partials.users.details', ['user' => $birthday, 'limit' => 15])
                                </div>
                                <div class="col-2">
                                    <span class="badge bg-primary align-baseline py-2 px-3 mt-4"><i class='bx bx-cake'></i> {{ $birthday->birthday_date->format('d M')}}</span>
                                </div>
                                <div class="col-3">
                                    @if($days = $birthday->birthday_date->diffInDays(now()))
                                        <span class="badge bg-secondary py-2 px-3 mt-4 ms-2">in {{ $days }} days</span>
                                    @else
                                        <span class="badge bg-success py-2 px-3 mt-4 ms-2">Today</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                            @if (!count($birthdays))
                                <div class="row">
                                    <div class="col-12 text-center py-5">
                                        <span class="text-muted">No birthdays found!</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="row">
                    <div class="col-12 mt-3">
                        <div class="card">
                            <div class="card-header p-4 border-0">
                                <div class="row">
                                    <div class="col-12">
                                        <div>
                                            <h4 class="mb-0"><b><i class='bx bx-clipboard'></i> {{__('global.to_do')}}</b></h4>
                                            <p class="text-muted mb-0">{{__('global.tasks')}} {{__('global.assigned_me')}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-borderless table-hover">
                                        <thead class="border-bottom">
                                            <tr>
                                                <th scope="col" class="min-width-0"></th>
                                                <th scope="col">{{__('crud.tasks.fields.title')}}</th>
                                                <th scope="col">{{__('crud.tasks.fields.start_date')}}</th>
                                                <th scope="col">{{__('crud.tasks.fields.due_date')}}</th>
                                                <th scope="col">{{__('crud.tasks.fields.status')}}</th>
                                                <th scope="col">{{__('crud.tasks.fields.priority')}}</th>
                                                <th scope="col"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tasks as $task)
                                                <tr class="table-entry align-middle border-bottom">
                                                    <td class="text-nowrap">
                                                        @if (in_array($task->id, auth()->user()->activeTaskTimers()->pluck('task_id')->toArray()))
                                                            <a href="{{ route('dashboard.task-clock-out', $task->id) }}"
                                                                class="btn btn-attendance-task">
                                                                <i class='bx bx-stop-circle align-middle'></i>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('dashboard.task-clock-in', $task->id) }}"
                                                                class="btn btn-attendance-task">
                                                                <i class='bx bx-play-circle align-middle'></i>
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('dashboard.tasks.show', $task->id) }}"
                                                            class="text-decoration-none"><b>{{ $task->title }}</b>
                                                        </a>

                                                        <p class="mt-0 mb-0">
                                                            @foreach ($task->labels as $label)
                                                                <span class="badge" style="{{$label->styles}}">{{$label->title}}</span>
                                                            @endforeach
                                                        </p>
                                                    </td>
                                                    <td class="text-muted">
                                                        {{ $task->start_date ? $task->start_date->format('d/m/Y') : '-' }}
                                                    </td>
                                                    <td class="{{ $task->is_overdue ? 'text-danger' : 'text-muted' }}">
                                                        {{ $task->duedate ? $task->duedate->format('d/m/Y') : '-' }}</td>
                                                    <td>
                                                        <span
                                                            class="badge" style="{{$task->status->styles}}">{{ $task->status->title }}</span>
                                                    </td>
                                                    <td><span
                                                            class="badge bg-{{ $task->priority }}">{{ ucfirst($task->priority) }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="dropdown">
                                                            <button class="btn btn-options" type="button"
                                                                id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <i class='bx bx-dots-horizontal-rounded'></i>
                                                            </button>
                                                            <ul class="dropdown-menu"
                                                                aria-labelledby="dropdownMenuButton">
                                                                <li><a class="dropdown-item"
                                                                        href="{{ route('dashboard.tasks.show', $task->id) }}"><i
                                                                            class='bx bx-show'></i> {{__('global.view')}}</a></li>
                                                                <li><a class="dropdown-item"
                                                                        href="{{ route('dashboard.tasks.edit', $task->id) }}"><i
                                                                            class='bx bx-edit'></i> {{__('global.edit')}}</a></li>
                                                                <li>
                                                                    <hr class="dropdown-divider">
                                                                </li>
                                                                <li>
                                                                    <form
                                                                        action="{{ route('dashboard.tasks.destroy', $task->id) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button class="dropdown-item text-danger"><i
                                                                                class='bx bx-trash'></i> {{__('global.remove')}}</button>
                                                                    </form>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach

                                            @if (!count($tasks))
                                                <tr>
                                                    <td colspan="7" class="text-center py-5">
                                                        <span class="text-muted">{{__('crud.tasks.no_tasks')}}</span>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="9" class="py-3 text-end">
                                                    <a href="{{ route('dashboard.tasks.index') }}">{{__('global.view_all')}} ></a>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 mt-3">
                        <div class="card pb-3 px-0">
                            <div class="card-header p-4 border-0">
                                <div class="row">
                                    <div class="col-12">
                                        <div>
                                            <h4 class="mb-0"><b><i class='bx bx-calendar'></i>{{__('crud.dashboard.fields.my_calendar')}}</b></h4>
                                            <p class="text-muted mb-0">{{__('crud.dashboard.fields.all_events')}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body scrollbar" style="max-height: 24rem">
                                @foreach ($weekdays as $idx => $wday)
                                    <div class="card rounded-0 p-0 border-0 card-header">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <span class="text-muted"><b>{{__('global.weekday.'.$wday) }}</b></span>
                                                </div>
                                                <div class="col-md-6 text-end">
                                                    <span
                                                        class="text-muted"><b>{{ now()->startOfWeek()->addDays(array_search($wday, $weekdays))->format('jS M, Y') }}</b></span>
                                                </div>
                                            </div>
                                        </div>
                                        @foreach ($events[$wday] as $event)
                                            @if (get_class($event) == 'App\Models\Leave')
                                                <div class="card border-0 rounded-0 card-event"
                                                    style="{{ $event->leaveType->styles }}" data-bs-toggle="tooltip"
                                                    data-bs-title="{{ $event->leaveType->name }}" data-bs-placement="top">
                                                    <div class="card-body p-2">
                                                        <div class="row p-0">
                                                            <div class="col-md-3">
                                                                {{__('crud.dashboard.fields.all_day')}}
                                                            </div>
                                                            <div class="col-md-9">
                                                                <i class='bx bxs-plane-take-off me-2 align-middle'
                                                                    style="font-size: 25px;"></i>
                                                                <span>{{ $event->user->name }}</span>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            @else
                                                <a class="card border-0 rounded-0 card-event bg-primary text-white text-decoration-none"
                                                    href="{{ route('dashboard.tasks.show', $event->id) }}">
                                                    <div class="card-body p-2">
                                                        <div class="row p-0">
                                                            <div class="col-md-3">
                                                                {{__('crud.dashboard.fields.all_day')}}
                                                            </div>
                                                            <div class="col-md-9">
                                                                <i class='bx bx-list-ul me-2 align-middle'
                                                                    style="font-size: 25px;"></i>
                                                                <span>{{ $event->title }}</span>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="clockIn-modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel"><i class="bx bx-time-five"></i> Clock In</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('dashboard.user-clock-in') }}" method="GET">
                    @csrf
                    <input type="hidden" name="longitude" id="longitude">
                    <input type="hidden" name="latitude" id="latitude">

                    <div class="modal-body">
                        <h4><i class="bx bxs-time-five"></i> {{ now()->format('d/m/Y H:i') }}</h4>

                        <div id="map" style="height: 500px;" class="mt-3"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary"><i class="bx bx-log-in-circle"></i> Clock In</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" crossorigin=""></script>

    <script>
        $(document).ready(function() {
            $('#clockIn-modal').on('shown.bs.modal', function () {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        var lat = position.coords.latitude;
                        var lon = position.coords.longitude;

                        var map = L.map('map').setView([lat, lon], 17);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                        }).addTo(map);

                        L.marker([lat, lon]).addTo(map)
                            .bindPopup('Tu ubicación actual')
                            .openPopup();

                        $('#latitude').val(lat)
                        $('#longitude').val(lon)
                    }, function(error) {
                        Swal.fire({
                            toast: true,
                            title: 'Error with ubication',
                            icon: 'error',
                            showConfirmButton: false,
                            position: 'top-end',
                            timer: 3000
                        });
                    });
                } else {
                    Swal.fire({
                        toast: true,
                        title: 'Geolocalización no soportada por este navegador',
                        icon: 'error',
                        showConfirmButton: false,
                        position: 'top-end',
                        timer: 3000
                    });
                }
            });
        });
    </script>
@endsection
