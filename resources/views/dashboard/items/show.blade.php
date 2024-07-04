@extends('layouts.dashboard', ['section' => 'Items'])

@section('content')
    <div class="row h-100">
        <div class="col-md-9 border-end">
            <div class="mt-2">
                <div class="row">
                    <div class="col-md-10">
                        <span class="h2 mt-1">
                            <a href="{{ route('dashboard.items.index') }}" class="btn btn-outline-primary">
                                <span class="px-1">
                                    <i class='bx bx-left-arrow-alt'></i>
                                    </span>
                            </a>
                            <b class="text-capitalize ms-2">{{ $item->name }} <span class="text-muted">({{$item->reference}})</span></b>
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
                                            href="{{ route('dashboard.items.edit', $item->id) }}"><i
                                                class='bx bx-edit'></i> Edit</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <form action="{{ route('dashboard.items.destroy', $item->id)}}"
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
                        <div class="col-md-5">
                            <div class="row">
                                <p class="h3 mb-4">
                                    <b><i class='bx bx-info-circle'></i> Details <span
                                            class="fs-6 text-muted">({{ $item->created_at->format('Y/m/d') }})</span></b>
                                </p>

                                <div class="fs-5">
                                    <p><b>Name:</b><span class="ms-2 text-capitalize text-muted">{{ $item->name }}</span></p>
                                    <p><b>Reference</b> <span class="ms-2 text-muted">{{ $item->reference }}</span></p>
                                    <p><b>Stock:</b> <span class="ms-2 text-muted">{{ $item->stock }}</span></p>
                                    <p><b>Price:</b> <span
                                            class="ms-2">{{ $item->price ? $item->price : '-' }}</span> {{ $client->currency->symbol }}</p>
                                    <p><b>Shop place:</b> <span
                                            class="ms-2">{{ $item->shop_place ? $item->shop_place : '-' }}</span>
                                    </p>
                                    <p>
                                        <b>Description:</b>
                                        <span class="text-muted ms-2">
                                        {!! $item->description ?? '-' !!}
                                        </span>
                                    </p>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="row mb-1">
                                <div class="row">
                                    <div class="col-12 mt-3">
                                        <div class="card bg-transparent">
                                            <div class="card-header p-2 p-md-4">
                                                <div class="row d-flex align-items-center">
                                                    <div class="col-8">
                                                        <div>
                                                            <h4 class="mb-0 hidden-md-down" ><b><i class='bx bx-objects-vertical-bottom'></i>
                                                                    Stock Movements</b></h4>
                                                            <p class="text-muted mb-0">All {{$item->name}} stock movements</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="text-end">
                                                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStockMovementModal"><i class='bx bx-plus-medical' ></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-borderless table-hover">
                                                        <thead class="border-bottom">
                                                            <tr>
                                                                <th scope="col">REASON</th>
                                                                <th scope="col">TYPE</th>
                                                                <th scope="col">QUANTITY</th>
                                                                <th scope="col">DATE</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($item->stock_movements()->orderBy('created_at', 'desc')->take(4)->get() as $stock_movement)
                                                                <tr class="table-entry align-middle border-bottom">
                                                                    <td style="width: 35%">
                                                                        <span class="text-capitalize" style="word-break:normal">{{ $stock_movement->reason }}</span>
                                                                    </td>
                                                                    <td class="text-start">
                                                                        <span class="badge bg-white text-dark">{{ strtoupper($stock_movement->type) }}</span>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <span>{{ $stock_movement->quantity }}</span>
                                                                    </td>
                                                                    <td>
                                                                        <span>{{ $stock_movement->created_at->format('Y-m-d') ?? '-' }}</span>
                                                                </tr>
                                                            @endforeach

                                                            @if (!count($item->assignments))
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
                                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#stockMovementsModal">View
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

            <div class="row mt-4 mb-4">
                <div class="col-md-12">
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="card bg-transparent">
                                <div class="card-header p-2 p-md-4">
                                    <div class="row d-flex align-items-center">
                                        <div class="col-7">
                                            <div>
                                                <h4 class="mb-0 hidden-md-down" ><b><i class='bx bx-clipboard'></i>
                                                        Assignments</b></h4>
                                                <p class="text-muted mb-0">Assigned {{ $item->name }}</p>
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <div class="text-end">
                                                <div style=" display:flex; justify-content:right; align-items:center;">
                                                <div class="d-flex idea justify-content-around align-items-center flex-1">
                                                        <div class="d-left">
                                                            {{ $item->remaining_stock}}</div>
                                                        <div
                                                            class="d-right">
                                                            {{ $item->stock }}</div>
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
                                                    <th scope="col">QUANTITY</th>
                                                    <th scope="col">ASSIGNMENT DAY</th>
                                                    <th scope="col">RETURN DAY</th>
                                                    <th scope="col"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($item->assignments->sortBy('return_date')->take(4) as $assignment)
                                                    <tr class="table-entry align-middle border-bottom">
                                                        <td colspan="2" class="width-2">
                                                            <a href="#">
                                                                <span class="text-capitalize">
                                                                    {{ $assignment?->assignment?->user?->name }}
                                                                </span>
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <span>{{ $assignment->quantity }}</span>
                                                        </td>
                                                        <td>
                                                            <span>{{ $assignment->assignment->extract_date ?? '-' }}</span>
                                                        </td>
                                                        <td>
                                                            <span class="{{ $assignment->assignment->return_date < now()->addDays(3) ? 'text-danger' : 'text-muted' }}">{{ $assignment->assignment->return_date }}</span>
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
                                                                            href="{{ route('dashboard.assignments.edit', $assignment->assignment->id) }}"><i
                                                                                class='bx bx-edit'></i> Edit</a>
                                                                    </li>
                                                                    <li>
                                                                        <hr class="dropdown-divider">
                                                                    </li>
                                                                    <li>
                                                                        <form
                                                                            action="{{ route('dashboard.assignments.line.delete', $assignment->id) }}"
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

                                                @if (!count($item->assignments))
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
        <div class="col-md-3">
            <div class="mt-3">
                <b class="fs-5"><i class='bx bx-image-alt' ></i></i> Images</b>
                <div class="col-md-12 gap-2 p-2 scrollbar" style="max-height: 50rem; height: 50rem;">
                    @foreach ($item->files as $file)
                        <img class="border mt-2" style="height: 250px" src="{{ asset('storage/' . $file->path) }}" alt="{{$file->title}}">
                    @endforeach

                    @if (!count($item->files))
                        <div class="d-flex justify-content-center align-items-center w-100 h-100">No image yet!</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="stockMovementsModal" tabindex="-1" aria-labelledby="stockMovementsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="stockMovementsModalLabel"><i class='bx bx-objects-vertical-bottom'></i> Stock Movements</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-borderless table-hover">
                            <thead class="border-bottom">
                                <tr>
                                    <th scope="col" colspan="2">USER</th>
                                    <th scope="col">REASON</th>
                                    <th scope="col">TYPE</th>
                                    <th scope="col">QUANTITY</th>
                                    <th scope="col">DATE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($item->stock_movements()->orderBy('created_at', 'desc')->get() as $stock_movement)
                                    <tr class="table-entry align-middle border-bottom">
                                        <td colspan="2">
                                            <a
                                                href="{{route('dashboard.users.show', $stock_movement->user->id)}}"><span
                                                    class="text-capitalize">{{ $stock_movement->user->name }}</span></a>
                                            </a>
                                        </td>
                                        <td style="width: 35%">
                                            <span class="text-capitalize" style="word-break:normal">{{ $stock_movement->reason }}</span>
                                        </td>
                                        <td class="text-start">
                                            <span class="badge bg-white text-dark">{{ strtoupper($stock_movement->type) }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span>{{ $stock_movement->quantity }}</span>
                                        </td>
                                        <td>
                                            <span>{{ $stock_movement->created_at->format('Y-m-d') ?? '-' }}</span>
                                    </tr>
                                @endforeach

                                @if (!count($item->assignments))
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <span class="text-muted">No assignments
                                                found!</span>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @include('partials.items.stockModal')
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.table-responsive').on('show.bs.dropdown', function() {
                $('.table-responsive').css("overflow", "inherit");
            });

            $('.table-responsive').on('hide.bs.dropdown', function() {
                $('.table-responsive').css("overflow", "auto");
            })
        });
    </script>
@endsection

