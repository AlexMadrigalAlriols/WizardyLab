@extends('layouts.dashboard', ['section' => 'Clients'])

@section('content')
<div class="row h-100">
    <div class="col-md-11 border-end">
        <div class="mt-2">
            <div class="row">
                <div class="col-md-10">
                    <span class="h2 mt-1">
                        <a href="{{ route('dashboard.clients.index') }}" class="btn btn-outline-primary">
                            <span class="px-1">
                                <</span>
                        </a>
                        <b>{{ $client->name }} @if($client->company)<span class="text-muted">({{$client->company?->name}})</span>@endif</b>
                    </span>
                    <br>

                    @if($client->active)
                    <span class="badge bg-success ms-5"><span class="px-2">ACTIVE</span></span>
                    @else
                        <span class="badge bg-danger ms-5"><span class="px-2">INACTIVE</span></span>
                    @endif
                </div>

                <div class="col-md-2">
                    <div class="text-end">
                        <div class="dropdown">
                            <button class="btn btn-options" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class='bx bx-dots-horizontal-rounded'></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><a class="dropdown-item" href="{{route('dashboard.clients.edit', $client->id)}}"><i class='bx bx-edit' ></i> Edit</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{route('dashboard.clients.destroy', $client->id)}}" method="POST">
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
                                <p><b>Email:</b> <a href="#" class="ms-2">{{$client->email}}</a></p>
                                <p><b>Mobile Phone:</b> <a href="#" class="ms-2">{{$client->phone}}</a></p>
                                <p><b>Vat Number:</b> <span class="text-muted ms-2">{{$client->vat_number}}</span></p>
                                <p><b>Currency:</b> <span class="text-muted ms-2">{{$client->currency->name}}</span></p>
                                <p><b>Language:</b> <span class="text-muted ms-2">{{$client->language->name}}</span></p>
                                <p><b>Projects:</b> <a href="{{route('dashboard.projects.index')}}?client_id={{$client->id}}" class="ms-2">{{$client->projects->count()}}</a></p>
                            </div>
                        </div>

                        <div class="row">
                            <p class="h4 mt-4">
                                <b><i class='bx bxs-user-detail' ></i> Address</b>
                            </p>

                            <div>
                                <p><b>Street:</b> <span class="text-muted ms-2">{{$client->address}}</span></p>
                                <p><b>City:</b> <span class="text-muted ms-2">{{$client->city}}</span></p>
                                <p><b>State:</b> <span class="text-muted ms-2">{{$client->state}}</span></p>
                                <p><b>Zip:</b> <span class="text-muted ms-2">{{$client->zip}}</span></p>
                                <p><b>Country:</b> <span class="text-muted ms-2">{{$client->country->name}}</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row mb-1">
                            <p class="h4 d-inline-block mt-1">
                                <b><i class='bx bx-line-chart'></i> Invoices</b>
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
const xValues = ['Paid', 'Unpaid', 'Inprogress'];
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
