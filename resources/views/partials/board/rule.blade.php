<div class="card mb-3 card-rule">
    <div class="card-body">
        <div class="row mb-2">
            <div class="col-md-8">
                <a class="btn btn-secondary btn-sm" href="{{route('dashboard.automation.edit', [$project->id, $rule->id])}}" data-bs-toggle="tooltip" data-bs-title="Edit" data-bs-placement="top"><i class="bx bx-pencil"></i></a>
                <a class="btn btn-secondary btn-sm ms-2" data-bs-toggle="tooltip" href="{{route('dashboard.automation.copy', [$project->id, $rule->id])}}" data-bs-title="Copy" data-bs-placement="top"><i class="bx bx-copy"></i></a>
                <form action="{{route('dashboard.automation.destroy', [$project->id, $rule->id])}}" class="d-inline-block" method="POST">
                    @method('DELETE')
                    @csrf
                    <button class="btn btn-secondary btn-sm ms-2" data-bs-toggle="tooltip" data-bs-title="Delete" data-bs-placement="top"><i class="bx bx-trash"></i></button>
                </form>
                <button class="btn btn-primary btn-sm ms-2" onclick="openModal({{$rule->id}})">
                    <i class='bx bx-plus-medical'></i> Add to another board
                </button>
            </div>
            <div class="col-md-4 text-end">
                <span class="text-muted">Enabled on {{ $rule->boardCount }} boards, </span>
                <span class="text-muted"><i>last modified {{ $rule->updated_at->diffForHumans() }}</i></span>
            </div>
        </div>
        <input type="text" value="{{ $rule->name }}" disabled class="form-control mb-2">
        <div>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="switch-{{$rule->id}}" data-id="{{$rule->id}}" {{$project->boardRules()->where('id', $rule->id)->exists() ? 'checked' : ''}}>
                <label class="form-check-label ms-2 mt-1" for="switch-{{$rule->id}}">Enabled on board</label>
              </div>
        </div>
    </div>
</div>
