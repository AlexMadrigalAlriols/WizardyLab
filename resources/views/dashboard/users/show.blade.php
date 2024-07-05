@extends('layouts.dashboard', ['section' => 'Users'])

@section('content')
<div class="row h-100">
    <div class="col-md-11 border-end">
        <div class="mt-2">
            <div class="row">
                <div class="col-md-10">
                    <span class="h2 mt-1">
                        <a href="{{ route('dashboard.users.index') }}" class="btn btn-outline-primary">
                            <span class="px-1">
                                <</span>
                        </a>
                        <b>{{ $user->name }} @if($user->department)<span class="text-muted">({{$user->department?->name}})</span>@endif</b>
                    </span>
                    <br>
                </div>

                <div class="col-md-2">
                    <div class="text-end">
                        <div class="dropdown">
                            <button class="btn btn-options" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class='bx bx-dots-horizontal-rounded'></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><a class="dropdown-item" href="{{route('dashboard.users.edit', $user->id)}}"><i class='bx bx-edit' ></i> Edit</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{route('dashboard.users.destroy', $user->id)}}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="dropdown-item text-danger"><i class='bx bx-trash' ></i> Remove</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="row">
                            <p class="h4">
                                <b><i class='bx bx-info-circle' ></i> Details</b>
                            </p>

                            <div>
                                <p><b>{{__('crud.users.fields.email')}}:</b> <a href="mailto:{{$user->email}}" class="ms-2">{{$user->email}}</a></p>
                                <p><b>{{__('crud.users.fields.gender')}}:</b> {{__("crud.users.".$user->gender) ?? '-'}}</p>
                                <p><b>{{__('crud.users.fields.country')}}:</b> <span class="text-muted ms-2">{{$user->country?->name ?? '-'}}</span></p>
                                <p><b>{{__('crud.users.fields.role')}}:</b> <span class="text-muted ms-2">{{$user->role?->name ?? '-'}}</span></p>
                                <p><b>{{__('crud.users.fields.report_to')}}:</b> <a href="{{$user->reportinguser?->id?route("dashboard.users.show", $user->reportinguser?->id):"#"}}"><span class="ms-2">{{$user->reportinguser?->name ?? '-'}}</span></a></p>
                                <p><b>{{__('crud.users.fields.birthday_date')}}:</b> <span>{{$user->birthday_date->format("Y-m-d") ?? '-'}}</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row mb-1">
                            <p class="h4 d-inline-block mt-1">
                                <b><i class='bx bx-line-chart'></i> Tasks</b>
                            </p>

                            <canvas id="activityChart" style="width:100%;max-width:700px"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const xValues = ['Competed', 'In progress', 'Canceleds'];
const yValues = [5, 2, 10];
var barColors = [
  "#b91d47",
  "#00aba9",
  "#2b5797",
  "#e8c3b9",
  "#1e7145"
];

new Chart("activityChart", {
  type: "pie",
  data: {
    labels: xValues,
    legend: false,
    datasets: [{
      backgroundColor: barColors,
      borderColor: "rgba(0,0,255,0.1)",
      data: yValues,
      fill: false
    }]
  }
});
</script>
@endsection
