@csrf
<div class="row">
    <div class="col-md-10">
        <div class="form-floating mt-3">
            <select class="form-select select2" id="user_id" name="user_id">
              @foreach ($users as $user)
                <option value="{{$user->id}}" {{($assignment->user_id == $user->id || old('project') == $user->id) ? 'selected' : ''}}>{{ $user->name }}</option>
              @endforeach
            </select>
            <label for="floatingSelect">{{ __('crud.assignments.fields.user') }} <span class="text-danger">*</span></label>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-5">
        <div class="form-floating mt-3">
            <input type="date" class="form-control flatpicker" id="extract_date" name="extract_date" placeholder="Extract date" value="{{ old('extract_date') ?? $assignment->extract_date ?? now()->toDateString()}}">
            <label for="extract_date">{{ __('crud.assignments.fields.extract_date') }}</label>
        </div>
    </div>
    <div class="col-md-5">
        <div class="form-floating mt-3">
            <input type="date" class="form-control flatpicker" id="return_date" name="return_date" placeholder="Return date" value="{{ old('return_date') ?? $assignment->return_date }}">
            <label for="return_date">{{ __('crud.assignments.fields.return_date') }}</label>
        </div>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-10">
        <multi-assignment :inventory-items="{{json_encode($inventoryArray)}}" :assignments="{{json_encode($assignment->items->toArray())}}"></multi-assignment>

        @if ($errors->has('items'))
            <span class="text-danger">{{ $errors->first('items') }}</span>
        @endif
    </div>
</div>

