<?php

namespace App\Http\DataTables;

use App\Models\Company;
use App\Services\QueryBuilderService;

class CompaniesDataTable extends DataTable
{

    public function __construct(protected ?string $name = 'companies') {}

    public function dataTable($table): mixed
    {
        $table->addColumn('placeholder', '&nbsp;');
        $table->addColumn('actions', '&nbsp;');

        $table->editColumn('actions', function($row) {
            $crudRoutePart = 'companies';
            $model = 'company';
            $viewGate = false;
            $editGate = 'company_edit';
            $deleteGate = 'company_delete';

            return view('partials.datatables.actions', compact(
                'row',
                'crudRoutePart',
                'model',
                'viewGate',
                'editGate',
                'deleteGate'
            ));
        });

        $table->editColumn('name', function($row) {
            return $row->name ?: '';
        });

        $table->editColumn('active', function($row) {
            return $row->active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
        });

        $table->editColumn('created_at', function($row) {
            return $row->created_at ?: '';
        });

        $table->rawColumns(['placeholder', 'active', 'actions']);

        return $table;
    }

    public function query()
    {
        $query = Company::query();

        $created_at_range = request()?->get('created_at_range');

        $query = (new QueryBuilderService())->advancedQuery(
            Company::class,
            [
                'conditions' => request()?->get('conditions') ?? '',
                'fields' => request()?->get('fields') ?? '',
                'operators' => request()?->get('operators') ?? '',
                'values' => request()?->get('values') ?? '',
            ],
            ['clients']
        );

        if(!empty($created_at_range)) {
            $range = str_replace(' a ', ' - ', $created_at_range);
            $range = explode(' - ', $range);

            $query->whereDate('created_at', '>=', $range[0]);

            if(isset($range[1])) {
                $query->whereDate('created_at', '<=', $range[1]);
            }
        }

        return $query;
    }
}
