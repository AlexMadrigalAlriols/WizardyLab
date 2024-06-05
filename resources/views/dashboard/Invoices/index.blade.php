@extends('layouts.dashboard', ['section' => 'Invoices'])

@section('content')
    <div class="mt-2">
        <span class="h2 d-inline-block mt-1">
            <b>{{__('crud.invoices.title')}}</b><span class="text-muted">({{count($invoices)}})</span>
        </span>
        <a class="btn btn-primary d-inline-block ms-3 align-top" href="{{route('dashboard.invoices.create')}}">
            <span class="px-4"><i class="bx bx-plus mt-1"></i>{{__('global.create')}} {{ __('crud.invoices.title_singular')}}</span>
        </a>
    </div>

    <div class="table-actions">
        <div class="row">
            <div class="col-md-5">

            </div>
            <div class="col-md-7 mt-1">
                <div class="justify-content-end">
                    <div class="row">
                        <div class="col-md-6 offset-md-6 col-sm-12 mt-3">
                            <div class="search-container w-100">
                                <i class="bx bx-search search-icon"></i>
                                <input type="text" class="search-input" id="search-input" name="search_input"
                                    placeholder="Search Invoices">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive mt-5">
        <table class="table table-borderless table-hover">
            <thead class="border-top border-bottom">
                <tr>
                    <th scope="col" class="min-width-0"><input type="checkbox" id="select_all"></th>
                    <th scope="col">{{strtoupper(__('crud.invoices.fields.number'))}}</th>
                    <th scope="col">{{strtoupper(__('crud.invoices.fields.project'))}} / {{strtoupper(__('crud.invoices.fields.client'))}}</th>
                    <th scope="col">{{strtoupper(__('crud.invoices.fields.status'))}}</th>
                    <th scope="col">{{strtoupper(__('crud.invoices.fields.issue_date'))}}</th>
                    <th scope="col">{{strtoupper(__('crud.invoices.fields.total'))}}</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoices as $invoice)
                    <tr class="table-entry align-middle border-bottom">
                        <td class="text-nowrap">
                            <input type="checkbox" name="checkbox[]">
                        </td>
                        <td>{{ $invoice->number }}</td>
                        <td>{{ $invoice->project?->name ?? ($invoice->client?->name ?? '-') }}</td>
                        <td><span class="badge">{{ $invoice->status->title }}</span></td>
                        <td>{{ $invoice->issue_date }}</td>
                        <td>{{ $invoice->total }} {{$invoice?->client->currency->symbol ?? '-'}}</td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-options" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class='bx bx-dots-horizontal-rounded'></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li><a class="dropdown-item" href="{{route('dashboard.invoices.download', $invoice->id)}}"><i class='bx bx-download' ></i> Download</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{route('dashboard.invoices.destroy', $invoice->id)}}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="dropdown-item text-danger"><i class='bx bx-trash' ></i> Remove</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach

                @if(!count($invoices))
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <span class="text-muted">No invoices found!</span>
                        </td>
                    </tr>
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="9" class="py-4 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                {{ count($invoices) }} to {{$pagination['take'] ?? count($invoices)}} items of <b>{{ $total }}</b>
                                @if(request('page') != 'all')
                                    <span class="ms-4">
                                        <a href="?page=all" class="text-decoration-none">View All <i class='bx bx-chevron-right'></i></a>
                                    </span>
                                @endif
                            </div>
                            <div>
                                @if(request('page') != 'all')
                                    <ul class="pagination m-0">
                                        <li class="page-item {{ $pagination['pages'] > 1 && request('page', 1) > 1 ? '' : 'disabled'}}">
                                            <a class="page-link arrow" href="?page={{(request('page', 1) - 1) . (request('status') ? '&status=' . request('status') : '')}}"><i class='bx bx-chevron-left' ></i></a>
                                        </li>
                                        @for ($page = 1; $page <= $pagination['pages']; $page++)
                                            <li class="page-item" aria-current="page">
                                                <a class="page-link {{request('page', 1) == $page ? 'active' : ''}}" href="?page={{$page . (request('status') ? '&status=' . request('status') : '')}}"><b>{{$page}}</b></a>
                                            </li>
                                        @endfor
                                        <li class="page-item {{ $pagination['pages'] > 1 && request('page', 1) != $pagination['pages'] ? '' : 'disabled'}}">
                                            <a class="page-link arrow" href="?page={{(request('page', 1) + 1) . (request('status') ? '&status=' . request('status') : '')}}"><i class='bx bx-chevron-right'></i></a>
                                        </li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
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
