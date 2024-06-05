@csrf
<div class="row">
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <input type="text" class="form-control @if($errors->has('name')) is-invalid @endif" id="name" name="name" placeholder="{{__('crud.status.fields.name')}}" value="{{ old('name') ?? $status->title }}">
            <label for="title">{{__('crud.status.fields.name')}} <span class="text-danger">*</span></label>
        </div>
        @if ($errors->has('name'))
            <span class="text-danger">{{ $errors->first('name') }}</span>
        @endif
    </div>
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <select name="type" id="type" class="form-control form-select @if($errors->has('type')) is-invalid @endif">
                <option value="task">Task</option>
                <option value="project">Project</option>
                <option value="invoice">Invoice</option>
            </select>
            <label for="">{{__('crud.status.fields.type')}} <span class="text-danger">*</span></label>
        </div>

        @if ($errors->has('type'))
            <span class="text-danger">{{ $errors->first('type') }}</span>
        @endif
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-6">
        <label for="background" class="form-label">Background Color</label>
        <input type="color" name="background" class="form-control form-control-color" id="background" value="{{ old('background') ?? ($status->data['background'] ?? '#000000') }}" title="Choose your color">
    </div>
    <div class="col-md-6">
        <label for="color" class="form-label">Text Color</label>
        <input type="color" name="color" class="form-control form-control-color" id="color" value="{{ old('color') ?? ($status->data['color'] ?? '#FFFFFF') }}" title="Choose your color">
    </div>
</div>
