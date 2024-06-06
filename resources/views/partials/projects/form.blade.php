@csrf
<div class="row">
    <div class="col-md-7">
        <div class="form-floating mt-3">
            <input type="text" class="form-control @if($errors->has('name')) is-invalid @endif" id="name" name="name" placeholder="Project Title" value="{{ old('name') ?? $project->name }}">
            <label for="title">Title <span class="text-danger">*</span></label>
        </div>

        @if ($errors->has('name'))
            <span class="text-danger">{{ $errors->first('name') }}</span>
        @endif
    </div>
    <div class="col-md-5">
        <div class="form-floating mt-3">
            <input type="text" class="form-control @if($errors->has('code')) is-invalid @endif" id="code" name="code" placeholder="Project Code" {{$project->id ? 'disabled' : ''}} value="{{ old('code') ?? $project->code }}">
            <label for="code">Code <span class="text-danger">*</span></label>
        </div>

        @if ($errors->has('code'))
            <span class="text-danger">{{ $errors->first('code') }}</span>
        @endif
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <input type="date" class="form-control flatpicker" id="start_date" name="start_date" placeholder="Start Date" value="{{ old('start_date') ?? $project->start_date?->format('Y-m-d') }}">
            <label for="start_date">Start Date</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <input type="date" class="form-control flatpicker" id="due_date" name="due_date" placeholder="Due Date" value="{{ old('due_date') ?? $project->duedate?->format('Y-m-d') }}">
            <label for="due_date">Due Date</label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <input type="number" class="form-control @if($errors->has('limit_hours')) is-invalid @endif" id="limit_hours" min="0" name="limit_hours" placeholder="Limit Hours"value="{{ old('limit_hours') ?? $project->limit_hours }}">
            <label for="limit_hours">Limit Hours</label>
        </div>

        @if ($errors->has('limit_hours'))
            <span class="text-danger">{{ $errors->first('limit_hours') }}</span>
        @endif
    </div>

    <div class="col-md-6">
        <div class="form-floating mt-3">
            <select class="form-select select2 @if($errors->has('status')) is-invalid @endif" id="status" name="status" aria-label="Status">
                @foreach ($project_statuses as $status)
                    <option value="{{ $status->id }}" {{$project->status_id == $status->id || old('status') == $status->id ? 'selected' : ''}}>{{ $status->title }}</option>
                @endforeach
            </select>
            <label for="floatingSelect">Status <span class="text-danger">*</span></label>
        </div>

        @if ($errors->has('status'))
            <span class="text-danger">{{ $errors->first('status') }}</span>
        @endif
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-floating mt-3">
            <textarea class="form-control" placeholder="Task Description" id="description" name="description" style="height: 85px;">{{old('description') ?? $project->description}}</textarea>
            <label for="description">Description</label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <select class="form-select select2" id="assigned_users" multiple name="assigned_users[]" aria-label="Assigned Users">
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{in_array($user->id, $project->users()->pluck('id')->toArray()) || old('assigned_users') == $user->id || $user->id === auth()->user()->id ? 'selected' : ''}}>{{ $user->name }}</option>
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
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <select class="form-select select2" id="client_id" name="client_id" aria-label="Status">
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}" {{$project->client_id == $client->id || old('client_id') == $client->id ? 'selected' : ''}}>{{ $client->name }} @if($client->email)({{$client->email}})@endif</option>
                @endforeach
            </select>
            <label for="floatingSelect">Client</label>
        </div>
    </div>
</div>
