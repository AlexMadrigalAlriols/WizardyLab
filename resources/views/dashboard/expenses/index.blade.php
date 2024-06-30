
@extends('layouts.dashboard', ['section' => 'Expenses'])

@section('content')
    <div class="mt-2">
        <span class="h2 d-inline-block mt-1">
            <b>Expenses </b><span class="text-muted">({{ $total }})</span>
        </span>
        <a class="btn btn-primary d-inline-block ms-3 align-top" href="{{route('dashboard.expenses.create')}}">
            <span class="px-4"><i class="bx bx-plus mt-1"></i>Add new Expense</span>
        </a>
    </div>

    <div class="mt-4">
        <table class="table table-borderless table-responsive table-hover ajaxTable datatable datatable-Expenses mt-5 w-100">
            <thead class="border-top border-bottom">
                <tr>
                    <th scope="col" class="border-bottom"></th>
                    <th scope="col" class="border-bottom">PROJECT</th>
                    <th scope="col" class="border-bottom">ITEM</th>
                    <th scope="col" class="border-bottom">QUANTITY</th>
                    <th scope="col" class="border-bottom">AMOUNT</th>
                    <th scope="col" class="border-bottom">FACTURABLE</th>
                    <th scope="col" class="border-bottom">CREATED AT</th>
                    <th scope="col" class="border-bottom"></th>
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
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

            let deleteButton = {
                text: '{{ trans('global.datatables.delete') }}',
                url: "{{ route('dashboard.expenses.massDestroy') }}",
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
                    url: "{{ route('dashboard.expenses.index') }}",
                    data: function(data) {
                        data.created_at_range = $('#created_at_range').val();

                        data.amount_min = $('#amount_min').val();
                        data.amount_max = $('#amount_max').val();
                        data.quantity_min = $('#quantity_min').val();
                        data.quantity_max = $('#quantity_max').val();
                        data.name = $('#name').val();

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
                        data: 'project',
                        name: 'projects.name',
                        filter: true,
                        type: 'select',
                        model: 'Project',
                        field: 'name',
                        searchable: false,
                        filterAjax: '{{ route('dashboard.searchListOptions.index') }}',
                        width: 30
                    },
                    {
                        data: 'name',
                        name: 'name',
                        filter: true,
                        width: 20
                    },
                    {
                        data: 'quantity',
                        name: 'quantity',
                        filter: false,
                        width: 30
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                        filter: true,
                        width: 30,
                        type: 'range',
                        currency: false,
                        searchable: false
                    },
                    {
                        data: 'facturable',
                        name: 'facturable',
                        type: 'options',
                        options: [
                                {value: '', label: '-'},
                                {value: 1, label: 'Facturable'},
                                {value: 0, label: 'Non-Facturable'},
                        ],
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

            drawDataTable('.datatable-Expenses', dtOverrideGlobals, true);

            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

            $('#DataTables_Table_0_filter').addClass('d-none');
            $('#DataTables_Table_0_length').appendTo('.dt-buttons');
            $('#DataTables_Table_0_length').addClass('d-inline-block');

            $('#quantityFilterModalBtn').on('click', function() {
                $('#quantityFilterModalBtn').modal('show');
            });

            $('#amountFilterModalBtn').on('click', function() {
                $('#amountFilterModal').modal('show');
            });
        });
    </script>
@endsection

