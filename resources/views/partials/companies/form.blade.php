@csrf
<div class="row">
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <input type="text" class="form-control @if($errors->has('name')) is-invalid @endif" id="name" name="name" placeholder="{{__('crud.companies.fields.name')}}" value="{{ old('name') ?? $company->name }}">
            <label for="title">{{__('crud.companies.fields.name')}} <span class="text-danger">*</span></label>
        </div>

        @if ($errors->has('name'))
            <span class="text-danger">{{ $errors->first('name') }}</span>
        @endif
    </div>
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <select class="form-select" id="active" name="active" aria-label="Active">
                <option value="0" {{$company->active == 0 || old('active') == 0 ? 'selected' : ''}}>{{__('global.no')}}</option>
                <option value="1" {{$company->active == 1 || old('active') == 1 ? 'selected' : ''}}>{{__('global.yes')}}</option>
            </select>
            <label for="floatingSelect">{{__('crud.companies.fields.active')}} <span class="text-danger">*</span></label>
        </div>
    </div>
</div>
