@extends('layouts.dashboard', ['section' => 'Items'])

@section('content')
    <div class="row h-100">
        <div class="col-md-11 border-end">
            <div class="mt-2">
                <div class="row">
                    <div class="col-md-10">
                        <span class="h2 mt-1">
                            <a href="{{ route('dashboard.inventories.index') }}" class="btn btn-outline-primary">
                                <span class="px-1">
                                    <i class='bx bx-left-arrow-alt'></i>
                                    </span>
                            </a>
                            <b class="text-capitalize">{{ $inventory->name }}</b>
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
                                            href="{{ route('dashboard.inventories.edit', $inventory->id) }}"><i
                                                class='bx bx-edit'></i> Edit</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <form action="{{ route('dashboard.inventories.destroy', $inventory->id)}}"
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
                                            class="fs-6 text-muted">({{ $inventory->created_at->format('Y/m/d') }})</span></b>
                                </p>

                                <div class="fs-5">
                                    <p><b>Name:</b><span class="ms-2 text-capitalize">{{ $inventory->name }}</span></p>
                                    <p><b>Reference</b> <span class="ms-2">{{ $inventory->reference }}</span></p>
                                    <p><b>Stock:</b> <span class="ms-2">{{ $inventory->stock }}</span></p>
                                    <p><b>Price:</b> <span
                                            class="ms-2">{{ $inventory->price ? $inventory->price : '-' }}</span> $</p>
                                    <p><b>Shop place:</b> <span
                                            class="ms-2">{{ $inventory->shop_place ? $inventory->shop_place : '-' }}</span>
                                    </p>
                                    <p><b>Description:</b><span class="text-muted ms-2">
                                            @if ($inventory->description)
                                                {{ $inventory->description }}@else-
                                            @endif
                                        </span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-1">
                                <div class="row">

                                    <div class="col-12 mt-3">
                                        <div class="card bg-transparent">
                                            <div class="card-header p-2 p-md-4">
                                                <div class="row d-flex align-items-center">
                                                    <div class="col-7">
                                                        <div>
                                                            <h4 class="mb-0 hidden-md-down" ><b><i class='bx bx-clipboard'></i>
                                                                    Assignments</b></h4>
                                                            <p class="text-muted mb-0">Assigned {{ $inventory->name }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-5">
                                                        <div class="text-end">
                                                            <div style=" display:flex; justify-content:right; align-items:center;">
                                                            <div class="d-flex idea justify-content-around align-items-center flex-1">
                                                                    <div class="d-left">
                                                                        {{ $inventory->remaining_stock}}</div>
                                                                    <div
                                                                        class="d-right">
                                                                        {{ $inventory->stock }}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-borderless table-hover">
                                                        <thead class="border-bottom">
                                                            <tr>
                                                                <th scope="col" colspan="2">USER</th>
                                                                <th scope="col" class="width-2"></th>
                                                                <th scope="col">QUANTITY</th>
                                                                <th scope="col">ASSIGNMENT DAY</th>
                                                                <th scope="col">RETURN DAY</th>
                                                                <th scope="col"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($inventory->UsersInventories->sortBy('return_date')->take(4) as $assignment)
                                                                <tr class="table-entry align-middle border-bottom">
                                                                    <td colspan="2" class="width-2">
                                                                        <a
                                                                            href="{{ route('dashboard.clients.show', $assignment->user?->id) }}"><span
                                                                                class="text-capitalize">{{ $assignment->user?->name }}</span></a>
                                                                    </td>
                                                                    <td></td>
                                                                    <td>
                                                                        <span>{{ $assignment->quantity }}</span>
                                                                    </td>
                                                                    <td>
                                                                        <span>{{ $assignment->extract_date }}</span>
                                                                    </td>
                                                                    <td>
                                                                        <span class="{{ $assignment->return_date < now()->addDays(3) ? 'text-danger' : 'text-muted' }}">{{ $assignment->return_date }}</span>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <div class="dropdown">
                                                                            <button class="btn btn-options" type="button"
                                                                                id="dropdownMenuButton"
                                                                                data-bs-toggle="dropdown"
                                                                                aria-expanded="false">
                                                                                <i
                                                                                    class='bx bx-dots-horizontal-rounded'></i>
                                                                            </button>
                                                                            <ul class="dropdown-menu"
                                                                                aria-labelledby="dropdownMenuButton">
                                                                                <li><a class="dropdown-item"
                                                                                        href="{{ route('dashboard.tasks.edit', $assignment->id) }}"><i
                                                                                            class='bx bx-edit'></i> Edit</a>
                                                                                </li>
                                                                                <li>
                                                                                    <hr class="dropdown-divider">
                                                                                </li>
                                                                                <li>
                                                                                    <form
                                                                                        action="{{ route('dashboard.tasks.destroy', $assignment->id) }}"
                                                                                        method="POST">
                                                                                        @csrf
                                                                                        @method('DELETE')
                                                                                        <button
                                                                                            class="dropdown-item text-danger"><i
                                                                                                class='bx bx-trash'></i>
                                                                                            Remove</button>
                                                                                    </form>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach

                                                            @if (!count($inventory->UsersInventories))
                                                                <tr>
                                                                    <td colspan="7" class="text-center py-5">
                                                                        <span class="text-muted">No assignments
                                                                            found!</span>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="9" class="py-3 text-end">
                                                                    <a href="{{ route('dashboard.assignments.index') }}">View
                                                                        All ></a>
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
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
    </div>
@endsection
