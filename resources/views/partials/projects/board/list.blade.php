<div class="list-container {{$status->collapsed ? 'collapsed' : ''}}" id="list-container-{{$status->id}}" data-statusId="{{$status->id}}">
    <div class="card">
        <div class="card-body">
            <button class="d-inline-block btn btn-white collapse-list collapsed p-0 pb-3" onclick="switchListCollapse(this)"><i class='bx bx-arrow-from-left' ></i></button>
            <p class="card-title mb-3">
                <span>{{$status->status->title}}</span> <span class="badge bg-dark rounded-circle ms-1">{{ count($tasks) }}</span>
                <button class="d-inline-block btn btn-white collapse-list p-0"  onclick="switchListCollapse(this)" style="float:right;"><i class='bx bx-arrow-from-right' ></i></button>
            </p>

            <div class="card-divider" style="{{$status->status->styles}}"></div>

            <p class="card-title-collapsed mb-3 mt-3">
                <span class="badge bg-dark rounded-circle ms-1">{{ count($tasks) }}</span> <span class="title-status mt-2">{{$status->status->title}}</span>
            </p>

            <div class="mt-3 task-container scrollbar" id="list-{{$status->status->id}}">
                @foreach ($tasks ?? [] as $task)
                    @include('partials.projects.board.task', ['task' => $task])
                @endforeach
            </div>
        </div>

        <div class="card-task p-2">
            <a href="{{route('dashboard.tasks.create')}}?board={{$project->id}}&status={{$status->status->id}}" class="text-decoration-none">
                <div class="card-task-body p-2 text-center">
                    <p class="card-task-body-text mt-3"><b class="text-muted"><i class='bx bx-plus'></i> ADD TASK</b></p>
                </div>
            </a>
        </div>
    </div>
</div>
