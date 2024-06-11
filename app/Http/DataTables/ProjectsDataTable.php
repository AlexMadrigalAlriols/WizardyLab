<?php

namespace App\Http\DataTables;

use App\Models\Company;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;

class ProjectsDataTable extends DataTable
{
    public function __construct(protected ?string $name = 'projects') {}

    public function dataTable($table): mixed
    {
        $table->addColumn('placeholder', '&nbsp;');
        $table->addColumn('actions', '&nbsp;');

        $table->editColumn('actions', function($row) {
            $crudRoutePart = 'projects';
            $model = 'project';
            $viewGate = false;
            $editGate = 'project_edit';
            $deleteGate = 'project_delete';
            $links = [
                [
                    'href' => route('dashboard.projects.generate-invoice', $row->id),
                    'icon' => 'bx bx-file',
                    'text' => 'Generate Invoice'
                ]
            ];

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

        $table->editColumn('code', function($row) {
            return $row->code ?: '';
        });

        $table->editColumn('name', function($row) {
            $link = '<a href="'.route('dashboard.projects.board', $row->id).'">'.$row->name.'</a>';

            return $row->name ? $link : '-';
        });

        $table->editColumn('start_date', function($row) {
            return $row->start_date ? $row->start_date->format('Y-m-d') : '';
        });

        $table->editColumn('deadline', function($row) {
            return $row->deadline ? $row->deadline->format('Y-m-d') : '';
        });

        $table->editColumn('status', function($row) {
            $selected_status = Status::find($row->status_id);
            $statuses = Status::where('morphable', Project::class)->get();
            $model = $row;
            $route = 'dashboard.projects.update-status';

            return view('partials.datatables.status_dropdown', compact(
                'selected_status',
                'statuses',
                'model',
                'route'
            ));
        });

        $table->editColumn('hours_logged', function($row) {
            return '<span class='.($row->total_hours > $row->limit_hours ? "text-danger" : "text-muted").'>' . ($row->total_hours ?? 0) . 'h / ' . ($row->limit_hours ?? '-') . 'h</span>';;
        });

        $table->rawColumns(['placeholder', 'hours_logged', 'name', 'status', 'actions']);

        return $table;
    }

    public function query()
    {
        $query = Project::whereIn('id', Auth::user()->projects->pluck('id'));

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

        return $query->select('projects.*');
    }
}
