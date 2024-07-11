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
    <div class="col-md-6">
        <div class="form-floating mt-3">
             <select class="form-select select2" id="assignments" multiple name="permissions[]" aria-label="Tags">
                @foreach ($permissions->filter(function ($tag){
                    return stripos($tag->name, 'assignment') !== false;
                }) as $tag)
                     <option value="{{ $tag->id }}" {{$role->permissions?->where("id", $tag->id)->first() || old('tags') == $tag->id ? 'selected' : ''}}>{{ __('crud.roles.'.$tag->name) }}</option>
                @endforeach
             </select>
             <label for="floatingSelect">{{ __('crud.assignments.title') }}</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating mt-3">
             <select class="form-select select2" id="clients" multiple name="permissions[]" aria-label="Tags">
                @foreach ($permissions->filter(function ($tag){
                    return stripos($tag->name, 'clients') || stripos($tag['name'], 'client') !== false;
                }) as $tag)
                     <option value="{{ $tag->id }}" {{$role->permissions?->where("id", $tag->id)->first() || old('tags') == $tag->id ? 'selected' : ''}}>{{ __('crud.roles.'.$tag->name) }}</option>
                @endforeach
             </select>
             <label for="floatingSelect">{{ __('crud.clients.title') }}</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating mt-3">
             <select class="form-select select2" id="leaves" multiple name="permissions[]" aria-label="Tags">
                @foreach ($permissions->filter(function ($tag){
                    return stripos($tag->name, 'leave') !== false;
                }) as $tag)
                     <option value="{{ $tag->id }}" {{$role->permissions?->where("id", $tag->id)->first() || old('tags') == $tag->id ? 'selected' : ''}}>{{ __('crud.roles.'.$tag->name) }}</option>
                @endforeach
             </select>
             <label for="floatingSelect">{{ __('crud.leaves.title') }}</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating mt-3">
             <select class="form-select select2" id="holidays" multiple name="permissions[]" aria-label="Tags">
                @foreach ($permissions->filter(function ($tag){
                    return stripos($tag->name, 'holiday') !== false;
                }) as $tag)
                     <option value="{{ $tag->id }}" {{$role->permissions?->where("id", $tag->id)->first() || old('tags') == $tag->id ? 'selected' : ''}}>{{ __('crud.roles.'.$tag->name) }}</option>
                @endforeach
             </select>
             <label for="floatingSelect">{{ __('global.holiday') }}</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating mt-3">
             <select class="form-select select2" id="documents" multiple name="permissions[]" aria-label="Tags">
                @foreach ($permissions->filter(function ($tag){
                    return stripos($tag->name, 'document') !== false;
                }) as $tag)
                     <option value="{{ $tag->id }}" {{$role->permissions?->where("id", $tag->id)->first() || old('tags') == $tag->id ? 'selected' : ''}}>{{ __('crud.roles.'.$tag->name) }}</option>
                @endforeach
             </select>
             <label for="floatingSelect">{{ __('crud.documents.title') }}</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating mt-3">
             <select class="form-select select2" id="users" multiple name="permissions[]" aria-label="Tags">
                @foreach ($permissions->filter(function ($tag){
                    return stripos($tag->name, 'user') !== false;
                }) as $tag)
                     <option value="{{ $tag->id }}" {{$role->permissions?->where("id", $tag->id)->first() || old('tags') == $tag->id ? 'selected' : ''}}>{{ __('crud.roles.'.$tag->name) }}</option>
                @endforeach
             </select>
             <label for="floatingSelect">{{ __('crud.users.title') }}</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating mt-3">
             <select class="form-select select2" id="items" multiple name="permissions[]" aria-label="Tags">
                @foreach ($permissions->filter(function ($tag){
                    return stripos($tag->name, 'item') !== false;
                }) as $tag)
                     <option value="{{ $tag->id }}" {{$role->permissions?->where("id", $tag->id)->first() || old('tags') == $tag->id ? 'selected' : ''}}>{{ __('crud.roles.'.$tag->name) }}</option>
                @endforeach
             </select>
             <label for="floatingSelect">{{ __('crud.items.title') }}</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating mt-3">
             <select class="form-select select2" id="configurations" multiple name="permissions[]" aria-label="Tags">
                @foreach ($permissions->filter(function ($tag){
                    return stripos($tag->name, 'configuration') !== false;
                }) as $tag)
                     <option value="{{ $tag->id }}" {{$role->permissions?->where("id", $tag->id)->first() || old('tags') == $tag->id ? 'selected' : ''}}>{{ __('crud.roles.'.$tag->name) }}</option>
                @endforeach
             </select>
             <label for="floatingSelect">{{ __('global.configuration') }}</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating mt-3">
             <select class="form-select select2" id="expenses" multiple name="permissions[]" aria-label="Tags">
                @foreach ($permissions->filter(function ($tag){
                    return stripos($tag->name, 'expense') !== false;
                }) as $tag)
                     <option value="{{ $tag->id }}" {{$role->permissions?->where("id", $tag->id)->first() || old('tags') == $tag->id ? 'selected' : ''}}>{{ __('crud.roles.'.$tag->name) }}</option>
                @endforeach
             </select>
             <label for="floatingSelect">{{ __('crud.expenses.title') }}</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating mt-3">
             <select class="form-select select2" id="status" multiple name="permissions[]" aria-label="Tags">
                @foreach ($permissions->filter(function ($tag){
                    return stripos($tag->name, 'status') !== false;
                }) as $tag)
                     <option value="{{ $tag->id }}" {{$role->permissions?->where("id", $tag->id)->first() || old('tags') == $tag->id ? 'selected' : ''}}>{{ __('crud.roles.'.$tag->name) }}</option>
                @endforeach
             </select>
             <label for="floatingSelect">{{ __('crud.status.title') }}</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating mt-3">
             <select class="form-select select2" id="labels" multiple name="permissions[]" aria-label="Tags">
                @foreach ($permissions->filter(function ($tag){
                    return stripos($tag->name, 'label') !== false;
                }) as $tag)
                     <option value="{{ $tag->id }}" {{$role->permissions?->where("id", $tag->id)->first() || old('tags') == $tag->id ? 'selected' : ''}}>{{ __('crud.roles.'.$tag->name) }}</option>
                @endforeach
             </select>
             <label for="floatingSelect">{{ __('crud.labels.title') }}</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating mt-3">
             <select class="form-select select2" id="leavetypes" multiple name="permissions[]" aria-label="Tags">
                @foreach ($permissions->filter(function ($tag){
                    return stripos($tag->name, 'leavetype') !== false;
                }) as $tag)
                     <option value="{{ $tag->id }}" {{$role->permissions?->where("id", $tag->id)->first() || old('tags') == $tag->id ? 'selected' : ''}}>{{ __('crud.roles.'.$tag->name) }}</option>
                @endforeach
             </select>
             <label for="floatingSelect">{{ __('global.leave_types') }}</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating mt-3">
             <select class="form-select select2" id="departments" multiple name="permissions[]" aria-label="Tags">
                @foreach ($permissions->filter(function ($tag){
                    return stripos($tag->name, 'department') !== false;
                }) as $tag)
                     <option value="{{ $tag->id }}" {{$role->permissions?->where("id", $tag->id)->first() || old('tags') == $tag->id ? 'selected' : ''}}>{{ __('crud.roles.'.$tag->name) }}</option>
                @endforeach
             </select>
             <label for="floatingSelect">{{ __('crud.departments.title') }}</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating mt-3">
             <select class="form-select select2" id="roles" multiple name="permissions[]" aria-label="Tags">
                @foreach ($permissions->filter(function ($tag){
                    return stripos($tag->name, 'role') !== false;
                }) as $tag)
                     <option value="{{ $tag->id }}" {{$role->permissions?->where("id", $tag->id)->first() || old('tags') == $tag->id ? 'selected' : ''}}>{{ __('crud.roles.'.$tag->name) }}</option>
                @endforeach
             </select>
             <label for="floatingSelect">{{ __('global.roles') }}</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating mt-3">
             <select class="form-select select2" id="projects" multiple name="permissions[]" aria-label="Tags">
                @foreach ($permissions->filter(function ($tag){
                    return stripos($tag->name, 'project') !== false;
                }) as $tag)
                     <option value="{{ $tag->id }}" {{$role->permissions?->where("id", $tag->id)->first() || old('tags') == $tag->id ? 'selected' : ''}}>{{ __('crud.roles.'.$tag->name) }}</option>
                @endforeach
             </select>
             <label for="floatingSelect">{{ __('crud.projects.title') }}</label>
        </div>
    </div>    <div class="col-md-6">
        <div class="form-floating mt-3">
             <select class="form-select select2" id="tasks" multiple name="permissions[]" aria-label="Tags">
                @foreach ($permissions->filter(function ($tag){
                    return stripos($tag->name, 'task') !== false;
                }) as $tag)
                     <option value="{{ $tag->id }}" {{$role->permissions?->where("id", $tag->id)->first() || old('tags') == $tag->id ? 'selected' : ''}}>{{ __('crud.roles.'.$tag->name) }}</option>
                @endforeach
             </select>
             <label for="floatingSelect">{{ __('crud.tasks.title') }}</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating mt-3">
             <select class="form-select select2" id="invoices" multiple name="permissions[]" aria-label="Tags">
                @foreach ($permissions->filter(function ($tag){
                    return stripos($tag->name, 'invoice') !== false;
                }) as $tag)
                     <option value="{{ $tag->id }}" {{$role->permissions?->where("id", $tag->id)->first() || old('tags') == $tag->id ? 'selected' : ''}}>{{ __('crud.roles.'.$tag->name) }}</option>
                @endforeach
             </select>
             <label for="floatingSelect">{{ __('crud.invoices.title') }}</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating mt-3">
             <select class="form-select select2" id="companies" multiple name="permissions[]" aria-label="Tags">
                @foreach ($permissions->filter(function ($tag){
                    return stripos($tag->name, 'company') !== false;
                }) as $tag)
                     <option value="{{ $tag->id }}" {{$role->permissions?->where("id", $tag->id)->first() || old('tags') == $tag->id ? 'selected' : ''}}>{{ __('crud.roles.'.$tag->name) }}</option>
                @endforeach
             </select>
             <label for="floatingSelect">{{ __('crud.companies.title') }}</label>
        </div>
    </div>
    {{-- @foreach ($permissions as $permission)
    <div class="col-md-3 mt-3">
        <div class="form-check form-switch">
            <input type="checkbox" class="form-check-input permissions-check" name="permissions[{{$permission->id}}]" id="{{$permission->name}}" {{$role->permissions->where('id', $permission->id)->first() ? 'checked' : ''}} role="switch">
            <label for="{{$permission->name}}" class="form-check-label mt-1 ms-1">{{$permission->name}}</label>
        </div>
    </div>
    @endforeach --}}
</div>
