<div class="board-nav p-2 border-bottom px-4">
    <div class="row">
        <div class="col-md-6">
            <div>
                <a href="{{ route('dashboard.projects.index') }}" class="btn btn-outline-primary">
                    <span class="px-4">
                        < Back</span>
                </a>
                <span class="h3 align-middle ms-3">
                    <b>{{ $project->name }}</b>
                </span>

                <div class="avatar-group d-inline-block align-middle ms-3">
                    @foreach ($project->users()->limit(3)->get() as $task_user)
                        <div class="avatar avatar-s">
                            <img src="{{ auth()->user()->profile_img }}" alt="avatar" class="rounded-circle">
                        </div>
                    @endforeach

                    @if ($project->users()->count() > 3)
                        <div class="avatar avatar-s">
                            <div class="avatar-name rounded-circle">
                                <span>+{{ $project->users()->count() - 3 }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6 text-end">
            <div class="mt-2">
                <a href="{{ route('dashboard.automation.index', $project->id) }}" class="btn btn-outline-primary">
                    <span class="px-4"><i class="bx bxs-zap me-1"></i> Automation</span>
                </a>
                <a href="{{ route('dashboard.projects.board.configurations', $project->id) }}" class="btn btn-primary">
                    <span class="px-4"><i class="bx bx-cog me-1 mt-1"></i> Configuración</span>
                </a>
            </div>
        </div>
    </div>
</div>
