@csrf
<div class="row">
    <div class="col-md-12">
        <label for="content">{{__('crud.notes.fields.content')}} <span class="text-danger">*</span></label>
        <textarea name="content" id="content" cols="30" rows="4" maxlength="1000" class="form-control textricheditor @if ($errors->has('content')) is-invalid @endif" placeholder="Something to never forget!">{{ old('content') ?? $note->content }}</textarea>
        <div class="mt-0 text-end">
            <span class="text-muted"><span id="contentCountChar">0</span>/1000</span>
        </div>
        @if ($errors->has('content'))
            <span class="text-danger">{{ $errors->first('content') }}</span>
        @endif
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-12">
        <div class="form-floating mt-3">
            <input type="datetime-local" class="form-control flatpicker hasTime" id="date" name="date" value="{{ old('date') ?? $note->date }}" step="1">
            <label for="title">{{__('crud.notes.fields.date')}}</label>
        </div>
    </div>
</div>
