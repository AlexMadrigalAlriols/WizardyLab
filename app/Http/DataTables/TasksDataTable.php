<?php

namespace App\Http\DataTables;

use App\Models\Company;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\Status;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TasksDataTable extends DataTable
{
    public function __construct(protected ?string $name = 'tasks') {}

    public function dataTable($table): mixed
    {
        $table->addColumn('placeholder', '&nbsp;');
        $table->addColumn('actions', '&nbsp;');

        $table->editColumn('actions', function($row) {
            $crudRoutePart = 'tasks';
            $model = 'task';
            $viewGate = 'task_show';
            $editGate = 'task_edit';
            $deleteGate = 'task_delete';

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

        $table->editColumn('timer', function($row) {
            return '-';
        });

        $table->editColumn('code', function($row) {
            return $row->code ?: '';
        });

        $table->editColumn('title', function($row) {
            $link = '<a href="'.route('dashboard.tasks.show', $row->id).'">'.$row->title.'</a>';

            return $row->title ? $link : '-';
        });

        $table->editColumn('assigness', function($row) {
            return '-';
        });

        $table->editColumn('duedate', function($row) {
            return $row->deadline ? $row->deadline->format('Y-m-d') : '';
        });

        $table->editColumn('status', function($row) {
            $selected_status = Status::find($row->status_id);
            $statuses = Status::where('morphable', Task::class)->get();
            $model = $row;
            $route = 'dashboard.tasks.update-status';

            return view('partials.datatables.status_dropdown', compact(
                'selected_status',
                'statuses',
                'model',
                'route'
            ));
        });

        $table->editColumn('priority', function($row) {
            return $row->priority ? $row->priority->name : '';
        });

        $table->editColumn('hours_logged', function($row) {
            return '<span class='.($row->total_hours > $row->limit_hours ? "text-danger" : "text-muted").'>' . ($row->total_hours ?? 0) . 'h / ' . ($row->limit_hours ?? '-') . 'h</span>';;
        });

        $table->rawColumns(['placeholder', 'hours_logged', 'title', 'assigness', 'status', 'actions']);

        return $table;
    }

    public function query()
    {
        $query = Task::whereIn('id', Auth::user()->tasks->pluck('id'));

        $start_date_range = request()?->get('start_date_range');
        $deadline_range = request()?->get('deadline_range');

        if(!empty($start_date_range)) {
            $range = str_replace(' a ', ' - ', $start_date_range);
            $range = explode(' - ', $range);

            $query->whereDate('start_date', '>=', $range[0]);

            if(isset($range[1])) {
                $query->whereDate('start_date', '<=', $range[1]);
            }
        }

        if(!empty($deadline_range)) {
            $range = str_replace(' a ', ' - ', $deadline_range);
            $range = explode(' - ', $range);

            $query->whereDate('deadline', '>=', $range[0]);

            if(isset($range[1])) {
                $query->whereDate('deadline', '<=', $range[1]);
            }
        }

        return $query->select('tasks.*');
    }
}
