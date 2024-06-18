<div class="card-task mt-2 border-bottom pb-2" data-task-id="{{$task->id}}" onclick="openShowTaskModal({{$task->id}})">
    <div class="card-task-body p-2">
        <div class="row">
            @if($task->cover)
                <img src="{{$task->cover}}" class="img-fluid rounded mb-2" alt="">
            @endif
        </div>
        <div class="card-task-header">
            <div class="card-task-header-title">
                <span class="badge bg-{{$task->priority}} me-1">{{ucfirst($task->priority)}}</span>

                @foreach ($task->labels as $label)
                    <span class="badge me-1" style="{{$label->styles}}">{{$label->title}}</span>
                @endforeach
            </div>
        </div>
        <p class="card-task-body-text mt-1">{{$task->title}}</p>

        <div class="row">
            <div class="col-md-8 align-middle task-body-grab">
                @if (!is_null($task->duedate))
                    <p class="mb-0 {{$task->is_overdue ? 'text-danger' : 'text-muted'}}"><i class='bx bx-calendar-x' ></i> {{$task->duedate->format('jS M, Y')}}</p>
                @endif
                @if($task->files->count() > 0)
                    <p class="mb-0 text-muted"><i class='bx bx-paperclip' ></i> {{$task->files->count()}}</p>
                @endif

                <div class="avatar-group mt-2">
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
            </div>

            <div class="col-md-4 text-end">
                @if(in_array($task->id, auth()->user()->activeTaskTimers()->pluck('task_id')->toArray()))
                    <a href="{{route('dashboard.task-clock-out', $task->id)}}" class="btn btn-attendance-task">
                        <i class='bx bx-stop-circle align-middle'></i>
                    </a>
                @else
                    <a href="{{route('dashboard.task-clock-in', $task->id)}}" class="btn btn-attendance-task">
                        <i class='bx bx-play-circle align-middle'></i>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
