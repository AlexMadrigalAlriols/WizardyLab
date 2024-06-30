<?php

namespace App\Http\DataTables;

use App\Models\Invoice;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Status;
use App\Services\QueryBuilderService;

class LeavesDataTable extends DataTable
{
    public function __construct(protected ?string $name = 'leaves') {}

    public function dataTable($table): mixed
    {
        $table->addColumn('placeholder', '&nbsp;');
        $table->addColumn('actions', '&nbsp;');

        $table->editColumn('actions', function($row) {
            $crudRoutePart = 'leaves';
            $model = 'leave';
            $viewGate = false;
            $editGate = false;
            $deleteGate = 'leave_delete';
            $links = [];

            if(!$row->approved) {
                $links[] = [
                    'href' => route('dashboard.leaves.approve', $row->id),
                    'icon' => 'bx bx-check',
                    'text' => 'Approve'
                ];
            }

            return view('partials.datatables.actions', compact(
                'row',
                'crudRoutePart',
                'model',
                'viewGate',
                'editGate',
                'deleteGate',
                'links'
            ));
        });

        $table->editColumn('user', function($row) {
            return view('partials.users.details', ['user' => $row->user]);
        });

        $table->editColumn('date', function($row) {
            return $row->date ? $row->date->format('Y-m-d') : '-';
        });

        $table->editColumn('approved', function($row) {
            return $row->approved ? '<span class="badge bg-success">APPROVED</span>' : '<span class="badge bg-danger">NON-APPROVED</span>';
        });

        $table->editColumn('type', function($row) {
            $status = LeaveType::find($row->leave_type_id);

            return view('partials.datatables.status_badge', compact('status'));
        });

        $table->rawColumns(['placeholder', 'approved', 'actions']);

        return $table;
    }

    public function query()
    {
        $query = Leave::with(['user', 'leaveType']);

        $date_range = request()?->get('date_range');
        $user_name = request()?->get('user_name');
        $type = request()?->get('type');

        $query = (new QueryBuilderService())->advancedQuery(
            Leave::class,
            [
                'conditions' => request()?->get('conditions') ?? '',
                'fields' => request()?->get('fields') ?? '',
                'operators' => request()?->get('operators') ?? '',
                'values' => request()?->get('values') ?? '',
            ],
            ['user', 'leaveType']
        );

        if(!empty($date_range)) {
            $range = str_replace(' a ', ' - ', $date_range);
            $range = explode(' - ', $range);

            $query->whereDate('date', '>=', $range[0]);

            if(isset($range[1])) {
                $query->whereDate('date', '<=', $range[1]);
            }
        }

        if(!empty($user_name)) {
            $query->whereHas('user', function($q) use ($user_name) {
                $q->where('name', 'like', '%' . $user_name . '%');
            });
        }

        if(!empty($type)) {
            $query->whereHas('leaveType', function($q) use ($type) {
                $q->where('name', 'like', '%' . $type . '%');
            });
        }

        return $query->select('leaves.*');
    }
}
