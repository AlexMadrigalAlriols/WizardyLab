@if($task->cover)
<div class="position-relative p-0 modal-header" style="height: 200px;">
    <img src="{{$task->cover}}" alt=""
        class="w-100 h-100 fit-cover task-cover-header">
</div>
@endif
<div class="modal-body">
    <div class="row">
        <div class="h-100 col-lg-8 col-12">
            <div class="mt-0 top-0 gy-4 pb-3 gx-0 px-3 row scrollbar card-task-body">
                <div class="col-sm-3 col-4">
                    <h6 class="text-muted fw-bolder mt-1">TITLE</h6>
                </div>
                <div class="col-sm-9 col-8">
                    <span class="mt-1 h6">{{$task->title}}</span>
                </div>

                <div class="col-sm-3 col-4">
                    <h6 class="text-muted fw-bolder mt-1">DESCRIPTION</h6>
                </div>
                <div class="col-sm-9 col-8">
                    <span class="mt-1 text-muted">{!! $task->description !!}</span>
                </div>

                <div class="col-sm-3 col-4">
                    <h6 class="text-muted fw-bolder mt-1">HOURS</h6>
                </div>
                <div class="col-sm-9 col-8">
                    <span class="{{$task->total_hours > $task->limit_hours ? 'text-danger' : 'text-muted'}}">{{$task->total_hours}}h</span> / <b>{{$task->limit_hours ?? '-'}}h</b>
                </div>

                <div class="col-sm-3 col-4">
                    <h6 class="text-muted fw-bolder mt-1">PROJECT</h6>
                </div>
                <div class="col-sm-9 col-8">
                    <span class="mt-1 text-muted"><a href="{{route('dashboard.projects.show', $task->project->id)}}">{{$task->project->name}}</a></span>
                </div>

                <div class="col-sm-3 col-4">
                    <h6 class="text-muted fw-bolder mt-1">STATUS</h6>
                </div>
                <div class="col-sm-9 col-8">
                    <span class="mt-1 badge {{$task->status->badge}}">{{$task->status->title}}</span>
                </div>

                <div class="col-sm-3 col-4">
                    <h6 class="text-muted fw-bolder mt-1">ASSIGNED TO</h6>
                </div>
                <div class="col-sm-9 col-8">
                   @foreach ($task->users as $user)
                    <a href="#" data-bs-toggle="tooltip" data-bs-title="{{$user->name}}" data-bs-placement="bottom">
                        <img src="{{$user->profile_url}}" class="rounded-circle me-2" width="35" height="35">
                    </a>
                   @endforeach
                </div>

                <div class="col-sm-3 col-4">
                    <h6 class="text-muted fw-bolder mt-1">PRIORITY</h6>
                </div>
                <div class="col-sm-9 col-8">
                    <span class="mt-1 badge bg-{{$task->priority}}">{{$task->priority}}</span>
                </div>

                <div class="col-sm-3 col-4">
                    <h6 class="text-muted fw-bolder mt-1">LABELS</h6>
                </div>
                <div class="col-sm-9 col-8">
                    <div class="d-flex flex-row gap-2 mt-1">
                        @foreach ($task->labels as $label)
                            <span class="badge" style="{{$label->styles}}">{{$label->title}}</span>
                        @endforeach
                    </div>
                </div>

                <div class="col-sm-3 col-4">
                    <h6 class="text-muted fw-bolder mt-1">FILES</h6>
                </div>
                <div class="col-sm-9 col-8">
                    <div class="d-flex flex-column gap-3 mb-2">
                        @foreach ($task->files as $file)
                            <div class="border-bottom d-flex flex-row pb-3">
                                <div class="row">
                                    <div class="col-md-2">
                                        <a href="{{asset('storage/' . $file->path)}}" target="__blank">
                                            <img src="{{asset('storage/' . $file->path)}}" alt="" width="65" height="65" class="rounded-3">
                                        </a>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="flex-1 ms-3 d-flex flex-column">
                                            <h5 class="h6 text-dark mb-0">{{$file->title}}</h5>
                                            <p class="text-muted h6 mb-0 mt-1">{{$file->updated_at->format('jS M, Y')}}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="dropdown">
                                            <button class="btn btn-options" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <i class='bx bx-dots-horizontal-rounded'></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <li><a class="dropdown-item" href="{{$file->download_link}}"><i class='bx bxs-download' ></i> Download</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{route('dashboard.task.delete_file', $file->id)}}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="dropdown-item text-danger"><i class='bx bx-trash' ></i> Remove</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="text-center mt-3">
                        <a href="{{route('dashboard.tasks.edit', $task->id)}}"><i class='bx bx-plus' ></i> Add Files</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-start h-100 scrollbar card-task-body col-lg-4 col-12 border-start">
            <b>Actions</b>
            <div class="row mb-3">
                <div class="col-12">
                    @foreach ($actions as $action)
                        <button class="btn btn-actions btn-sm w-100 text-start mt-2" id="{{$action['action']}}" onclick="sendAction('{{$action['action']}}', {{$task->id}})">
                            <i class="{{$action['icon']}} me-2"></i>{{$action['label']}}
                        </button>
                    @endforeach
                </div>
            </div>
            <b>Activities</b>
            <div class="row mt-3">
                <div class="col-12">
                    @foreach ($task->activity as $activity)
                        @include('partials.projects.board.activity', ['activity' => $activity])
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="border-top">
                <p class="h5 mt-3"><b><i class='bx bx-chat' ></i> Comments</b></p>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <form action="{{route('dashboard.comments.store', $task->id)}}" method="POST" id="commentsFrm">
                            @csrf
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="comment" placeholder="Add a comment" aria-label="Add a comment" aria-describedby="button-addon2">
                                <button class="btn btn-primary" type="button" onclick="submitCommentForm()"><i class='bx bx-send' ></i> Comment</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 border-bottom" id="comments">
                        @foreach ($task_comments as $comment)
                            @include('partials.comments.index', ['comment' => $comment, 'task' => $task])
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="justify-content-between modal-footer border-0">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class='bx bx-x' ></i> Close</button>
    <a type="button" class="btn btn-primary" href="{{route('dashboard.tasks.edit', $task->id)}}?board={{$task->project_id}}"><span class="px-3">Edit <i class="bx bx-edit"></i></span></a>
</div>
