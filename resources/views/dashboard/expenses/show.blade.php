@extends('layouts.dashboard', ['section' => 'Expenses'])

@section('content')
    <div class="row h-100">
        <div class="col-md-7 border-end">
            <div class="mt-2">
                <div class="row">
                    <div class="col-md-10">
                        <span class="h2 mt-1">
                            <a href="{{ route('dashboard.expenses.index') }}" class="btn btn-outline-primary">
                                <span class="px-1">
                                    <i class='bx bx-left-arrow-alt'></i>
                                    </span>
                            </a>
                            <b class="text-capitalize ms-2">{{ $expense->name }}</b>
                        </span>
                        <br>
                    </div>

                    <div class="col-md-2">
                        <div class="text-end">
                            <div class="dropdown">
                                <button class="btn btn-options" type="button" id="dropdownMenuButton"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class='bx bx-dots-horizontal-rounded'></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li>
                                        <form action="{{ route('dashboard.expenses.destroy', $expense->id)}}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="dropdown-item text-danger"><i class='bx bx-trash'></i>
                                                Remove</button>
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
                                <p class="h3 mb-4">
                                    <b><i class='bx bx-info-circle'></i> Details <span
                                            class="fs-6 text-muted">({{ $expense->created_at->format('Y/m/d') }})</span></b>
                                </p>

                                <div class="fs-5">
                                    <p><b>Name:</b><span class="ms-2 text-capitalize">{{ $expense->name }}</span></p>
                                    <p><b>Project</b> <span class="ms-2">{{ $expense->project->name }}</span></p>
                                    <p><b>Amount:</b> <span class="ms-2">{{ $expense->amount }} $</span></p>
                                    <p><b>Quantity:</b> <span class="ms-2">{{ $expense->quantity ?? '-' }}</span></p>
                                    <p>
                                        <b>Facturated:</b>
                                        <span class="ms-2 badge {{$expense->facturable ? 'bg-danger' : 'bg-success'}}">{{ $expense->facturable ? 'Non-Facturated' : 'Facturated' }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="px-3">
                <div class="mt-2 border-bottom">
                    <p class="h4 d-inline-block mt-1">
                        <b>Bills</b>
                    </p>

                    @foreach ($expense->bills as $file)
                        <div class="row border-top border-bottom p-3">
                            <div class="row">
                                <div class="col-md-10">
                                    <a href="{{asset('storage/' . $file->path)}}" target="__blank">
                                        <img src="{{asset('storage/' . $file->path)}}" style="width: 300px;" alt="">
                                    </a>
                                </div>
                                <div class="col-md-2">
                                    <div class="dropdown">
                                        <button class="btn btn-options" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class='bx bx-dots-horizontal-rounded'></i>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <li>
                                                <form action="{{route('dashboard.expenses.delete_file', $file->id)}}" method="POST">
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
                    @endforeach

                    @if($expense->bills->isEmpty())
                        <div class="text-center p-3">
                            <p class="text-muted">No bills uploaded yet!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
