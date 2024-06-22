@csrf
<div class="row">
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <select class="form-select select2 @if($errors->has('user_id')) is-invalid @endif" id="user_id" name="user_id" aria-label="Type">
                @foreach ($users as $user)
                    <option value="{{$user->id}}" {{ auth()->user()->id == $user->id || old('user_id') == $user->id ? 'selected' : ''}}>{{$user->name}}</option>
                @endforeach
            </select>
            <label for="user">{{__('crud.leaves.fields.user')}} <span class="text-danger">*</span></label>
        </div>

        @if ($errors->has('user_id'))
            <span class="text-danger">{{ $errors->first('user_id') }}</span>
        @endif
    </div>
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <select class="form-select @if($errors->has('type')) is-invalid @endif" id="type" name="type" aria-label="Type">
                <option value="">--</option>
                @foreach ($leaveTypes as $types)
                    <option value="{{$types->id}}" {{ old('type') == $types->id ? 'selected' : ''}}>{{$types->name}}</option>
                @endforeach
            </select>
            <label for="type">{{__('crud.leaves.fields.type')}} <span class="text-danger">*</span></label>
        </div>

        @if ($errors->has('type'))
            <span class="text-danger">{{ $errors->first('type') }}</span>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <select class="form-select @if($errors->has('duration')) is-invalid @endif" id="duration" name="duration" aria-label="Duration">
                <option value="single" {{old('duration') == 'single' ? 'selected' : ''}}>Single</option>
                <option value="multiple" {{old('duration') == 'multiple' ? 'selected' : ''}}>Multiple</option>
            </select>
            <label for="duration">{{__('crud.leaves.fields.duration')}} <span class="text-danger">*</span></label>
        </div>

        @if ($errors->has('duration'))
            <span class="text-danger">{{ $errors->first('duration') }}</span>
        @endif
    </div>
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <input type="date" class="form-control flatpicker @if($errors->has('date')) is-invalid @endif" id="date" name="date" placeholder="{{__('crud.leaves.fields.date')}}" value="{{ old('date') }}">
            <label for="date">{{__('crud.leaves.fields.date')}} <span class="text-danger">*</span></label>
        </div>

        @if ($errors->has('date'))
            <span class="text-danger">{{ $errors->first('date') }}</span>
        @endif
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-12">
        <textarea name="reason" id="reason" rows="3" class="form-control" placeholder="{{__('crud.leaves.example')}}" maxlength="100"></textarea>
        <div class="mt-0 text-end">
            <span class="text-muted"><span id="reasonCountChar">0</span>/100</span>
        </div>
    </div>
</div>
