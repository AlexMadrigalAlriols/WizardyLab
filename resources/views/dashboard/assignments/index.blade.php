@extends('layouts.dashboard', ['section' => 'Assignments'])

@section('content')
    <div class="mt-2">
        <span class="h2 d-inline-block mt-1">
            <b>Assignments</b><span class="text-muted">({{ $total }})</span>
        </span>
        <a class="btn btn-primary d-inline-block ms-3 align-top" href="{{ route('dashboard.assignments.create') }}">
            <span class="px-4"><i class="bx bx-plus mt-1"></i>Add new Assignment</span>
        </a>
    </div>

    <div class="mt-4">
        <table class="table table-borderless table-responsive table-hover ajaxTable datatable datatable-Assignments mt-5 w-100">
            <thead class="border-top border-bottom">
                <tr>
                    <th scope="col" class="border-bottom"></th>
                    <th scope="col" class="border-bottom">USER</th>
                    <th scope="col" class="border-bottom">ITEMS</th>
                    <th scope="col" class="border-bottom">EXTRACT DATE</th>
                    <th scope="col" class="border-bottom">RETURN DATE</th>
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
                url: "{{ route('dashboard.assignments.massDestroy') }}",
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
                    url: "{{ route('dashboard.assignments.index') }}",
                    data: function(data) {
                        data.return_date_range = $('#return_date_range').val();
                        data.extract_date_range = $('#extract_date_range').val();
                        data.user_name = $('#users\\.name').val();

                        data.items = $('#items').val();

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
                        data: 'user',
                        name: 'users.name',
                        filter: true,
                        type: 'select',
                        model: 'User',
                        field: 'name',
                        searchable: false,
                        filterAjax: '{{ route('dashboard.searchListOptions.index') }}',
                        width: 30
                    },
                    {
                        data: 'items',
                        name: 'items',
                        filter: true,
                        width: 30,
                        searchable: false
                    },
                    {
                        data: 'extract_date',
                        name: 'extract_date',
                        filter: true,
                        datepicker: true,
                        type: 'range',
                        width: 30,
                        searchable: false
                    },
                    {
                        data: 'return_date',
                        name: 'return_date',
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

            drawDataTable('.datatable-Assignments', dtOverrideGlobals, true);

            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

            $('#DataTables_Table_0_filter').addClass('d-none');
            $('#DataTables_Table_0_length').appendTo('.dt-buttons');
            $('#DataTables_Table_0_length').addClass('d-inline-block');
        });
    </script>
@endsection
