@csrf
<div class="row">
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <input type="text" class="form-control" id="name" name="name" placeholder="{{__('crud.companies.fields.name')}}" value="{{ old('name') ?? auth()->user()->name }}">
            <label for="title">{{__('crud.companies.fields.name')}} <span class="text-danger">*</span></label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <select class="form-select" id="type" name="type" aria-label="Type">
                <option value="">--</option>
                @foreach ($leaveTypes as $types)
                    <option value="{{$types->id}}" {{ old('type') == $types->id ? 'selected' : ''}}>{{$types->name}}</option>
                @endforeach
            </select>
            <label for="type">{{__('crud.leaves.fields.type')}} <span class="text-danger">*</span></label>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <select class="form-select" id="duration" name="duration" aria-label="Duration">
                <option value="single" {{old('duration') == 'single' ? 'selected' : ''}}>Single</option>
                <option value="multiple" {{old('duration') == 'multiple' ? 'selected' : ''}}>Multiple</option>
            </select>
            <label for="duration">{{__('crud.leaves.fields.duration')}} <span class="text-danger">*</span></label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <input type="date" class="form-control" id="date" name="date" placeholder="{{__('crud.leaves.fields.date')}}" value="{{ old('date') }}">
            <label for="date">{{__('crud.leaves.fields.date')}} <span class="text-danger">*</span></label>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-floating mt-3">
            <select class="form-select select2" id="user_id" name="user_id" aria-label="Type">
                @foreach ($users as $user)
                    <option value="{{$user->id}}" {{ auth()->user()->id == $user->id || old('user_id') == $user->id ? 'selected' : ''}}>{{$user->name}}</option>
                @endforeach
            </select>
            <label for="user">{{__('crud.leaves.fields.user')}} <span class="text-danger">*</span></label>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-12">
        <textarea name="reason" id="reason" rows="3" class="form-control" placeholder="e.g. Feeling not well"></textarea>
    </div>
</div>
