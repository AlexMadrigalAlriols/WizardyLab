@extends('layouts.dashboard', ['section' => 'Assignments'])

@section('content')
    <link rel="stylesheet" href="inventories.css">
    <div class="row h-100">
        <div class="col-md-11 border-end">
            <div class="mt-2">
                <div class="row">
                    <div class="col-md-10">
                        <span class="h2 mt-1">
                            <a href="{{ route('dashboard.assignments.index') }}" class="btn btn-outline-primary">
                                <span class="mx-2">
                                    Return
                                </span>
                            </a>
                            <b class="text-capitalize ms-2">Assignment {{ $assignment->user?->name }}</b>
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
                                    <li><a class="dropdown-item"
                                            href="{{ route('dashboard.assignments.edit', $assignment->id) }}"><i
                                                class='bx bx-edit'></i> Edit</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <form action="{{ route('dashboard.assignments.destroy', $assignment->id) }}"
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
                                    <b><i class='bx bx-info-circle'></i> Details @if ($assignment->return_date)
                                            <span class="fs-6 text-muted">({{ $assignment->return_date }})</span>
                                        @endif
                                    </b>
                                </p>

                                <div class="fs-5">
                                    <p><b>User:</b><span class="ms-2 text-capitalize">{{ $assignment->user?->name }}</span>
                                    </p>
                                    <p><b>Item:</b> <a href="{{route('dashboard.inventories.show', $assignment->inventory->id)}}" class="ms-2">{{ $assignment->inventory?->name }} -
                                            {{ $assignment->inventory?->reference }}</a></p>
                                    <p><b>Quantity:</b> <span class="ms-2">{{ $assignment->quantity }}</span></p>
                                    <p><b>Extract date:</b> <span
                                            class="ms-2">{{ $assignment->extract_date ? $assignment->extract_date : '-' }}</span>
                                    </p>
                                    <p><b>Shop place:</b> <span
                                            class="ms-2">{{ $assignment->return_date ? $assignment->return_date : '-' }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-1">
                                <div class="row">

                                    <div class="col-12 mt-3">
                                        <div class="card bg-transparent">
                                            <div class="card-header p-4">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <div>
                                                            <h4 class="mb-0"><b><i class='bx bx-clipboard'></i> Items
                                                                    assigned</b></h4>
                                                            <div class="d-flex align-items-center">
                                                                <p class="text-muted mb-0 d-flex">Assigned to&nbsp;&nbsp;<a
                                                                        href="{{ route('dashboard.inventories.show', $assignment->user?->id) }}"
                                                                        class="text-decoration-none text-capitalize">
                                                                        <div tabindex="0" data-bs-toggle="popover"
                                                                            data-bs-trigger="hover focus"
                                                                            data-bs-content="{{ $assignment->user?->name }}"
                                                                            class="avatar avatar-s d-inline-block"><img
                                                                                src="{{ $assignment->user->profile_img }}"
                                                                                alt="user_image" class="rounded-circle">
                                                                        </div>
                                                                    </a></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <a class="card d-flex flex-row align-items-center bg-transparent border text-dark text-decoration-none w-100" href="{{route('dashboard.inventories.show', $assignment->inventory->id)}}">
                                                    <img class="m-4 shadow img-thumbnail" style="max-width: 150px; aspect-ratio:1/1; object-fit:cover;" src="{{$assignment->inventory->getCoverAttribute()}}" alt="...">
                                                    <div class="card-body">
                                                      <h5 class="card-title">{{$assignment->inventory->name}} <span class="text-muted fs-6">({{$assignment->inventory->reference}})</span></h5>
                                                      <p class="card-text">{{$assignment->inventory->description?$assignment->inventory->description:"-"}}</p>
                                                    </div>
                                                  </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
        const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))
    </script>
@endsection
