@csrf
<input type="hidden" name="board" value="{{ request()->input('board') }}">
<div class="row">
    <div class="col-md-7">
        <div class="form-floating mt-3">
            <input type="text" class="form-control" id="title" name="title" placeholder="Task Title" value="{{ old('title') ?? $task->title }}">
            <label for="title">Title <span class="text-danger">*</span></label>
        </div>
    </div>
    <div class="col-md-5">
        <div class="form-floating mt-3">
            <select class="form-select select2" id="project" name="project">
              <option value="" selected>--</option>
              @foreach ($projects as $project)
                <option value="{{ $project->id }}" {{$project->id == request()->input('board') || ($task->project_id == $project->id || old('project') == $project->id) ? 'selected' : ''}}>{{ $project->name }}</option>
              @endforeach
            </select>
            <label for="floatingSelect">Project</label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-floating mt-3">
            <input type="date" class="form-control" id="start_date" name="start_date" placeholder="Start Date" value="{{ old('start_date') ?? $task->start_date?->format('Y-m-d') }}">
            <label for="start_date">Start Date</label>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-floating mt-3">
            <input type="date" class="form-control" id="due_date" name="due_date" placeholder="Due Date" value="{{ old('due_date') ?? $task->duedate?->format('Y-m-d') }}">
            <label for="due_date">Due Date</label>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-floating mt-3">
            <input type="number" class="form-control" id="limit_hours" min="0" name="limit_hours" placeholder="Limit Hours"value="{{ old('limit_hours') ?? $task->limit_hours }}">
            <label for="limit_hours">Limit Hours</label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <select class="form-select select2" id="assigned_users" multiple name="assigned_users[]" aria-label="Assigned Users">
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{auth()->user()->id == $user->id || (in_array($user->id, $task->users()->pluck('id')->toArray()) || old('assigned_users') == $user->id) ? 'selected' : ''}}>{{ $user->name }}</option>
                @endforeach
            </select>
            <label for="floatingSelect">Assigned To</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <select class="form-select select2" id="departments" multiple name="departments[]" aria-label="Department">
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                @endforeach
            </select>
            <label for="floatingSelect">Department</label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-floating mt-3">
            <textarea class="form-control" placeholder="Task Description" id="description" name="description" style="height: 85px;">{{old('description') ?? $task->description}}</textarea>
            <label for="description">Description</label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <select class="form-select" id="priority" name="priority" aria-label="Priority">
                @foreach ($priorities as $priority)
                    <option value="{{ $priority }}" {{$task->priority == $priority || old('priority') == $task->priority ? 'selected' : ''}}>{{ ucfirst($priority) }}</option>
                @endforeach
            </select>
            <label for="floatingSelect">Priority <span class="text-danger">*</span></label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <select class="form-select select2" id="parent_task" name="parent_task" aria-label="Task">
                <option value=""></option>
                @foreach ($tasks as $parent_task)
                    <option value="{{ $parent_task->id }}" {{$parent_task->id == $task->task_id || old('parent_task') == $parent_task->id ? 'selected' : ''}}>{{ $parent_task->title }}</option>
                @endforeach
            </select>
            <label for="floatingSelect">Task</label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <select class="form-select select2" id="status" name="status" aria-label="Status">
                @foreach ($task_statuses as $status)
                    <option value="{{ $status->id }}" {{$status->id == request()->input('status') || ($task->status_id == $status->id || old('status') == $status->id) ? 'selected' : ''}}>{{ $status->title }}</option>
                @endforeach
            </select>
            <label for="floatingSelect">Status <span class="text-danger">*</span></label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <select class="form-select select2" id="tags" multiple name="tags[]" aria-label="Tags">
                @foreach ($tags as $tag)
                    <option value="{{ $tag->id }}" {{in_array($tag->id, $task->labels()->pluck('id')->toArray()) || old('tags') == $tag->id ? 'selected' : ''}}>{{ $tag->title }}</option>
                @endforeach
            </select>
            <label for="floatingSelect">Tags</label>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-12">
        <span class="h3">Upload Files</span>
        <div class="dropzone mt-2" id="logoDropzone"></div>
    </div>
</div>

@section('scripts')
    <script>
        Dropzone.autoDiscover = false;

        $(document).ready(function() {
            new Dropzone("#logoDropzone", {
                url: "{{ route('dashboard.task.upload_file') }}", // Ruta donde manejarás la carga de archivos
                paramName: "dropzone_image", // Nombre del campo de formulario para el archivo
                maxFilesize: 2, // Tamaño máximo en MB
                acceptedFiles: ".jpeg,.jpg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.txt",
                addRemoveLinks: true,
                uploadMultiple: true,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });
        });
    </script>
@endsection
