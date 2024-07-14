@extends('layouts.dashboard', ['section' => 'Holiday'])

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card mt-5 me-4">
                <div class="card-header text-center fs-6 py-3 justify-content-center d-flex">
                    {{__("crud.holidays.authorization")}}
                </div>
                <div class="card-body pt-4">
                    <blockquote class="blockquote mb-0 d-flex justify-content-around">
                        <div class="generado d-flex flex-column align-items-end">
                            <div class="fs-2">30</div>
                            <div class="fs-6 text-muted">{{__("crud.holidays.total")}}</div>
                        </div>
                        <div class="generado d-flex flex-column align-items-center">
                            <div class="fs-2">{{ 30 - $leaves->count() }}</div>
                            <div class="fs-6 text-muted">{{__("crud.holidays.available")}}</div>
                        </div>
                        <div class="generado d-flex flex-column align-items-center">
                            <div class="fs-2">{{ $leaves->count() }}</div>
                            <div class="fs-6 text-muted">{{__("crud.holidays.used")}}</div>
                        </div>
                    </blockquote>
                </div>
            </div>
            <div class="fs-1 mt-3" style="color:var(--primary-color)"><i class='bx bx-calendar-alt'></i></div>
            <h5><b>{{__("crud.holidays.other_leaves")}}</b></h5>
            <p class="w-75">{{__("crud.holidays.other_leaves_desc")}}</p>

            @if (count($oldLeaves))
                <div class="d-flex flex-column gap-2">
                    <div class="card">
                        <div class="row p-1">
                            <div class="col-md-4 d-flex justify-content-center align-items-center">
                                <div class="calendar-day-style border">
                                    <div class="head d-flex justify-content-center"
                                        style="background: {{ $oldLeaves->first()?->leaveType?->data['background'] }}">
                                        {{ $oldLeaves->first()?->date?->format('M') }}.
                                    </div>
                                    <div class="fs-6 d-flex justify-content-center">
                                        {{ $oldLeaves->first()?->date?->format('d') }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7 d-flex flex-col justify-content-center align-items-left flex-column">
                                <p>
                                    <b class="text-capitalized fs-6">
                                        <div>{{ $oldLeaves->first()?->leaveType?->name }}</div>
                                    </b>
                                <div class="text-capitalize">{{ $oldLeaves->first()?->reason }}</div>
                                </p>
                            </div>
                            <div class="col-md-1">
                                <i id="btn" class='bx bx-chevron-down' style="cursor:pointer"></i>
                            </div>
                        </div>
                    </div>
                    <div class="d-none" id="cards-container">
                        @foreach ($oldLeaves->skip(1) as $leave)
                            <div class="card">
                                <div class="row p-2">
                                    <div class="col-md-4 d-flex justify-content-center align-items-center overflow-hidden">
                                        <div class="border calendar-day-style">
                                            <div class="head d-flex justify-content-center"
                                                style="background: {{ $leave?->leaveType?->data['background'] }}">
                                                {{ $leave->date->format('M') }}.
                                            </div>
                                            <div class="fs-6 d-flex justify-content-center">
                                                {{ $leave->date->format('d') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8 d-flex flex-column justify-content-start align-items-start">
                                        <p>
                                            <b class="fs-6 text-capitalize">{{ $leave?->leaveType?->name }}</b>
                                        <div class="text-capitalize">{{ $leave->reason }}</div>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="text-muted text-center mt-5">
                    < {{__("crud.holidays.no_prev")}} >
                </div>
            @endif
        </div>
        <div class="col-md-8 mt-3">
            <h5 class="text-center">{{ $calendars[1]['dates'][1]['day']->format('Y') }}</h5>
            <div class="d-flex justify-content-center align-items-top flex-wrap gap-5 mt-3 border-top pt-4">
                @foreach ($calendars as $calendar)
                    <div class="month d-flex flex-column gap-3" style="max-width: 184px">
                        <div style="font-size: 14px">{{ $calendar['currentDate']->format('F') }}</div>
                        <div class="calendar-head d-flex justify-content-center align-items-center" style="color: #b1aeb9;">
                            <div>lu</div>
                            <div>ma</div>
                            <div>mi</div>
                            <div>ju</div>
                            <div>vi</div>
                            <div>sá</div>
                            <div>do</div>
                        </div>
                        <div class="calendar-days d-flex flex-wrap" style="row-gap: 0.5rem!important">
                            @for ($i = 0; $i < $calendar['diffInitial']; $i++)
                                <div class="day"> </div>
                            @endfor
                            @foreach ($calendar['dates'] as $date)
                                <div class="day"
                                    @if ($date['is_holiday']) data-bs-toggle="popover" data-bs-placement="bottom"
                                data-bs-custom-class="custom"
                                data-bs-html="true"
                                data-bs-content="<div class='d-flex flex-column text-color-success'><div class='fs-5'>{{ $date['day']->format('d') }} of {{ $date['day']->format('M') }}</div><div>{{ $date['is_holiday']->name }}</div></div>" @endif
                                    style="
                                    font-weight:500;
                                    @if ($date['day']->format('l') == 'Sunday' || $date['day']->format('l') == 'Saturday') color: #b1aeb9; @endif

                            @if ($date['is_holiday']) background-color:{{ $date['is_holiday']?->data['background'] }};
                                color: {{ $date['is_holiday']?->data['color'] }};

                                @if ($date['first_day'])
                                    border-top-left-radius: 50px;
                                    border-bottom-left-radius: 50px; @endif

                                @if ($date['last_day']) border-top-right-radius: 50px;
                                    border-bottom-right-radius: 50px; @endif

                            @endif

                            @if ($date['day']->format('d') == now()->format('d') && $date['day']->format('m') == now()->format('m')) border: 2px solid red; border-radius: 50px; @endif
                            ">
                                    <b>{{ $date['day']->format('j') }}</b>
                                </div>
                            @endforeach

                            @for ($i = 0; $i < $calendar['diffFinal']; $i++)
                                <div class="day"> </div>
                            @endfor
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    @parent
    <script>
        $(document).ready(function() {
            // Inicializar todos los popovers
            $('[data-bs-toggle="popover"]').popover();

            // Cerrar todos los popovers abiertos al hacer clic en el botón
            $('.day').on('click', function() {
                $('[data-bs-toggle="popover"]').not(this).popover('hide');
            });



            $('#btn').click(function() {
                $('#cards-container').toggleClass('d-none');
                $(this).toggleClass('bx-chevron-up bx-chevron-down')
            });
        });
    </script>
@endsection
