@csrf
<div class="row">
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <select class="form-select @if($errors->has('type')) is-invalid @endif" id="type" name="type" aria-label="Type">
                @foreach ($types as $type)
                    <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                @endforeach
            </select>
            <label for="floatingSelect">{{__('crud.invoices.fields.type')}} <span class="text-danger">*</span></label>
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

    <div class="col-md-6 d-none" id="manual">
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

<div class="row">
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <select class="form-select @if($errors->has('status_id')) is-invalid @endif" id="status_id" name="status_id" aria-label="Status">
                @foreach ($statuses as $status)
                    <option value="{{ $status->id }}" {{$invoice->status_id == $status->id || old('status_id') == $status->id ? 'selected' : ''}}>{{$status->title=="Paid"?__("crud.invoices.paid"):__("crud.invoices.no_paid")}}</option>
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

<div class="row">
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <select class="form-select @if($errors->has('include_tax')) is-invalid @endif" id="include_tax" name="include_tax" aria-label="Status">
                <option value="0" {{old('include_tax') === 0 ? 'selected' : ''}}>{{__('global.no')}}</option>
                <option value="1" {{old('include_tax') === 1 ? 'selected' : ''}}>{{__('global.yes')}}</option>
            </select>
            <label for="floatingSelect">{{__('crud.invoices.fields.include_tax')}} <span class="text-danger">*</span></label>
        </div>

        @if ($errors->has('include_tax'))
            <span class="text-danger">{{ $errors->first('include_tax') }}</span>
        @endif
    </div>

    <div class="col-md-6">
        <div class="form-floating mt-3">
            <select class="form-select @if($errors->has('show_logo')) is-invalid @endif" id="show_logo" name="show_logo" aria-label="Status">
                <option value="1" {{old('show_logo') === 1 ? 'selected' : ''}}>{{__('global.yes')}}</option>
                <option value="0" {{old('show_logo') === 0 ? 'selected' : ''}}>{{__('global.no')}}</option>
            </select>
            <label for="floatingSelect">{{__('crud.invoices.fields.show_logo')}} <span class="text-danger">*</span></label>
        </div>

        @if ($errors->has('show_logo'))
            <span class="text-danger">{{ $errors->first('show_logo') }}</span>
        @endif
    </div>
</div>

<div class="row d-none mt-4" id="manualValues">
    <div class="col-md-12">
        <expenses-form :inventory-items="{{json_encode($inventoryArray)}}" :assignments="{{json_encode([])}}"></expenses-form>

        @if ($errors->has('items'))
            <span class="text-danger">{{ $errors->first('items') }}</span>
        @endif
    </div>
</div>
