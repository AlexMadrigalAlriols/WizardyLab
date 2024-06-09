@csrf
<div class="row">
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <input type="text" class="form-control @if($errors->has('name')) is-invalid @endif" maxlength="40" id="name" name="name" placeholder="Name" value="{{ old('name') ?? $department->name }}">
            <label for="title">Name <span class="text-danger">*</span></label>
        </div>

        <div class="mt-0 text-end">
            <span class="text-muted"><span id="nameCountChar">0</span>/40</span>
        </div>

        {{-- @if ($errors->has('name'))
            <span class="text-danger">{{ $errors->first('name') }}</span>
        @endif --}}
    </div>
</div>
