@extends('layouts.dashboard', ['section' => 'Users'])

@section('content')
    <div class="mt-2 row">
        <div class="col-md-8">
            <span class="h2 d-inline-block mt-1">
                <b>Users</b><span class="text-muted">({{ $total }})</span>
            </span>
            <a class="btn btn-primary d-inline-block ms-3 align-top {{ $portal->users->count() >= $portal->user_count ? 'disabled' : ''}}" href="{{route('dashboard.users.create')}}">
                <span class="px-4"><i class="bx bx-plus mt-1"></i>Add new {{__('crud.users.title_singular')}}</span>
            </a>
        </div>

        <div class="col-md-4 text-end">
            <span class="text-muted h2">{{$portal->users()->count()}}/{{$portal->user_count}}</span> <span class="text-muted">Employees</span>
        </div>
    </div>

    <div class="mt-4">
        <table class="table table-borderless table-responsive table-hover ajaxTable datatable datatable-Users mt-5 w-100">
            <thead class="border-top border-bottom">
                <tr>
                    <th scope="col" class="border-bottom"></th>
                    <th scope="col" class="border-bottom">PROFILE IMAGE</th>
                    <th scope="col" class="border-bottom">NAME</th>
                    <th scope="col" class="border-bottom">EMAIL</th>
                    <th scope="col" class="border-bottom">DEPARTMENT</th>
                    <th scope="col" class="border-bottom">ROLE</th>
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
                url: "{{ route('dashboard.users.massDestroy') }}",
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
                    url: "{{ route('dashboard.users.index') }}",
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
                        data: 'profile_img',
                        name: 'profile_img',
                        filter: true,
                        searchable: false,
                        width: 30
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
                        data: 'department',
                        name: 'departments.name',
                        filter: true,
                        type: 'select',
                        model: 'Department',
                        field: 'name',
                        searchable: false,
                        filterAjax: '{{ route('dashboard.searchListOptions.index') }}',
                        width: 30
                    },
                    {
                        data: 'role',
                        name: 'roles.name',
                        filter: true,
                        type: 'select',
                        model: 'Role',
                        field: 'name',
                        searchable: false,
                        filterAjax: '{{ route('dashboard.searchListOptions.index') }}',
                        width: 30
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
                    [3, "desc"]
                ],
                pageLength: 10,
                filterAjax: '{{ route('dashboard.searchListOptions.index') }}',
            };

            drawDataTable('.datatable-Users', dtOverrideGlobals, true);

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
