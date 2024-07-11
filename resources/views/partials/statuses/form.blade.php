@csrf
<div class="row">
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <input type="text" class="form-control @if($errors->has('name')) is-invalid @endif" maxlength="15" id="name" name="name" placeholder="{{__('crud.status.fields.name')}}" value="{{ old('name') ?? $status->title }}">
            <label for="title">{{__('crud.status.fields.name')}} <span class="text-danger">*</span></label>
        </div>

        <div class="mt-0 text-end">
            <span class="text-muted"><span id="nameCountChar">0</span>/15</span>
        </div>

        @if ($errors->has('name'))
            <span class="text-danger">{{ $errors->first('name') }}</span>
        @endif
    </div>
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <select name="type" id="type" class="form-control form-select @if($errors->has('type')) is-invalid @endif">
                <option value="task">{{__("crud.tasks.title_singular")}}</option>
                <option value="project">{{__("crud.projects.title_singular")}}</option>
                <option value="invoice">{{__("crud.invoices.title_singular")}}</option>
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
        <label for="background" class="form-label">{{__("global.background_color")}}</label>
        <input type="text" name="background" class="form-control colorpicker" id="background" value="{{ old('background') ?? ($status->data['background'] ?? '#000000') }}" title="Choose your color">
    </div>
    <div class="col-md-6">
        <label for="color" class="form-label">{{__("global.text_color")}}</label>
        <input type="text" name="color" class="form-control colorpicker" id="color" value="{{ old('color') ?? ($status->data['color'] ?? '#FFFFFF') }}" title="Choose your color">
    </div>
</div>
