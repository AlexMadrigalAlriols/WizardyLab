@csrf
<div class="row">
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <input type="text" class="form-control @if($errors->has('name')) is-invalid @endif" maxlength="25" id="name" name="name" placeholder="Name" value="{{ old('name') ?? $role->name }}">
            <label for="title">Name <span class="text-danger">*</span></label>
        </div>

        <div class="mt-0 text-end">
            <span class="text-muted"><span id="nameCountChar">0</span>/25</span>
        </div>

        @if ($errors->has('name'))
            <span class="text-danger">{{ $errors->first('name') }}</span>
        @endif
    </div>
</div>

<div class="row">
    <div>
        <span class="h6">Permissions</span>
        <div class="form-check form-switch d-inline-block ms-2">
            <input type="checkbox" class="form-check-input" id="check_all" role="switch">
            <label for="check_all" class="form-check-label mt-1 ms-1">Select All</label>
        </div>
    </div>
    @foreach ($permissions as $permission)
    <div class="col-md-3 mt-3">
        <div class="form-check form-switch">
            <input type="checkbox" class="form-check-input permissions-check" name="permissions[{{$permission->id}}]" id="{{$permission->name}}" {{$role->permissions->where('id', $permission->id)->first() ? 'checked' : ''}} role="switch">
            <label for="{{$permission->name}}" class="form-check-label mt-1 ms-1">{{$permission->name}}</label>
        </div>
    </div>
    @endforeach
</div>
