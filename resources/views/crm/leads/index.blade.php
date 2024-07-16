@extends('layouts.crm', ['section' => 'Leads'])

@section('content')
    <div class="mt-2">
        <span class="h2 d-inline-block mt-1">
            <b>{{__('crud.leads.title')}}</b><span class="text-muted">({{$total}})</span>
        </span>
        <a class="btn btn-primary d-inline-block ms-3 align-top" href="{{route('crm.leads.create')}}">
            <span class="px-4"><i class="bx bx-plus mt-1"></i>{{__('global.add_new')}} {{__('crud.leads.title_singular')}}</span>
        </a>
    </div>

    <div id="filterTable" class="d-inline-block" style="float: right;">
        <div class="mb-3">
            <div class="d-flex text-end">
                <div class="d-flex me-3">
                    <input type="search" id="datatable_search_input" class="form-control form-control-sm" placeholder="Search By Name" aria-controls="DataTables_Table_0">
                </div>
                <div class="d-flex me-3">
                    <input type="text" class="form-control form-control-sm flatpickr" id="created_at_range" placeholder="Select Date" name="created_at_range" />
                </div>
                <div class="d-flex">
                    <button id="advanced_filtersModalBtn" class="btn btn-secondary" data-toggle="modal" data-target="#advanced_filtersModal">
                        <i class="bx bx-filter-alt px-3" style="font-size: 22px"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <table class="table table-responsive table-hover ajaxTable datatable datatable-Leads mt-5 w-100 border-top">
            <thead class="border-top">
                <tr>
                    <th scope="col" class="py-2 text-center"><span class=""><input type="checkbox"></span></th>
                    <th scope="col" class="py-2">{{__('crud.leads.fields.name')}}</th>
                    <th scope="col" class="py-2"><span class="bg-danger"><i class='bx bx-envelope' ></i></span>  {{__('crud.leads.fields.email')}}</th>
                    <th scope="col" class="py-2">{{__('crud.leads.fields.mobile_number')}}</th>
                    <th scope="col">{{__('crud.leads.fields.created_at')}}</th>
                    <th scope="col"></th>
                </tr>
            </thead>
        </table>
    </div>

    @include('partials.advancedFiltersModal')
@endsection

@section('scripts')
    @parent
    <script src="{{ asset('js/datatables/drawDataTable.js') }}"></script>
    <script>
        $(function () {
            let dtButtons = [];

            let deleteButton = {
                text: '{{ trans('global.datatables.delete') }}',
                url: "{{ route('crm.leads.massDestroy') }}",
                className: 'btn-danger',
                action: function(e, dt, node, config) {
                    var ids = $.map(dt.rows({
                        selected: true
                    }).data(), function(entry) {
                        return entry.id
                    });

                    if (ids.length === 0) {
                        alert('{{ trans('global.datatables.zero_selected') }}')

                        return
                    }

                    if (confirm('{{ trans('global.areYouSure') }}')) {
                        $.ajax({
                                headers: {
                                    'x-csrf-token': '{{ csrf_token() }}'
                                },
                                method: 'POST',
                                url: config.url,
                                data: {
                                    ids: ids,
                                    _method: 'DELETE'
                                }
                            })
                            .done(function() {
                                location.reload()
                            })
                    }
                }
            }
            dtButtons.push(deleteButton);

            let dtOverrideGlobals = {
                searchDelay: 1000,
                buttons: dtButtons,
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                language: {
                    paginate: {
                        next: '<i class="bx bx-chevron-right"></i>',
                        previous: '<i class="bx bx-chevron-left"></i>',
                    }
                },
                ajax: {
                    url: "{{ route('crm.leads.index') }}",
                    data: function(data) {
                        data.created_at_range = $('#created_at_range').val();

                        data.conditions = getInputValue("condition");
                        data.fields = getInputValue("field");
                        data.values = getInputValue("value");
                        data.operators = getInputValue("operator");
                    }
                },
                columns: [
                    {
                        data: 'placeholder',
                        name: 'placeholder',
                        width: 5
                    },
                    {
                        data: 'name',
                        name: 'name',
                        filter: true,
                        width: 30
                    },
                    {
                        data: 'email',
                        name: 'email',
                        filter: true,
                        width: 30
                    },
                    {
                        data: 'phone',
                        name: 'phone',
                        filter: true,
                        width: 30
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        filter: true,
                        datepicker: true,
                        type: 'range',
                        width: 30,
                        searchable: false
                    },
                    {
                        data: 'actions',
                        name: '{{ trans('global.actions') }}',
                        width: 50,
                        filter: true,
                        searchable: false,
                        type: 'advanced_filters',
                        field: 'actions'
                    }
                ],
                orderCellsTop: true,
                order: [
                    [5, "desc"]
                ],
                pageLength: 10,
                filterAjax: '{{ route('dashboard.searchListOptions.index') }}',
            };

            drawDataTable('.datatable-Leads', dtOverrideGlobals, false);

            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

            $('#DataTables_Table_0_length').addClass('d-none');
            $('#DataTables_Table_0_filter').addClass('d-none');
            $('#filterTable').appendTo('.dt-buttons');
        });
    </script>
@endsection
