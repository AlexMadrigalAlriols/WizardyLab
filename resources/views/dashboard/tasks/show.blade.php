@extends('layouts.dashboard', ['section' => 'Tasks'])

@section('content')
<div class="row h-100">
    <div class="col-md-8 border-end">
        <div class="mt-2">
            <div class="row">
                <div class="col-md-10">
                    <span class="h2 mt-1">
                        <b>{{ $task->title }}</b>
                    </span>
                    <br>
                    <span class="badge bg-{{$task->priority}} ms-2">{{ucfirst($task->priority)}}</span>
                    <span class="badge ms-2" style="{{$task->status->styles}}"><span class="px-2">{{$task->status->title}}</span></span>
                    <p class="text-muted mt-2 mb-1">{!! $task->description !!}</p>


                    @if(in_array($task->id, auth()->user()->activeTaskTimers()->pluck('task_id')->toArray()))
                        <a href="{{route('dashboard.task-clock-out', $task->id)}}" class="btn btn-outline-secondary mt-1">
                            <i class='bx bx-stop-circle align-middle'></i> <span>{{__("crud.dashboard.fields.clock_out")}}</span>
                        </a>
                    @else
                        <a href="{{route('dashboard.task-clock-in', $task->id)}}" class="btn btn-outline-secondary mt-1">
                            <i class='bx bx-play-circle align-middle'></i> <span>{{__("crud.dashboard.fields.clock_in")}}</span>
                        </a>
                    @endif

                    <button class="btn btn-secondary mt-1 ms-2" id="timerTask">
                        {{$task->timer}}
                    </button>
                </div>

                <div class="col-md-2">
                    <div class="text-end">
                        <div class="dropdown">
                            <button class="btn btn-options" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class='bx bx-dots-horizontal-rounded'></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><a class="dropdown-item" href="{{route('dashboard.tasks.edit', $task->id)}}"><i class='bx bx-edit' ></i> {{__("global.edit")}}</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{route('dashboard.tasks.destroy', $task->id)}}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="dropdown-item text-danger"><i class='bx bx-trash' ></i> {{__("global.remove")}}</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-5">
                        <div class="row">
                            <p class="h4">
                                <b><i class='bx bx-info-circle' ></i> Info</b>
                            </p>

                            <div>
                                <p><b>{{__("crud.tasks.fields.project")}}:</b> <a href="{{route('dashboard.projects.board', $task->project->id)}}" class="ms-2">{{$task->project->name ?? '-'}}</a></p>
                                <p><b>{{__("crud.tasks.fields.assigned_to")}}:</b> <a href="#" class="ms-2">{{$task->assignee->name}}</a></p>
                                <p><b>{{__("crud.tasks.fields.start_date")}}:</b> <span class="text-muted ms-2">{{$task->start_date?->format('jS M, Y') ?? '-'}}</span></p>
                                <p><b>{{__("crud.tasks.fields.due_date")}}:</b> <span class="text-muted ms-2">{{$task->duedate?->format('jS M, Y') ?? '-'}}</span></p>
                                <p><b>{{__("crud.tasks.fields.total_hours")}}:</b> <span class="text-muted ms-2">{{$task->total_hours}}h</span></p>
                            </div>
                        </div>

                        <div class="row">
                            <p class="h4 mt-4">
                                <b><i class='bx bx-pie-chart-alt-2' ></i> {{__("crud.tasks.work_loads")}}</b>
                            </p>

                            <canvas id="hoursChart" style="width:100%;max-width:700px"></canvas>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="row mb-1">
                            <p class="h4 d-inline-block mt-1">
                                <b><i class='bx bx-line-chart'></i> {{__("crud.tasks.recent_activity")}}</b>
                            </p>

                            <canvas id="activityChart" style="width:100%;max-width:700px"></canvas>
                        </div>
                        <div class="row mt-4 mb-1">
                            <p class="h4"><b><i class='bx bx-user' ></i> {{__("crud.tasks.team_members")}}</b></p>
                            <div>
                                @foreach ($task->users as $user)
                                    <img src="{{ $user->profile_url }}" class="rounded-circle" width="45" height="45" alt="{{$user->name}}">
                                @endforeach
                            </div>
                        </div>
                        <div class="row mt-4 mb-1">
                            <p class="h4 mb-1"><b><i class='bx bx-purchase-tag-alt'></i> Tags</b></p>
                            <div>
                                @foreach ($task->labels as $label)
                                    <span class="badge ms-2" style="{{$label->styles}}">{{$label->title}}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 px-3">
        <div class="mt-2 border-bottom">
            <p class="h4 d-inline-block mt-1">
                <b>Files</b>
            </p>

            @foreach ($task->files as $file)
                <div class="row border-top border-bottom p-3">
                    <div class="col-md-10 col-9">
                        <p class="mt-1 mb-1">
                            <i class="{{$file->icon}} text-muted"></i>
                            {{$file->title}}
                        </p>
                        <p class="text-muted" style="font-size: 14px">{{$file->real_size}} | <a href="{{$file->user_id}}">{{$file->user->name}}</a> | {{$file->created_at->format('jS M, h:i A')}}</p>

                        @if($file->is_image)
                            <a href="{{ asset('storage/' . $file->path) }}" target="__blank">
                                <img src="{{ asset('storage/' . $file->path) }}" class="img-fluid" alt="{{$file->title}}" style="max-width: 250px">
                            </a>
                        @endif
                    </div>
                    <div class="col-md-2 col-3">
                        <div class="col-md-2 text-end">
                            <div class="dropdown">
                                <button class="btn btn-options" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class='bx bx-dots-horizontal-rounded'></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li><a class="dropdown-item" href="{{$file->download_link}}"><i class='bx bxs-download' ></i> {{__("global.download")}}</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{route('dashboard.task.delete_file', $file->id)}}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="dropdown-item text-danger"><i class='bx bx-trash' ></i> {{__("global.remove")}}</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            @if($task->files->isEmpty())
                <div class="text-center p-3">
                    <p class="text-muted">No files uploaded yet!</p>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="row pb-4">
    <div class="col-md-8">
        <div class="mt-4">
            <p class="h4 d-inline-block mt-1">
                <b><i class='bx bx-comment-detail' ></i> {{__("crud.tasks.comments")}}</b>
            </p>
            <div class="row">
                <div class="col-md-12">
                    <form action="{{route('dashboard.comments.store', $task->id)}}" method="POST">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="comment" placeholder="{{__("crud.tasks.add_comment")}}" aria-label="Add a comment" aria-describedby="button-addon2">
                            <button class="btn btn-white" type="button" id="button-addon3"><i class='bx bx-paperclip' ></i></button>
                            <button class="btn btn-primary" type="submit" id="button-addon2"><i class='bx bx-send' ></i> {{__("crud.tasks.comment")}}</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 border">
                    @foreach ($task_comments as $comment)
                        <div class="row p-3 border-bottom">
                            <div class="col-md-1">
                                <img src="{{ $comment->user->profile_url }}" class="rounded-circle" width="45" height="45" alt="Profile">
                            </div>
                            <div class="col-md-10">
                                <p class="h6">{{ $comment->user->name }}</p>
                                <p class="text-muted">{{ $comment->text }}</p>
                            </div>
                            <div class="col-md-1">
                                <div class="dropdown">
                                    <button class="btn btn-options" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class='bx bx-dots-horizontal-rounded'></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <li>
                                            <form action="{{route('dashboard.comments.destroy', [$task->id, $comment->id])}}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="dropdown-item text-danger"><i class='bx bx-trash' ></i> {{__("global.remove")}}</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if ($task_comments->isEmpty())
                        <div class="text-center p-3">
                            <p class="text-muted">{{__("crud.tasks.not_comments")}}!</p>
                        </div>

                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const xValues = ['Mon','Tue','Wed','Thu','Fri','Sat', 'Sun'];
const yValues = [7,8,8,9,9,9,10,11,14,14,15];

new Chart("activityChart", {
    type: "bar",
    data: {
        labels: xValues,
        legend: false,
        datasets: [{
            backgroundColor:"rgba(0,0,255,1.0)",
            borderColor: "rgba(0,0,255,0.1)",
            data: [{{ isset($activityChart['values']) ? implode(',', $activityChart['values']) : '0' }}],
            fill: false
        }]
    },
    options:{
        legend: false,
        scales:
        {
            y: {
                beginAtZero: true,
                min: 0
            }
        }
    }
});

var barColors = [
  "#b91d47",
  "#00aba9",
  "#2b5797",
  "#e8c3b9",
  "#1e7145"
];

new Chart("hoursChart", {
    type: "doughnut",
    data: {
        labels: [{!! $hoursChart['labels'] ?? '' !!}],
        datasets: [{
        backgroundColor: barColors,
        // transform array of objects to array of values
        data: [{{ isset($hoursChart['values']) ? implode(',', $hoursChart['values']) : '0' }}]
        }]
    },
    options: {
    }
});

$(function() {
    let timer = $('#timerTask');

    function updateTaskTimer() {
        console.log('hola')
        var myTime = timer.html();
        var ss = myTime.split(":");

        var hours = ss[0];
        var mins = ss[1];
        var secs = ss[2];
        secs = parseInt(secs) + 1;

        if(secs > 59) {
            secs = '00';
            mins = parseInt(mins) + 1;
        }

        if(mins > 59) {
            mins = '00';
            secs = '00';
            hours = parseInt(hours) + 1;
        }

        if (hours.toString().length < 2) {
            hours = '0' + hours;
        }

        if (mins.toString().length < 2) {
            mins = '0' + mins;
        }

        if (secs.toString().length < 2) {
            secs = '0' + secs;
        }

        var ts = hours + ":" + mins + ":" + secs;
        timer.html(ts);
    }

    @if(in_array($task->id, auth()->user()->activeTaskTimers()->pluck('task_id')->toArray()))
        setInterval(updateTaskTimer, 1000);
    @endif
})


</script>
@endsection
