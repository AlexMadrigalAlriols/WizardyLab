@csrf
<div class="row">
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <select class="form-select @if($errors->has('type')) is-invalid @endif" id="type" name="type" aria-label="Type">
                @foreach ($types as $type)
                    <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                @endforeach
            </select>
            <label for="floatingSelect">Type <span class="text-danger">*</span></label>
        </div>

        @if ($errors->has('type'))
            <span class="text-danger">{{ $errors->first('type') }}</span>
        @endif
    </div>

    <div class="col-md-6 d-none" id="project">
        <div class="form-floating mt-3">
            <select class="form-select @if($errors->has('project_id')) is-invalid @endif" id="project_id" name="project_id" aria-label="Project">
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}" {{$invoice->project_id == $project->id || old('project_id') == $project->id ? 'selected' : ''}}>{{ $project->name }}</option>
                @endforeach
            </select>
            <label for="floatingSelect">{{__('crud.invoices.fields.project')}}</label>
        </div>

        @if ($errors->has('project_id'))
            <span class="text-danger">{{ $errors->first('project_id') }}</span>
        @endif
    </div>
</div>
<div id="tasks">
    <div class="row">
        <div class="col-md-6">
            <div class="form-floating mt-3">
                <select class="form-select @if($errors->has('client_id')) is-invalid @endif" id="client_id" name="client_id" aria-label="Client">
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}" {{$invoice->client_id == $client->id || old('client_id') == $client->id ? 'selected' : ''}}>{{ $client->name }}</option>
                    @endforeach
                </select>
                <label for="floatingSelect">{{__('crud.invoices.fields.client')}}</label>
            </div>

            @if ($errors->has('client_id'))
                <span class="text-danger">{{ $errors->first('client_id') }}</span>
            @endif
        </div>

        <div class="col-md-6">
            <div class="form-floating mt-3">
                <select class="form-select select2 @if($errors->has('tasks')) is-invalid @endif" id="tasks" name="tasks[]" aria-label="Tasks" multiple>
                    @foreach ($tasks as $task)
                        <option value="{{ $task->id }}">{{ $task->title }}</option>
                    @endforeach
                </select>
                <label for="tasks">{{__('crud.invoices.fields.tasks')}}</label>
            </div>

            @if ($errors->has('tasks'))
                <span class="text-danger">{{ $errors->first('tasks') }}</span>
            @endif
        </div>
    </div>
</div>

<div id="manual" class="d-none">
    <div class="row">
        <div class="col-md-6">
            <div class="form-floating mt-3">
                <select class="form-select @if($errors->has('client_id')) is-invalid @endif" id="client_id" name="client_id" aria-label="Client">
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}" {{$invoice->client_id == $client->id || old('client_id') == $client->id ? 'selected' : ''}}>{{ $client->name }}</option>
                    @endforeach
                </select>
                <label for="floatingSelect">{{__('crud.invoices.fields.client')}}</label>
            </div>

            @if ($errors->has('client_id'))
                <span class="text-danger">{{ $errors->first('client_id') }}</span>
            @endif
        </div>

        <div class="col-md-6">
            <div class="form-floating mt-3">
                <input type="text" class="form-control @if($errors->has('amount')) is-invalid @endif" id="amount" name="amount" placeholder="Amount" value="{{ old('amount') ?? $invoice->amount }}">
                <label for="number">{{__('crud.invoices.fields.amount')}} <span class="text-danger">*</span></label>
            </div>

            @if ($errors->has('amount'))
                <span class="text-danger">{{ $errors->first('amount') }}</span>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <select class="form-select @if($errors->has('status_id')) is-invalid @endif" id="status_id" name="status_id" aria-label="Status">
                @foreach ($statuses as $status)
                    <option value="{{ $status->id }}" {{$invoice->status_id == $status->id || old('status_id') == $status->id ? 'selected' : ''}}>{{ $status->title }}</option>
                @endforeach
            </select>
            <label for="floatingSelect">{{__('crud.invoices.fields.status')}} <span class="text-danger">*</span></label>
        </div>

        @if ($errors->has('status_id'))
            <span class="text-danger">{{ $errors->first('status_id') }}</span>
        @endif
    </div>
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <input type="date" class="form-control flatpicker @if($errors->has('issue_date')) is-invalid @endif" id="issue_date" name="issue_date" placeholder="Address" value="{{ old('issue_date') ?? now()->format('Y-m-d') }}">
            <label for="issue_date">{{__('crud.invoices.fields.issue_date')}} <span class="text-danger">*</span></label>
        </div>

        @if ($errors->has('issue_date'))
            <span class="text-danger">{{ $errors->first('issue_date') }}</span>
        @endif
    </div>
</div>