@csrf
<div class="row">
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <input type="text" class="form-control" id="name" name="name" placeholder="{{__('crud.leaveTypes.fields.name')}}" value="{{ old('name') ?? $leaveType->name }}">
            <label for="title">{{__('crud.leaveTypes.fields.name')}} <span class="text-danger">*</span></label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <input type="number" class="form-control" id="max_days" name="max_days" placeholder="{{__('crud.leaveTypes.fields.max_days')}}" value="{{ old('max_days') ?? $leaveType->max_days }}">
            <label for="title">{{__('crud.leaveTypes.fields.max_days')}} <span class="text-danger">*</span></label>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-6">
        <label for="background" class="form-label">Background Color</label>
        <input type="color" name="background" class="form-control form-control-color" id="background" value="{{ old('background') ?? ($leaveType->data['background'] ?? '#000000') }}" title="Choose your color">
    </div>
    <div class="col-md-6">
        <label for="color" class="form-label">Text Color</label>
        <input type="color" name="color" class="form-control form-control-color" id="color" value="{{ old('color') ?? ($leaveType->data['color'] ?? '#FFFFFF') }}" title="Choose your color">
    </div>
</div>
