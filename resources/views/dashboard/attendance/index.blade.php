@extends('layouts.dashboard', ['section' => 'Attendance'])

@section('content')
    <div class="mt-2 row">
        <div class="col-md-12">
            <div class="position-relative text-center mb-4">
                @if($month > 1)
                    <a class="me-4 h4 text-dark text-decoration-none" href="?month={{$month-1}}{{request()->has('user') ? '&user=' . request()->get('user') : ''}}"><i class='bx bx-chevron-left'></i></a>
                @endif
                <span class="h4">{{ date('F', mktime(0, 0, 0, $month, 1, $year)) }} {{$year}}</span>
                @if($month < 12 && $month < $currentMonth)
                    <a class="ms-4 h4 text-dark text-decoration-none" href="?month={{$month+1}}{{request()->has('user') ? '&user=' . request()->get('user') : ''}}"><i class='bx bx-chevron-right'></i></a>
                @endif
                <a class="btn btn-primary mb-2 position-absolute align-top {{$month == $currentMonth ? 'disabled' : ''}}" href="{{route('dashboard.attendance.download-extract')}}{{request()->has('month') ? '?month=' . request()->input('month')  : ''}}{{request()->has('user') ? '&user=' . request()->get('user') : ''}}" style="right: 0;">
                    <span class="px-4"><i class="bx bx-plus mt-1"></i>{{__('crud.attendance.fields.export_sheet')}}</span>
                </a>
            </div>
        </div>
        <hr>
    </div>

    <div class="row">
        <div class="col-md-9 mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            @include('partials.users.details', ['user' => $user])
                        </div>
                        <div class="col-md-4 mb-2 text-center">
                            <p class="h3 text-muted mb-0 mt-2">{{$totals['worked_hours']}}</p>
                            <span class="h6 text-muted">{{__('crud.attendance.fields.worked_hours')}}</span>
                        </div>
                        <div class="col-md-4 mb-2 text-center">
                            <p class="h3 text-muted mb-0 mt-2">{{$totals['estimated_hours']}}</p>
                            <span class="h6 text-muted">{{__('crud.attendance.fields.estimated_hours')}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mt-3">
            <div class="card">
                <div class="card-body mb-2">
                    <p class="h3 mb-0 mt-2 {{ strpos($totals['balance'], '-') !== false ? 'text-danger' : 'text-muted'}}">{{ $totals['balance']}}</p>
                    <span class="h6 text-muted">{{__('crud.attendance.fields.balance')}}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="card">
            <div class="card-body">
                <div class="row p-1">
                    <div class="col-md-9">
                        <div class="mt-2">
                            <span class="text-muted me-2"><b>{{__('crud.attendance.fields.state')}}</b></span>
                            @if($month < $currentMonth)
                                <span class="badge bg-success me-3">{{__('crud.attendance.fields.close')}}</span>
                                <span class="text-muted">{{__('crud.attendance.fields.close_period')}}</span>
                            @else
                                <span class="badge bg-secondary me-3">IN PROGRESS</span>
                                <span class="text-muted">{{__('crud.attendance.fields.open_period')}}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3 text-end">
                        <button class="btn btn-outline-primary align-top" id="expandAll">{{__('crud.attendance.fields.open_all')}} <i class='
                            bx bx-chevron-down'></i></button>
                        <button class="btn btn-outline-primary align-top d-none" id="closeAll">{{__('crud.attendance.fields.close_all')}} <i class='bx bx-chevron-up'></i></button>
                    </div>
                </div>
                <hr>
                <div id="attendanceDetails" class="table-responsive">
                    <table class="table table-hover">
                        <tr>
                            <th class="text-muted">{{__('global.day')}}</th>
                            <th>{{__('crud.attendance.fields.worked_hours')}} / {{__('crud.attendance.fields.estimated_hours')}}</th>
                            <th class="text-muted">{{__('global.leaves')}}</th>
                            <th></th>
                        </tr>
                        @foreach ($dates as $idx => $date)
                            <tr>
                                <td class="py-3 align-middle">
                                    <p class="mb-0" style="font-size: 20px"><b>{{ $date['day']->format('j M'); }}</b></p>
                                    <span class="text-muted">{{ $date['day']->isoFormat('dddd'); }}</span>
                                </td>
                                <td class="py-3 align-middle" style="font-size: 24px">
                                    <span class="text-muted" id="workedHours-{{$idx}}">{{ $date['worked_hours']; }} / {{ $date['hours_per_day']}}</span>

                                    @php
                                        $showExcessTime = false;
                                        if($date['excess_time'] !== false && !$date['holiday'] || ($date['holiday'] && strpos($date['excess_time'] , '-') === false)) {
                                            $showExcessTime = true;
                                        }
                                    @endphp

                                    <span id="excessTime-{{$idx}}" class="badge {{ strpos($date['excess_time'] , '-') !== false ? 'bg-danger' : 'bg-warning'}} ms-2 {{ !$showExcessTime ? 'd-none' : ''}}">{{$date['excess_time']}}</span>

                                    @if ($date['holiday'])
                                        <span class="badge ms-2" style="{{$date['holiday']->leaveType->styles}}" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ $date['holiday']->leaveType->name }}"><i class='bx bxs-plane-take-off'></i></span>
                                    @endif
                                </td>
                                <td class="py-3 align-middle"></td>
                                <td class="py-3 align-middle text-end">
                                    <button class="btn btn-outline-secondary open-details" data-idx="{{$idx}}"><i class='bx bx-chevron-down'></i></button>
                                </td>
                            </tr>
                            <tr id="details-{{$idx}}" class="d-none">
                                <td colspan="4">
                                    <div class="row p-4">
                                        <div class="col-md-4">
                                            <form action="{{route('dashboard.attendance.update', $user->id)}}" method="PUT" id="frmAttendance-{{$idx}}">
                                                @csrf
                                                <input type="hidden" name="date" value="{{$date['day']->format('y-m-d')}}">
                                                <div id="attendanceTimes-{{$idx}}">
                                                    @foreach ($date['attendances'] ?? [] as $id => $attendance)
                                                        <input type="hidden" name="ids[{{$id}}]" value="{{$attendance->id}}">
                                                        <div class="mt-3">
                                                                <span class="me-3"><i class='bx bx-alarm-exclamation align-middle' style="font-size: 20px"></i></span>
                                                                <input type="time" class="form-control w-25 d-inline-block" name="check_in[]" value="{{$attendance->check_in?->format('H:i')}}" {{$month != $currentMonth ? 'disabled' : ''}}>
                                                                <span class="mx-2">-</span>
                                                                <input type="time" class="form-control w-25 d-inline-block" name="check_out[]" value="{{$attendance->check_out?->format('H:i')}}" {{$month != $currentMonth ? 'disabled' : ''}}>

                                                                @if ($attendance->isEdited)
                                                                    <span class="ms-3" style="color: var(--primary-color)"><i class='bx bx-history align-middle' style="font-size: 20px"></i></span>
                                                                @endif
                                                        </div>
                                                    @endforeach

                                                    @if(!count($date['attendances']))
                                                        <div>
                                                            <span class="me-3"><i class='bx bx-alarm-exclamation align-middle' style="font-size: 20px"></i></span>
                                                            <input type="time" class="form-control w-25 d-inline-block" name="check_in[]" {{$month != $currentMonth ? 'disabled' : ''}}>
                                                            <span class="mx-2">-</span>
                                                            <input type="time" class="form-control w-25 d-inline-block" name="check_out[]" {{$month != $currentMonth ? 'disabled' : ''}}>
                                                        </div>
                                                    @endif
                                                </div>

                                                @if($month == $currentMonth && !$date['day']->isFuture())
                                                    <button class="btn btn-primary d-block mt-3" type="button" onclick="addTime({{$idx}})"><i class='bx bx-plus' ></i> {{__('global.add')}}</button>
                                                @endif
                                            </form>
                                        </div>
                                        <div class="col-md-8 text-end">
                                            @can('attendance_edit')
                                                @if ($month == $currentMonth && !$date['day']->isFuture())
                                                    <button class="btn btn-outline-primary save-attendances" type="button" data-idx="{{$idx}}">{{__('global.save')}}</button>
                                                @endif
                                            @endcan
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $('.open-details').click(function() {
        $('#details-' + $(this).data('idx')).toggleClass('d-none');
        $(this).find('i').toggleClass('bx-chevron-down bx-chevron-up');
    });

    $('#expandAll').click(function() {
        $('#closeAll').toggleClass('d-none');
        $('#expandAll').toggleClass('d-none');
        $('.open-details').each(function() {
            $('#details-' + $(this).data('idx')).removeClass('d-none');
            $(this).find('i').removeClass('bx-chevron-down').addClass('bx-chevron-up');
        });
    });

    $('#closeAll').click(function() {
        $('#closeAll').toggleClass('d-none');
        $('#expandAll').toggleClass('d-none');
        $('.open-details').each(function() {
            $('#details-' + $(this).data('idx')).addClass('d-none');
            $(this).find('i').removeClass('bx-chevron-up').addClass('bx-chevron-down');
        });
    });

    $('.save-attendances').click(function() {
        let idx = $(this).data('idx');
        form = $('#frmAttendance-' + idx).closest('form');

        $.ajax({
            url: form.attr('action'),
            type: 'PUT',
            data: form.serialize(),
            success: function(response) {
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    title: 'Jornada updated successfully',
                    showConfirmButton: false,
                    position: 'top-end',
                    timer: 1000
                });

                $('#workedHours-' + idx).html(response['worked_hours'] + ' / ' + response['hours_per_day']);
                if(response['excess_time']) {
                    $('#excessTime-' + idx).removeClass('d-none');
                    $('#excessTime-' + idx).html(response['excess_time']);

                    if(response['excess_time'].startsWith('-')) {
                        $('#excessTime-' + idx).removeClass('bg-warning').addClass('bg-danger');
                    } else {
                        $('#excessTime-' + idx).removeClass('bg-danger').addClass('bg-warning');
                    }
                } else {
                    $('#excessTime-' + idx).addClass('d-none');
                    $('#excessTime-' + idx).html('00h 00m');
                }

            }
        });
    });

    function addTime(idx) {
        $('#attendanceTimes-' + idx).append(
            '<div class="mt-3">' +
                '<span class="me-3"><i class="bx bx-alarm-exclamation align-middle" style="font-size: 20px"></i></span>' +
                '<input type="time" class="form-control w-25 d-inline-block" name="check_in[]">' +
                '<span class="mx-2">-</span>' +
                '<input type="time" class="form-control w-25 d-inline-block" name="check_out[]">' +
            '</div>'
        );
    }
</script>
@endsection
