@extends('layouts.dashboard', ['section' => 'Projects'])

@section('content')
    <div class="mt-2">
        <span class="h2 d-inline-block mt-1">
            <b>{{__('crud.projects.title')}}</b><span class="text-muted">({{ $total }})</span>
        </span>
        <a class="btn btn-primary d-inline-block ms-3 align-top" href="{{route('dashboard.projects.create')}}">
            <span class="px-4"><i class="bx bx-plus mt-1"></i>{{__('crud.projects.add_new')}}</span>
        </a>
    </div>

    <div class="mt-4">
        <table class="table table-borderless table-responsive table-hover ajaxTable datatable datatable-Projects mt-5 w-100">
            <thead class="border-top border-bottom">
                <tr>
                    <th scope="col" class="border-bottom"></th>
                    <th scope="col" class="border-bottom">{{__('crud.projects.fields.code')}}</th>
                    <th scope="col" class="border-bottom">{{__('crud.projects.fields.title')}}</th>
                    <th scope="col" class="border-bottom">{{__('crud.projects.fields.start_date')}}</th>
                    <th scope="col" class="border-bottom">{{__('crud.projects.fields.due_date')}}</th>
                    <th scope="col" class="border-bottom">{{__('crud.projects.fields.status')}}</th>
                    <th scope="col" class="border-bottom">{{__('crud.projects.fields.hours_logged')}}</th>
                    <th scope="col" class="border-bottom"></th>
                </tr>
            </thead>
        </table>
    </div>
@endsection

@section('scripts')
    @parent
    <script src="{{ asset('js/datatables/drawDataTable.js') }}"></script>
    <script>
        $(function () {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

            let deleteButton = {
                text: '{{ trans('global.datatables.delete') }}',
                url: "{{ route('dashboard.projects.massDestroy') }}",
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
                dropdownParent: $('body'),
                aaSorting: [],
                language: {
                    paginate: {
                        next: '<i class="bx bx-chevron-right"></i>',
                        previous: '<i class="bx bx-chevron-left"></i>',
                    }
                },
                ajax: {
                    url: "{{ route('dashboard.projects.index') }}",
                    data: function(data) {
                        data.start_date_range = $('#start_date_range').val();
                        data.deadline_range = $('#deadline_range').val();

                        data.status = $('#status').val();
                    }
                },
                columns: [
                    {
                        data: 'placeholder',
                        name: 'placeholder',
                        width: 5
                    },
                    {
                        data: 'code',
                        name: 'code',
                        filter: true,
                        width: 30
                    },
                    {
                        data: 'name',
                        name: 'name',
                        filter: true,
                        width: 30
                    },
                    {
                        data: 'start_date',
                        name: 'start_date',
                        filter: true,
                        datepicker: true,
                        type: 'range',
                        width: 30,
                        searchable: false
                    },
                    {
                        data: 'deadline',
                        name: 'deadline',
                        filter: true,
                        datepicker: true,
                        type: 'range',
                        width: 30,
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'status_id',
                        type: 'options',
                        options: {!! $statuses !!},
                        filter: true,
                        width: 30
                    },
                    {
                        data: 'hours_logged',
                        name: 'hours_logged',
                        filter: false,
                        searchable: false,
                        width: 30
                    },
                    {
                        data: 'actions',
                        name: '{{ trans('global.actions') }}',
                        width: 20
                    }
                ],
                orderCellsTop: true,
                order: [
                    [4, "desc"]
                ],
                pageLength: 10,
                filterAjax: '{{ route('dashboard.searchListOptions.index') }}',
            };

            drawDataTable('.datatable-Projects', dtOverrideGlobals, true);

            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

            $('#DataTables_Table_0_filter').addClass('d-none');
            $('#DataTables_Table_0_length').appendTo('.dt-buttons');
            $('#DataTables_Table_0_length').addClass('d-inline-block');

            $('#totalFilterModalBtn').on('click', function() {
                $('#totalFilterModal').modal('show');
            });
        });
    </script>
@endsection
