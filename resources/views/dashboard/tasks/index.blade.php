@extends('layouts.dashboard', ['section' => 'Tasks'])

@section('content')
    <div class="mt-2">
        <span class="h2 d-inline-block mt-1">
            <b>Tasks</b><span class="text-muted">({{count($tasks)}})</span>
        </span>
        <a class="btn btn-primary d-inline-block ms-3 align-top" href="{{route('dashboard.tasks.create')}}">
            <span class="px-4"><i class="bx bx-plus mt-1"></i>Add new Task</span>
        </a>
    </div>

    <div class="table-actions">
        <div class="row">
            <div class="col-md-5">
                <div class="d-flex justify-content-start mt-4">
                    <a class="ms-3 text-decoration-none {{ request('status') == null ? 'text-black' : '' }}" href="{{ route('dashboard.tasks.index') }}"><b>All</b> ({{ $counters['total'] }})</a>
                    <a class="ms-3 text-decoration-none {{ request('status') == 1 ? 'text-black' : '' }}" href="?status=1"><b>In progress</b> ({{ $counters['in_progress'] }})</a>
                    <a class="ms-3 text-decoration-none {{ request('status') == 2 ? 'text-black' : '' }}" href="?status=2"><b>Completed</b> ({{ $counters['completed'] }})</a>
                    <a class="ms-3 text-decoration-none {{ request('status') == 3 ? 'text-black' : '' }}" href="?status=3"><b>Not Started</b> ({{ $counters['not_started'] }})</a>
                </div>
            </div>
            <div class="col-md-7 mt-1">
                <div class="justify-content-end">
                    <div class="row">
                        <div class="col-md-1 offset-md-3"></div>
                        <div class="col-md-6 col-sm-12 mt-3">
                            <div class="search-container w-100">
                                <i class="bx bx-search search-icon"></i>
                                <input type="text" class="search-input" id="search-input" name="search_input"
                                    placeholder="Search Tasks">
                            </div>
                        </div>
                        <div class="col-md-2 col-12 mt-3 text-end">
                            <button class="btn btn-change-view active" data-bs-toggle="tooltip" data-bs-title="List View"
                                data-bs-placement="top">
                                <i class='bx bx-list-ul'></i>
                            </button>
                            <button class="btn btn-change-view" data-bs-toggle="tooltip" data-bs-title="Board View"
                                data-bs-placement="top">
                                <i class='bx bx-chalkboard'></i>
                            </button>
                            <button class="btn btn-change-view" data-bs-toggle="tooltip" data-bs-title="Card View"
                                data-bs-placement="top">
                                <i class='bx bxs-dashboard'></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive mt-5">
        <table class="table table-borderless table-hover">
            <thead class="border-top border-bottom">
                <tr>
                    <th scope="col" class="min-width-0"></th>
                    <th scope="col">TITLE</th>
                    <th scope="col">ASSIGNESS</th>
                    <th scope="col">START DATE</th>
                    <th scope="col">DUE DATE</th>
                    <th scope="col">STATUS</th>
                    <th scope="col">PRIORITY</th>
                    <th scope="col">HOURS LOGGED</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $task)
                    <tr class="table-entry align-middle border-bottom">
                        <td class="text-nowrap">
                            @if($task->subtasks()->count())
                                <a href="#" class="text-decoration-none text-dark me-2" onclick="toggleSubTasks({{$task->id}})">
                                    <i class='bx bxs-right-arrow' id="toggler-{{$task->id}}"></i>
                                </a>
                            @endif

                            @if(in_array($task->id, auth()->user()->activeTaskTimers()->pluck('task_id')->toArray()))
                                <a href="{{route('dashboard.task-clock-out', $task->id)}}" class="btn btn-attendance-task">
                                    <i class='bx bx-stop-circle align-middle'></i>
                                </a>
                            @else
                                <a href="{{route('dashboard.task-clock-in', $task->id)}}" class="btn btn-attendance-task">
                                    <i class='bx bx-play-circle align-middle'></i>
                                </a>
                            @endif
                        </td>
                        <td><a href="{{route('dashboard.tasks.show', $task->id)}}" class="text-decoration-none"><b>{{ $task->title }}</b></a></td>
                        <td>
                            <div class="avatar-group">
                                @foreach ($task->users()->limit(3)->get() as $task_user)
                                    <div class="avatar avatar-s">
                                        <img src="{{ $task_user->profile_img }}" alt="avatar" class="rounded-circle">
                                    </div>
                                @endforeach

                               @if($task->users()->count() > 3)
                                    <div class="avatar avatar-s">
                                        <div class="avatar-name rounded-circle">
                                            <span>+{{ $task->users()->count() - 3 }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="text-muted">{{ $task->start_date ? $task->start_date->format('d/m/Y') : '-' }}</td>
                        <td class="{{ $task->is_overdue ? 'text-danger' : 'text-muted' }}">{{ $task->duedate ? $task->duedate->format('d/m/Y') : '-' }}</td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm dropdown-toggle" style="{{$task->status->styles}}" data-bs-toggle="dropdown" aria-expanded="false">
                                  <b>{{$task->status->title}}</b>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-sm">
                                    @foreach ($statuses as $status)
                                        <li class="border-top"><a class="dropdown-item" href="{{route('dashboard.tasks.update-status', [$task->id, $status->id])}}"><span class="badge" style="{{$status->styles}}">{{$status->title}}</span></a></li>
                                    @endforeach
                                </ul>
                              </div>
                        </td>
                        <td><span class="badge bg-{{$task->priority}}">{{ucfirst($task->priority)}}</span></td>
                        <td class="text-muted"><span class="{{$task->limit_hours && $task->total_hours > $task->limit_hours ? 'text-danger' : ''}}">{{$task->total_hours}}h</span> / <b>{{$task->limit_hours ?? '-'}}h</b> </td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-options" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class='bx bx-dots-horizontal-rounded'></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li><a class="dropdown-item" href="{{route('dashboard.tasks.show', $task->id)}}"><i class='bx bx-show' ></i> View</a></li>
                                    <li><a class="dropdown-item" href="{{route('dashboard.tasks.edit', $task->id)}}"><i class='bx bx-edit' ></i> Edit</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{route('dashboard.tasks.destroy', $task->id)}}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="dropdown-item text-danger"><i class='bx bx-trash' ></i> Remove</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>

                    @foreach ($task->subtasks as $subtask)
                        <tr class="table-entry align-middle border-bottom subtasks-{{$task->id}} d-none">
                            <td class="text-nowrap ps-5">
                                @if(in_array($subtask->id, auth()->user()->activeTaskTimers()->pluck('task_id')->toArray()))
                                    <a href="{{route('dashboard.task-clock-out', $subtask->id)}}" class="btn btn-attendance-task">
                                        <i class='bx bx-stop-circle align-middle'></i>
                                    </a>
                                @else
                                    <a href="{{route('dashboard.task-clock-in', $subtask->id)}}" class="btn btn-attendance-task">
                                        <i class='bx bx-play-circle align-middle'></i>
                                    </a>
                                @endif
                            </td>
                            <td><a href="{{route('dashboard.tasks.show', $subtask->id)}}" class="text-decoration-none"><b>{{ $subtask->title }}</b></a></td>
                            <td>
                                <div class="avatar-group">
                                    @foreach ($subtask->users()->limit(3)->get() as $task_user)
                                        <div class="avatar avatar-s">
                                            <img src="{{ $task_user->profile_img}}" alt="avatar" class="rounded-circle">
                                        </div>
                                    @endforeach

                                @if($subtask->users()->count() > 3)
                                        <div class="avatar avatar-s">
                                            <div class="avatar-name rounded-circle">
                                                <span>+{{ $subtask->users()->count() - 3 }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="text-muted">{{ $subtask->start_date ? $subtask->start_date->format('d/m/Y') : '-' }}</td>
                            <td class="{{ $subtask->is_overdue ? 'text-danger' : 'text-muted' }}">{{ $subtask->duedate ? $subtask->duedate->format('d/m/Y') : '-' }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm dropdown-toggle" style="{{$subtask->status->styles}}" data-bs-toggle="dropdown" aria-expanded="false">
                                    <b>{{$subtask->status->title}}</b>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-sm">
                                        @foreach ($statuses as $status)
                                            <li class="border-top"><a class="dropdown-item" href="{{route('dashboard.tasks.update-status', [$subtask->id, $status->id])}}"><span class="badge" style="{{$status->styles}}">{{$status->title}}</span></a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </td>
                            <td><span class="badge bg-{{$subtask->priority}}">{{ucfirst($subtask->priority)}}</span></td>
                            <td class="text-muted"><span class="{{$subtask->limit_hours && $subtask->total_hours > $subtask->limit_hours ? 'text-danger' : ''}}">{{$subtask->total_hours}}h</span> / <b>{{$subtask->limit_hours ?? '-'}}h</b> </td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-options" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class='bx bx-dots-horizontal-rounded'></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <li><a class="dropdown-item" href="{{route('dashboard.tasks.show', $subtask->id)}}"><i class='bx bx-show' ></i> View</a></li>
                                        <li><a class="dropdown-item" href="{{route('dashboard.tasks.edit', $subtask->id)}}"><i class='bx bx-edit' ></i> Edit</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{route('dashboard.tasks.destroy', $subtask->id)}}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="dropdown-item text-danger"><i class='bx bx-trash' ></i> Remove</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endforeach

                @if(!count($tasks))
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <span class="text-muted">No tasks found!</span>
                        </td>
                    </tr>
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="9" class="py-4 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                {{ count($tasks) }} to {{$pagination['take'] ?? $counters['total']}} items of <b>{{ $counters['total'] }}</b>
                                @if(request('page') != 'all')
                                    <span class="ms-4">
                                        <a href="?page=all" class="text-decoration-none">View All <i class='bx bx-chevron-right'></i></a>
                                    </span>
                                @endif
                            </div>
                            <div>
                                @if(request('page') != 'all')
                                    <ul class="pagination m-0">
                                        <li class="page-item {{ $pagination['pages'] > 1 && request('page', 1) > 1 ? '' : 'disabled'}}">
                                            <a class="page-link arrow" href="?page={{(request('page', 1) - 1) . (request('status') ? '&status=' . request('status') : '')}}"><i class='bx bx-chevron-left' ></i></a>
                                        </li>
                                        @for ($page = 1; $page <= $pagination['pages']; $page++)
                                            <li class="page-item" aria-current="page">
                                                <a class="page-link {{request('page', 1) == $page ? 'active' : ''}}" href="?page={{$page . (request('status') ? '&status=' . request('status') : '')}}"><b>{{$page}}</b></a>
                                            </li>
                                        @endfor
                                        <li class="page-item {{ $pagination['pages'] > 1 && request('page', 1) != $pagination['pages'] ? '' : 'disabled'}}">
                                            <a class="page-link arrow" href="?page={{(request('page', 1) + 1) . (request('status') ? '&status=' . request('status') : '')}}"><i class='bx bx-chevron-right'></i></a>
                                        </li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.table-responsive').on('show.bs.dropdown', function() {
                $('.table-responsive').css("overflow", "inherit");
            });

            $('.table-responsive').on('hide.bs.dropdown', function() {
                $('.table-responsive').css("overflow", "auto");
            })
        });

        function toggleSubTasks($taskId) {
            $('.subtasks-' + $taskId).toggleClass('d-none');
            $('#toggler-' + $taskId).toggleClass('bx-rotate-90');
        }
    </script>
@endsection
