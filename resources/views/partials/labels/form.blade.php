@csrf
<div class="row">
    <div class="col-md-12">
        <div class="form-floating mt-3">
            <input type="text" class="form-control @if($errors->has('name')) is-invalid @endif" id="name" name="name" maxlength="15" placeholder="{{__('crud.labels.fields.name')}}" value="{{ old('name') ?? $label->title }}">
            <label for="title">{{__('crud.labels.fields.name')}} <span class="text-danger">*</span></label>
        </div>

        <div class="mt-0 text-end">
            <span class="text-muted"><span id="nameCountChar">0</span>/15</span>
        </div>

        @if ($errors->has('name'))
            <span class="text-danger">{{ $errors->first('name') }}</span>
        @endif
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-6">
        <label for="backgorund_color" class="form-label">Background Color</label>
        <input type="text" name="background" class="form-control colorpicker" id="background" value="{{ old('background') ?? ($label->data['background'] ?? '#000000') }}" title="Choose your color">
    </div>
    <div class="col-md-6">
        <label for="color" class="form-label">Text Color</label>
        <input type="text" name="color" class="form-control colorpicker" id="color" value="{{ old('color') ?? ($label->data['color'] ?? '#FFFFFF') }}" title="Choose your color">
    </div>
</div>
