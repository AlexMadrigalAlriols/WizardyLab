function drawDataTable(selector, options, withFilters = false) {
    if (withFilters) {
        $(`${selector} thead tr`)
            .clone(true)
            .appendTo(`${selector} thead`);

        $(`${selector} thead tr:eq(1) th`).each(function (i) {
            const columnOptions = options.columns[i];
            const title = $(`${selector} thead tr:eq(1) th`)
                .eq($(this).index())
                .text();

            if (columnOptions.filter) {
                switch (columnOptions.type) {
                    case 'options':
                        let selectOptions = ''
                        if (columnOptions.options) {
                            for (const { value, label } of columnOptions.options) {
                                selectOptions += `<option value="${value}" ${columnOptions.value && columnOptions.value == value ? 'selected' : ''}>${label}</option>`
                            }
                        }

                        $(this).html(`
                            <select class="form-control form-control-sm select2" style="width:100%" id="${columnOptions.name}" data-index="${i}" ${columnOptions.multiple ? 'multiple="multiple"' : ''}>
                                ${selectOptions}
                            </select>
                        `);

                        $(`#${columnOptions.name}`).select2()
                        break
                    case 'range':
                        if (columnOptions.datepicker) {
                            $(this).html(`
                                <div class="row d-flex justify-content-center">
                                    <div class="col-sm-12 col-md">
                                        <input class="form-control form-control-sm flatpicker" type="text" id="${columnOptions.name}_range" name="min" data-index="${i}" data-status="closed">
                                    </div>
                                </div>
                            `);

                            const flatpicker = $(`#${columnOptions.name}_range`).flatpickr({
                                mode: 'range',
                                locale: columnOptions.locale,
                                defaultDate: columnOptions.value ? columnOptions.value.split(' - ') : [],
                                onOpen: () => {
                                    $(`#${columnOptions.name}_range`).data('status', 'opened')
                                },
                                onClose: (selectedDates, dateStr, instance) => {
                                    $(`#${columnOptions.name}_range`).data('status', 'closed')
                                    instance.element.value = dateStr.replace(/to|a/gi, '-');
                                }
                            });

                            $('.flatpickr-calendar').append(`
                                <div class='row'>
                                    <div class='col'>
                                        <button type="button" class="btn btn-sm text-danger mb-2"><i class="fa fa-history"></i></a>
                                    </div>
                                </div>
                            `);

                            $('.flatpickr-calendar .btn').click(function () {
                                $(`#${columnOptions.name}_range`).data('status', 'closed')
                                flatpicker.clear();
                                flatpicker.value = '';
                                flatpicker.close();
                            });
                        } else {
                            let currencyInput = '';

                            if (columnOptions.currency) {
                                currencyInput = `<div class="col-sm-12 col-md">
                                    <div class="form-group">
                                        <label for="currency" class="required">${trans('cruds.product.fields.currency')}</label>
                                        <select class="form-control form-control-sm select2" id="${columnOptions.name}_currency" name="currency">
                                            <option></option>`;

                                if(columnOptions.value) {
                                    currencyInput += `<option value="${columnOptions.value}" selected>${columnOptions.value}</option>`;
                                }

                                currencyInput += `</select></div></div>`;
                            }

                            const contentModal = `
                                <form data-index="">
                                    <div class="row d-flex justify-content-center">
                                        ${currencyInput}
                                        <div class="col-sm-12 col-md">
                                            <label for="exampleInputEmail1">${trans('global.min')}</label>
                                            <input class="form-control form-control-sm" type="text" name="min" id="${columnOptions.name}_min" placeholder="Min" value="${columnOptions.valueMin}">
                                        </div>

                                        <div class="col-sm-12 col-md">
                                            <label for="exampleInputEmail1">${trans('global.min')}</label>
                                            <input class="form-control form-control-sm" type="text" name="max" id="${columnOptions.name}_max" placeholder="Max" value="${columnOptions.valueMax}">
                                        </div>
                                    </div>
                                </form>
                            `
                            appendModal(contentModal, title, columnOptions);
                            $(this).html(buttonModal(columnOptions.name));

                            if(columnOptions.valueMin || columnOptions.valueMax) {
                                $('#amountFilterModalBtn').addClass('btn-primary').removeClass('btn-secondary');
                            }

                            $(document).on(
                                'shown.bs.modal',
                                `#${columnOptions.name}FilterModal`,
                                () => {
                                    $(`#${columnOptions.name}FilterModal`).find('form').attr('data-index', i);

                                    $(`#${columnOptions.name}_currency`).select2({
                                        ajax: {
                                            delay: 1000,
                                            placeholder: trans('global.select_all'),
                                            url: options.currenciesAjax,
                                            dataType: 'json',
                                            data: params => {
                                                return { search: params.term }
                                            },
                                            processResults: results => {
                                                return {
                                                    results: parseSelect2Results(results)
                                                }
                                            }
                                        }
                                    });
                                }
                            );
                        }
                        break;
                    case 'select':
                    case 'origin':
                        let select2 = `
                            <select class="form-control form-control-sm select2" style="width: 100%;" id="${columnOptions.name}" data-index="${i}" ${columnOptions.multiple ? 'multiple="multiple"' : ''}>`;
                        if(columnOptions.value) {
                            select2 += `<option value="${columnOptions.value}" selected>${columnOptions.value}</option>`;
                        }
                        select2 +=`<option value="">${trans('global.select_all')}</option>
                            </select>`

                        if (columnOptions.type === 'select') {
                            $(this).html(select2)
                            $(`${selector} select[data-index='${i}']`).select2({
                                ajax: {
                                    placeholder: trans('global.select_all'),
                                    url: columnOptions.filterAjax,
                                    dataType: 'json',
                                    data: params => {
                                        return {
                                            search: params.term,
                                            model: columnOptions.model,
                                            field: columnOptions.field ?? columnOptions.name,
                                            method: columnOptions.method
                                        }
                                    },
                                    processResults: results => {
                                        const options = results.map(result => {
                                            return {
                                                id: result,
                                                text: result
                                            }
                                        })

                                        if (!columnOptions.notShowDefault) {
                                            options.unshift({
                                                id: '',
                                                text: trans('global.select_all')
                                            })
                                        }

                                        return { results: options }
                                    }
                                }
                            });
                        } else if (columnOptions.type === 'origin') {
                            const contentModal = `
                                <form data-index="">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            ${select2}
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control form-control-sm" id="filter_origin_id" placeholder="ID" data-index="${i}" value="${columnOptions.valueOriginId ?? ''}">
                                        </div>
                                    </div>
                                </form>
                            `
                            appendModal(contentModal, title, columnOptions);
                            $(this).html(buttonModal(columnOptions.name));

                            if(columnOptions.value || columnOptions.valueOriginId) {
                                $('#origin_marketplace_idFilterModalBtn').addClass('btn-primary').removeClass('btn-secondary');
                            }

                            $(`#${columnOptions.name}FilterModal`).on('shown.bs.modal', () => {
                                setTimeout(function () {
                                    $(`#origin_marketplace_id`).select2({
                                        ajax: {
                                            placeholder: trans('global.select_all'),
                                            url: columnOptions.filterAjax,
                                            dataType: 'json',
                                            data: params => {
                                                return {
                                                    search: params.term,
                                                    model: columnOptions.model,
                                                    field: columnOptions.field ?? columnOptions.name,
                                                    method: columnOptions.method
                                                }
                                            },
                                            processResults: results => {
                                                return {
                                                    results: results.map(result => {
                                                        return {
                                                            id: result,
                                                            text: result
                                                        }
                                                    })
                                                }
                                            }
                                        }
                                    })
                                }, 1000)
                            })
                        }
                        break;
                    case 'advanced_filters':
                        $(this).html(buttonModal('actions'));
                        break;
                    case 'text':
                    default:
                        $(this).html(`<input type="text" class="form-control form-control-sm" id="${columnOptions.name}" value="${columnOptions.value ?? ''}" placeholder="${title.trim()}" data-index="${i}">`);
                        break;
                }
            } else {
                $(this).html('');
            }
        });
    }

    function buttonModal(name) {
        return `
            <div class="row d-flex justify-content-center">
                <button id="${name}FilterModalBtn" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#${name}FilterModal">
                    <i class="fa fa-filter"></i>
                </button>
            </div>
        `
    }

    function appendModal(content, modalTitle = '', columnOptions = {}) {
        $('body').append(`
            <div class='modal fade tableFilterModal' id='${columnOptions.name}FilterModal'>
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">${modalTitle}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                        <div class="modal-body">${content}</div>
                        <div class="modal-footer">
                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <button type="button" class="btn btn-secondary btn-sm resetFilterModal" data-modal="#${columnOptions.name}FilterModal">${trans('global.reset')}</button>
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <button type="button" class="btn btn-primary btn-sm submitFilterModal" data-modal="#${columnOptions.name}FilterModal">${trans('global.filter')}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `);
    }

    const table = $(selector).DataTable({ ...options });

    // Filter event handler
    if (withFilters) {
        $(table.table().header()).on('keyup', 'input', function () {
            table.column($(this).data('index')).search(this.value).draw();
        });

        $(table.table().header()).on('change', 'select', function () {
            table.column($(this).data('index')).search(this.value).draw();
        });

        $(table.table().header()).on('change', '.flatpicker', function () {
            if ($(this).data('status') === 'closed') {
                table.column($(this).data('index')).search(this.value).draw()
            }
        });

        $(document)
            .on(
                'click',
                `.tableFilterModal .submitFilterModal`,
                function (e) {
                    e.preventDefault();

                    const $modal = $($(this).data('modal'))
                    const $form = $modal.find('form')
                    const data = $form.serialize()
                    const $btn = $(`#${$modal.attr('id')}Btn`)

                    $modal.modal('hide');

                    if (!$form.find('select[name="currency"]').length || !!$form.find('select[name="currency"]').val()) {
                        table.column($form.data('index')).search(data).draw();
                        $btn.removeClass('btn-secondary').addClass('btn-primary');
                    }
                }
            )
            .on(
                'click',
                `.tableFilterModal .resetFilterModal`,
                function (e) {
                    e.preventDefault();

                    const $modal = $($(this).data('modal'))
                    const $form = $modal.find('form')
                    const data = $form.serialize()
                    const $btn = $(`#${$modal.attr('id')}Btn`)

                    $modal.find('form')[0].reset();
                    $modal.find('.currency')?.val('').trigger('change');
                    $modal.find('#origin_marketplace_id')?.val('').trigger('change');
                    $modal.find('#filter_origin_id')?.val('');
                    $modal.modal('hide');
                    table.column($form.data('index')).search(data).draw();
                    $btn.removeClass('btn-primary').addClass('btn-secondary');
                }
            );
    }
}
