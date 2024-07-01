@extends('layouts.dashboard', ['section' => 'Tasks'])

@section('content')
    <div class="mt-2">
        <span class="h2 d-inline-block mt-1">
            <b>{{__('crud.tasks.title')}}</b><span class="text-muted">({{count($tasks)}})</span>
        </span>
        <a class="btn btn-primary d-inline-block ms-3 align-top" href="{{route('dashboard.tasks.create')}}">
            <span class="px-4"><i class="bx bx-plus mt-1"></i>{{__('crud.tasks.add_new')}}</span>
        </a>
    </div>

    <div class="table-actions">
        <div class="row">
            <div class="col-md-5">
                <div class="d-flex justify-content-start mt-4">
                    <a class="ms-3 text-decoration-none {{ request('status') == null ? 'text-black' : '' }}" href="{{ route('dashboard.tasks.index') }}"><b>{{__('global.all')}}</b> ({{ $counters['total'] }})</a>
                    <a class="ms-3 text-decoration-none {{ request('status') == 6 ? 'text-black' : '' }}" href="?status=6"><b>{{__('global.in_progress')}}</b> ({{ $counters['in_progress'] }})</a>
                    <a class="ms-3 text-decoration-none {{ request('status') == 7 ? 'text-black' : '' }}" href="?status=7"><b>{{__('global.completed')}}</b> ({{ $counters['completed'] }})</a>
                    <a class="ms-3 text-decoration-none {{ request('status') == 5 ? 'text-black' : '' }}" href="?status=5"><b>{{__('global.not_started')}}</b> ({{ $counters['not_started'] }})</a>
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
                            <a class="btn btn-change-view me-2 {{request('archived') === null || request('archived') == 0 ? 'active' : ''}}" href="?archived=0" data-bs-toggle="tooltip" data-bs-title="{{__('global.list_view')}}"
                                data-bs-placement="top">
                                <i class='bx bx-list-ul'></i>
                            </a>
                            <a class="btn btn-change-view {{request('archived') !== null && request('archived') == 1 ? 'active' : ''}}" href="?archived=1" data-bs-toggle="tooltip" data-bs-title="{{__('global.archive')}}"
                                data-bs-placement="top">
                                <i class='bx bx-box'></i>
                            </a>
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
                    <th scope="col">{{__('crud.tasks.fields.title')}}</th>
                    <th scope="col">{{__('crud.tasks.fields.assigned_to')}}</th>
                    <th scope="col">{{__('crud.tasks.fields.start_date')}}</th>
                    <th scope="col">{{__('crud.tasks.fields.due_date')}}</th>
                    <th scope="col">{{__('crud.tasks.fields.status')}}</th>
                    <th scope="col">{{__('crud.tasks.fields.priority')}}</th>
                    <th scope="col">{{__('crud.tasks.fields.hours_logged')}}</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $task)
                    @php
                        foreach ($task->subtasks()->pluck('id') as $stask_id) {
                            $active = in_array($stask_id, auth()->user()->activeTaskTimers()->pluck('task_id')->toArray());
                            break;
                        }
                    @endphp
                    <tr class="table-entry align-middle border-bottom">
                        <td class="text-nowrap">
                            @if($task->subtasks()->count())
                                <a href="#" class="text-decoration-none text-dark me-2" onclick="toggleSubTasks({{$task->id}})">
                                    <i class='bx bxs-right-arrow {{$active ? 'bx-rotate-90' : ''}}' id="toggler-{{$task->id}}"></i>
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
                        <td>
                            <a href="{{route('dashboard.tasks.show', $task->id)}}" class="text-decoration-none"><b>{{ $task->title }}</b></a>

                            <p class="mt-0 mb-0">
                                @foreach ($task->labels as $label)
                                    <span class="badge" style="{{$label->styles}}">{{$label->title}}</span>
                                @endforeach
                            </p>
                        </td>
                        <td>
                            <div class="avatar-group">
                                @foreach ($task->users()->limit(3)->get() as $task_user)
                                    <div class="avatar avatar-s">
                                        <img src="{{ $task_user->profile_url }}" alt="avatar" class="rounded-circle">
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
                                    @if(request('archived'))
                                        <li><a class="dropdown-item" href="{{route('dashboard.tasks.action.get', [$task->id, 'unarchive'])}}"><i class='bx bx-box' ></i> UnArchive</a></li>
                                    @else
                                        <li><a class="dropdown-item" href="{{route('dashboard.tasks.action.get', [$task->id, 'archive'])}}"><i class='bx bx-box' ></i> Archive</a></li>
                                    @endif
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
                        <tr class="table-entry align-middle border-bottom subtasks-{{$task->id}} {{$active ? '' : 'd-none'}}">
                            <td class="text-nowrap">
                                @if(in_array($subtask->id, auth()->user()->activeTaskTimers()->pluck('task_id')->toArray()))
                                    <a href="{{route('dashboard.task-clock-out', $subtask->id)}}" class="btn ms-5 btn-attendance-task">
                                        <i class='bx bx-stop-circle align-middle'></i>
                                    </a>
                                @else
                                    <a href="{{route('dashboard.task-clock-in', $subtask->id)}}" class="btn ms-5 btn-attendance-task">
                                        <i class='bx bx-play-circle align-middle'></i>
                                    </a>
                                @endif
                            </td>
                            <td>
                                <a href="{{route('dashboard.tasks.show', $subtask->id)}}" class="text-decoration-none"><b>{{ $subtask->title }}</b></a>

                                <p class="mt-0 mb-0">
                                    @foreach ($subtask->labels as $label)
                                        <span class="badge" style="{{$label->styles}}">{{$label->title}}</span>
                                    @endforeach
                                </p>
                            </td>
                            <td>
                                <div class="avatar-group">
                                    @foreach ($subtask->users()->limit(3)->get() as $task_user)
                                        <div class="avatar avatar-s">
                                            <img src="{{ $task_user->profile_url}}" alt="avatar" class="rounded-circle">
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
                                            <a class="page-link arrow" href="?page={{(request('page', 1) - 1) . (request('status') ? '&status=' . request('status') : '') . (request('archived') ? '&archived=' . request('archived') : '')}}"><i class='bx bx-chevron-left' ></i></a>
                                        </li>
                                        @for ($page = 1; $page <= $pagination['pages']; $page++)
                                            <li class="page-item" aria-current="page">
                                                <a class="page-link {{request('page', 1) == $page ? 'active' : ''}}" href="?page={{$page . (request('status') ? '&status=' . request('status') : '') . (request('archived') ? '&archived=' . request('archived') : '')}}"><b>{{$page}}</b></a>
                                            </li>
                                        @endfor
                                        <li class="page-item {{ $pagination['pages'] > 1 && request('page', 1) != $pagination['pages'] ? '' : 'disabled'}}">
                                            <a class="page-link arrow" href="?page={{(request('page', 1) + 1) . (request('status') ? '&status=' . request('status') : '') . (request('archived') ? '&archived=' . request('archived') : '')}}"><i class='bx bx-chevron-right'></i></a>
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
