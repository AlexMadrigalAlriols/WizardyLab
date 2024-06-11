<?php

namespace App\Http\DataTables;

use App\Models\Client;
use App\Models\LeaveType;
use App\Models\Status;

class LeaveTypesDataTable extends DataTable
{
    public function __construct(protected ?string $name = 'leaveTypes') {}

    public function dataTable($table): mixed
    {
        $table->addColumn('placeholder', '&nbsp;');
        $table->addColumn('actions', '&nbsp;');

        $table->editColumn('actions', function($row) {
            $crudRoutePart = 'leaveTypes';
            $model = 'leaveType';
            $viewGate = false;
            $editGate = 'leaveType_edit';
            $deleteGate = 'leaveType_delete';

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
            return view('partials.datatables.status_badge', ['status' => $row]);
        });

        $table->editColumn('max_days', function($row) {
            return $row->max_days ?: '';
        });

        $table->editColumn('created_at', function($row) {
            return $row->created_at ?: '';
        });

        $table->rawColumns(['placeholder', 'name', 'actions']);

        return $table;
    }

    public function query()
    {
        $query = LeaveType::query();

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
