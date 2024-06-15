<?php

namespace App\Http\DataTables;

use App\Models\Client;
use App\Models\Status;

class StatusesDataTable extends DataTable
{
    public function __construct(protected ?string $name = 'statuses') {}

    public function dataTable($table): mixed
    {
        $table->addColumn('placeholder', '&nbsp;');
        $table->addColumn('actions', '&nbsp;');

        $table->editColumn('actions', function($row) {
            $crudRoutePart = 'statuses';
            $model = 'status';
            $viewGate = false;
            $editGate = 'status_edit';
            $deleteGate = 'status_delete';

            return view('partials.datatables.actions', compact(
                'row',
                'crudRoutePart',
                'model',
                'viewGate',
                'editGate',
                'deleteGate'
            ));
        });

        $table->editColumn('title', function($row) {
            return view('partials.datatables.status_badge', ['status' => $row]);
        });

        $table->editColumn('morphable', function($row) {
            return explode("\\", $row->morphable)[2] ?: '';
        });

        $table->editColumn('created_at', function($row) {
            return $row->created_at ?: '';
        });

        $table->rawColumns(['placeholder', 'title', 'actions']);

        return $table;
    }

    public function query()
    {
        $query = Status::query();

        $created_at_range = request()?->get('created_at_range');

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
