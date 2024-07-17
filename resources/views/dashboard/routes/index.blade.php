@extends('layouts.dashboard', ['section' => 'Routes'])

@section('styles')
<style>
    #map {
        height: 600px;
        width: 100%;
    }
    .calendar-days>* {
        width: calc(700px / 7);
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 28px;
    }
    .calendar-head>* {
        width: calc(700px / 7);
        display: inline-block;
        text-align: center;
        font-size: 20px;
    }
</style>
@endsection
@section('content')
    <div class="mt-2">
        <span class="h2 d-inline-block mt-1">
            <b>{{__('crud.routes.title')}}</b><span class="text-muted"></span>
        </span>
        <a class="btn btn-primary d-inline-block ms-3 align-top" href="{{route('dashboard.statuses.create')}}">
            <span class="px-4"><i class="bx bx-plus mt-1"></i>{{__('global.create')}} {{ __('crud.routes.title_singular')}}</span>
        </a>
    </div>

    <div class="row mt-3">
        <div class="col-md-6">
            <div id="map" style=""></div>
        </div>
        <div class="col-md-6">
            <div>
                <div class="card border-0">
                    <div class="card-header border-0">
                        <div class="row">
                            <div class="col-10">
                                <h3><i class='bx bx-map-alt' ></i> Week Routes</h3>
                            </div>
                            <div class="col-2 text-end">
                                <span class="text-muted"><i class='bx bx-chevron-down align-middle mt-2' style="font-size: 24px;"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @foreach ($routes as $route)
                            <div class="row @if(!$loop->last) border-bottom pb-3 @endif mb-3">
                                <div class="col-1">
                                    <div class="circle" style="background-color: {{$route->color}}"></div>
                                </div>
                                <div class="col-11">
                                    <div class="ms-3">
                                        <span class="h6"><b>{{ $route->name }}</b> <span class="badge bg-light text-dark border">{{$loop->last ? '' : 'In Progress'}}</span></span>
                                        <p class="text-muted mb-0 mt-1" style="font-size: 12px">{{$loop->last ? '22-07-2024 - 24-07-2024' : '17-07-2024 - 20-07-2024'}}</p>
                                        <p class="text-muted mb-0">{{ $route->description }}</p>

                                        <div class="avatar-group mt-1">
                                            @foreach ($users as $task_user)
                                                <div class="avatar avatar-s">
                                                    <img src="{{ $task_user->profile_url}}" alt="avatar" class="rounded-circle">
                                                </div>
                                            @endforeach

                                        @if($users->count() > 3)
                                                <div class="avatar avatar-s">
                                                    <div class="avatar-name rounded-circle">
                                                        <span>+{{ $users->count() - 3 }}</span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <div class="card border-0">
                    <div class="card-header border-0">
                        <h3><i class='bx bx-calendar' ></i> Open Dates</h3>
                    </div>
                    <div class="card-body text-center ms-5">
                        <div class="month d-flex flex-column gap-3">
                            <div style="font-size: 30px">{{ $calendar['currentDate']->format('F') }}</div>
                            <div class="calendar-head d-flex justify-content-center align-items-center mb-2" style="color: #b1aeb9;">
                                <div>lu</div>
                                <div>ma</div>
                                <div>mi</div>
                                <div>ju</div>
                                <div>vi</div>
                                <div>sá</div>
                                <div>do</div>
                            </div>
                            <div class="calendar-days d-flex flex-wrap" style="row-gap: 1.2rem!important">
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
                                    @if ($date["day"]->eq($today)){
                                        background-color: red;
                                    }
                                    @endif
                                    ">
                                        <b>{{ $date['day']->format('j')}}</b>
                                    </div>
                                @endforeach

                                @for ($i = 0; $i < $calendar['diffFinal']; $i++)
                                    <div class="day"> </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAni3pSi1eilgs1l0VuXAS2K2VqTkhHbKA&libraries=geometry"></script>

<script>
    function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 6,
            center: {lat: 40.4637, lng: -3.7492} // Center of the ES
        });

        var routes = @json($routes);

        routes.forEach(function(route) {
            var start = route.coordinates.start.split(',').map(Number);
            var end = route.coordinates.end.split(',').map(Number);
            var color = route.color;

            var directionsService = new google.maps.DirectionsService();
            var directionsRenderer = new google.maps.DirectionsRenderer({
                map: map,
                polylineOptions: {
                    strokeColor: color
                }
            });

            var request = {
                origin: {lat: start[0], lng: start[1]},
                destination: {lat: end[0], lng: end[1]},
                travelMode: 'DRIVING'
            };

            directionsService.route(request, function(result, status) {
                if (status == 'OK') {
                    directionsRenderer.setDirections(result);
                }
            });
        });
    }

    google.maps.event.addDomListener(window, 'load', initMap);
</script>
@endsection
