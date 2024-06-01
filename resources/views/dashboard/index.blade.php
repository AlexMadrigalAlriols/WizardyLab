@extends('layouts.dashboard', ['section' => 'Dashboard'])

@section('content')
    <div class="pb-4">
        <div class="row">
            <div class="col-md-8 col-sm-12">
                <h3 class=""><b>Welcome {{ $user->name }}</b></h3>
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
                                <i class='bx bx-log-out-circle'></i> Clock Out
                            </a>
                        @else
                            <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#clockIn-modal">
                                <i class='bx bx-log-in-circle'></i> Clock In
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-5 col-sm-12">
                <div class="row">
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5 col-md-4 col-lg-3 text-center">
                                    <img src="{{ $user->profile_img }}" alt="" class="rounded border" width="100px"
                                        height="100px">
                                </div>
                                <div class="col-7 col-md-8 col-lg-9">
                                    <h5 class="mb-0"><b>{{ $user->name }}</b></h5>
                                    <p class="mt-1">{{ $user->role?->name }} - {{ $user->department?->name }}</p>
                                    <p class="text-muted">ID: {{ $user->code }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white">
                            <div class="row">
                                <div class="col-6">
                                    <div>
                                        <h6 class="mb-3 text-muted">Completed Tasks</h6>
                                        <h3>{{ $counters['tasks']['total'] }}</h3>
                                    </div>
                                </div>
                                <div class="col-6 text-end">
                                    <div>
                                        <h6 class="mb-3 text-muted">Projects</h6>
                                        <h3>{{ $counters['projects']['total'] }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mt-3">
                        <div class="card pb-3">
                            <div class="card-header p-4 bg-white">
                                <div class="row">
                                    <div class="col-8">
                                        <div>
                                            <h4 class="mb-0"><b><i class='bx bx-calendar'></i> My Calendar</b></h4>
                                            <p class="text-muted mb-0">All events on week</p>
                                        </div>
                                    </div>
                                    <div class="col-4">

                                    </div>
                                </div>
                            </div>
                            <div class="card-body scrollbar" style="max-height: 24rem">
                                @foreach ($weekdays as $idx => $wday)
                                    <div class="card rounded-0">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <span class="text-muted"><b>{{ $wday }}</b></span>
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
                                                                Todo el dia
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
                                                                Todo el dia
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
            <div class="col-md-7">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card dashboard-card mt-3">
                            <div class="card-body" style="position: relative;">
                                <p class="mb-0">Tasks</p>
                                <div class="mt-3">
                                    <div class="d-inline-block me-4">
                                        <h3 class="mb-0 text-primary"><b>{{ $counters['tasks']['total'] }}</b></h3>
                                        <span class="text-muted">Pending</span>
                                    </div>
                                    <div class="d-inline-block ms-5">
                                        <h3 class="mb-0 text-danger"><b>{{ $counters['tasks']['overdue'] }}</b></h3>
                                        <span class="text-muted">Overdue</span>
                                    </div>
                                </div>
                                <span class="icon"><i class='bx bx-clipboard'></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card dashboard-card mt-3">
                            <div class="card-body" style="position: relative;">
                                <p class="mb-0">Projects</p>
                                <div class="mt-3">
                                    <div class="d-inline-block me-4">
                                        <h3 class="mb-0 text-primary"><b>{{ $counters['projects']['total'] }}</b></h3>
                                        <span class="text-muted">Pending</span>
                                    </div>
                                    <div class="d-inline-block ms-5">
                                        <h3 class="mb-0 text-danger"><b>{{ $counters['projects']['overdue'] }}</b></h3>
                                        <span class="text-muted">Overdue</span>
                                    </div>
                                </div>
                                <span class="icon"><i class='bx bxs-dashboard'></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 mt-3">
                        <div class="card">
                            <div class="card-header p-4 bg-white">
                                <div class="row">
                                    <div class="col-8">
                                        <div>
                                            <h4 class="mb-0"><b><i class='bx bx-clipboard'></i> To do</b></h4>
                                            <p class="text-muted mb-0">Tasks assigned to me</p>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-end">
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="Search tasks...">
                                                <button class="btn btn-primary" type="button">
                                                    <i class="bx bx-search"></i>
                                                </button>
                                            </div>
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
                                                <th scope="col">TITLE</th>
                                                <th scope="col">START DATE</th>
                                                <th scope="col">DUE DATE</th>
                                                <th scope="col">STATUS</th>
                                                <th scope="col">PRIORITY</th>
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
                                                    <td><a href="{{ route('dashboard.tasks.show', $task->id) }}"
                                                            class="text-decoration-none"><b>{{ $task->title }}</b></a>
                                                    </td>
                                                    <td class="text-muted">
                                                        {{ $task->start_date ? $task->start_date->format('d/m/Y') : '-' }}
                                                    </td>
                                                    <td class="{{ $task->is_overdue ? 'text-danger' : 'text-muted' }}">
                                                        {{ $task->duedate ? $task->duedate->format('d/m/Y') : '-' }}</td>
                                                    <td>
                                                        <span
                                                            class="badge {{ $task->status->badge }}">{{ $task->status->title }}</span>
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
                                                                            class='bx bx-show'></i> View</a></li>
                                                                <li><a class="dropdown-item"
                                                                        href="{{ route('dashboard.tasks.edit', $task->id) }}"><i
                                                                            class='bx bx-edit'></i> Edit</a></li>
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
                                                                                class='bx bx-trash'></i> Remove</button>
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
                                                        <span class="text-muted">No tasks found!</span>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="9" class="py-3 text-end">
                                                    <a href="{{ route('dashboard.tasks.index') }}">View All ></a>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
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
                    <h5 class="modal-title" id="modalLabel">Clock In</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('dashboard.user-clock-in') }}" method="GET">
                    @csrf
                    <div class="modal-body">
                        <h4><i class="bx bxs-time-five"></i> {{ now()->format('d/m/Y H:i') }}</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Clock In</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
