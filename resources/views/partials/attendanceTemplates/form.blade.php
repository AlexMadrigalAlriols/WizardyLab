@csrf
<div class="row">
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <input type="text" class="form-control @if($errors->has('name')) is-invalid @endif" maxlength="40" id="name" name="name" placeholder="{{__('crud.companies.fields.name')}}" value="{{ old('name') ?? $attendanceTemplate->name }}">
            <label for="title">{{__('crud.companies.fields.name')}} <span class="text-danger">*</span></label>
        </div>

        <div class="mt-0 text-end">
            <span class="text-muted"><span id="nameCountChar">0</span>/40</span>
        </div>

        @if ($errors->has('name'))
            <span class="text-danger">{{ $errors->first('name') }}</span>
        @endif
    </div>
    <div class="col-md-3">
        <label for="background" class="form-label">{{__("global.background_color")}}</label>
        <input type="text" name="background" class="form-control colorpicker" id="background"
            value="{{ old('background') ?? ($attendanceTemplate->data['background'] ?? '#000000') }}" title="Choose your color">
    </div>
    <div class="col-md-3">
        <label for="color" class="form-label">{{__("global.text_color")}}</label>
        <input type="text" name="color" class="form-control colorpicker" id="color"
            value="{{ old('color') ?? ($attendanceTemplate->data['color'] ?? '#FFFFFF') }}" title="Choose your color">
    </div>
</div>

<div class="row table-responsive mt-4">
    <table class="table table-hover">
        <tr>
            <th>{{__("crud.attendanceTemplates.fields.weekday")}}</th>
            <th>{{__("crud.attendanceTemplates.fields.start_time")}}</th>
            <th>{{__("crud.attendanceTemplates.fields.end_time")}}</th>
            <th>{{__("crud.attendanceTemplates.fields.break_time")}}</th>
        </tr>
        @foreach ($attendanceTemplate->weekDays as $weekDay)
            <tr>
                <td class="align-middle">{{$weekDay->weekday}}</td>
                <td class="align-middle">
                    <input type="time" class="form-control" name="start_time[{{$weekDay->weekday}}]" value="{{ old('start_time['.$weekDay->weekday.']') ?? ($weekDay->start_time?->format('H:i') ?? '09:00') }}">
                </td>
                <td class="align-middle">
                    <input type="time" class="form-control" name="end_time[{{$weekDay->weekday}}]" value="{{ old('end_time['.$weekDay->weekday.']') ?? ($weekDay->end_time?->format('H:i') ?? '18:00') }}">
                </td>
                <td>
                    <input type="time" class="form-control" name="start_break[{{$weekDay->weekday}}]" value="{{ old('start_break['.$weekDay->weekday.']') ?? ($weekDay->start_break?->format('H:i') ?? '11:00') }}">
                    <input type="time" class="form-control mt-2" name="end_break[{{$weekDay->weekday}}]" value="{{ old('end_break['.$weekDay->weekday.']') ?? ($weekDay->end_break?->format('H:i') ?? '11:30') }}">
                </td>
            </tr>
        @endforeach
    </table>
</div>
