@csrf
<div class="row">
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <input type="text" class="form-control @if ($errors->has('name')) is-invalid @endif" id="name"
                name="name" placeholder="{{ __('crud.leaveTypes.fields.name') }}"
                value="{{ old('name') ?? $leaveType->name }}" maxlength="30">
            <label for="name">{{ __('crud.leaveTypes.fields.name') }} <span class="text-danger">*</span></label>
        </div>
        <div class="mt-0 text-end">
            <span class="text-muted"><span id="nameCountChar">0</span>/30</span>
        </div>
        @if ($errors->has('name'))
            <span class="text-danger">{{ $errors->first('name') }}</span>
        @endif
    </div>
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <input type="number" class="form-control  @if ($errors->has('max_days')) is-invalid @endif"
                id="max_days" name="max_days" placeholder="{{ __('crud.leaveTypes.fields.max_days') }}"
                value="{{ old('max_days') ?? $leaveType->max_days }}">
            <label for="title">{{ __('crud.leaveTypes.fields.max_days') }} <span class="text-danger">*</span></label>
        </div>

        @if ($errors->has('max_days'))
            <span class="text-danger">{{ $errors->first('max_days') }}</span>
        @endif
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-6">
        <label for="background" class="form-label">Background Color</label>
        <input type="text" name="background" class="form-control colorpicker" id="background"
            value="{{ old('background') ?? ($leaveType->data['background'] ?? '#000000') }}" title="Choose your color">
    </div>
    <div class="col-md-6">
        <label for="color" class="form-label">Text Color</label>
        <input type="text" name="color" class="form-control colorpicker" id="color"
            value="{{ old('color') ?? ($leaveType->data['color'] ?? '#FFFFFF') }}" title="Choose your color">
    </div>
</div>
