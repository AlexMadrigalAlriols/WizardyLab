@csrf
<div class="row">
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <input type="text" class="form-control" id="name" name="name" placeholder="{{__('crud.status.fields.name')}}" value="{{ old('name') ?? $status->title }}">
            <label for="title">{{__('crud.status.fields.name')}} <span class="text-danger">*</span></label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <select name="type" id="type" class="form-control form-select">
                <option value="task">Task</option>
                <option value="project">Project</option>
                <option value="invoice">Invoice</option>
            </select>
        </div>
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
