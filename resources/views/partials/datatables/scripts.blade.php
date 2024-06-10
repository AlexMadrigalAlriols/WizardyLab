<script>
    $(function() {
    let copyButtonTrans = '{{ trans('global.datatables.copy') }}'
    let csvButtonTrans = '{{ trans('global.datatables.csv') }}'
    let excelButtonTrans = '{{ trans('global.datatables.excel') }}'
    let pdfButtonTrans = '{{ trans('global.datatables.pdf') }}'
    let printButtonTrans = '{{ trans('global.datatables.print') }}'
    let colvisButtonTrans = '{{ trans('global.datatables.colvis') }}'
    let selectAllButtonTrans = '{{ trans('global.select_all') }}'
    let selectNoneButtonTrans = '{{ trans('global.deselect_all') }}'
    let resetFilterButtonTrans = '{{ trans('global.datatables.reset') }}'

    let languages = {
        'es': '{{ asset('js/datatables/plugins/Spanish.json') }}'
    };

    $.extend(true, $.fn.dataTable.Buttons.defaults.dom.button, {
        className: 'btn me-2 mb-3 mt-2'
    })
    $.extend(true, $.fn.dataTable.defaults, {
        language: {
            url: languages['{{ app()->getLocale() }}']
        },
        columnDefs: [{
            orderable: false,
            className: 'select-checkbox',
            targets: 0
        }, {
            orderable: false,
            searchable: false,
            targets: -1
        }],
        select: {
            style: 'multi+shift',
            selector: 'td:first-child'
        },
        order: [],
        scrollX: true,
        pageLength: 100,
        dom: 'lBfrtip<"actions">',
        buttons: [{
            extend: 'selectAll',
            className: 'btn-primary',
            text: selectAllButtonTrans,
            exportOptions: {
                columns: ':visible'
            },
            action: function(e, dt) {
                e.preventDefault()
                dt.rows().deselect();
                dt.rows({
                    search: 'applied'
                }).select();
            }
        },
            {
                extend: 'selectNone',
                className: 'btn-primary',
                text: selectNoneButtonTrans,
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'copy',
                className: 'btn-default',
                text: copyButtonTrans,
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'csv',
                className: 'btn-default',
                text: csvButtonTrans,
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'excel',
                className: 'btn-default',
                text: excelButtonTrans,
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdf',
                className: 'btn-default',
                text: pdfButtonTrans,
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'print',
                className: 'btn-default',
                text: printButtonTrans,
                exportOptions: {
                    columns: ':visible'
                }
            }
        ]
    });
});
</script>
