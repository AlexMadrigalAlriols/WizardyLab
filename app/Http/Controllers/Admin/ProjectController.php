<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PaginationHelper;
use App\Helpers\TaskAttendanceHelper;
use App\Http\Controllers\Controller;
use App\Http\DataTables\ProjectsDataTable;
use App\Http\Requests\MassDestroyRequest;
use App\Http\Requests\Projects\StoreRequest;
use App\Http\Requests\Projects\UpdateRequest;
use App\Models\Client;
use App\Models\Department;
use App\Models\Project;
use App\Models\ProjectStatuses;
use App\Models\Status;
use App\Models\Task;
use App\Models\User;
use App\UseCases\Projects\StoreUseCase;
use App\UseCases\Projects\UpdateStatusUseCase;
use App\UseCases\Projects\UpdateUseCase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()) {
            $dataTable = new ProjectsDataTable('projects');
            return $dataTable->make();
        }

        $total = Auth::user()->projects()->count();
        $statuses = Status::where('morphable', Project::class)->get();
        $json_statuses = [['value' => '', 'label' => '-']];

        foreach ($statuses as $status) {
            $json_statuses[] = ['value' => $status->id, 'label' => $status->title];
        }

        $statuses = json_encode($json_statuses, JSON_UNESCAPED_UNICODE);
        return view('dashboard.projects.index', compact('total', 'statuses'));
    }

    public function show(Task $task)
    {
        $hoursChart = TaskAttendanceHelper::getTaskHoursChart($task);
        $activityChart = TaskAttendanceHelper::getTaskActivityChart($task);
        $task_comments = $task->comments()->orderBy('created_at', 'desc')->get();

        return view('dashboard.tasks.show', compact('task', 'hoursChart', 'activityChart', 'task_comments'));
    }

    public function create()
    {
        $project = new Project();
        [$users, $project_statuses, $departments, $clients] = $this->getData();

        return view('dashboard.projects.create', compact(
            'project',
            'users',
            'project_statuses',
            'departments',
            'clients'
        ));
    }

    public function store(StoreRequest $request) {
        $project = (new StoreUseCase(
            Auth::user(),
            Client::find($request->input('client_id')),
            $request->input('name'),
            $request->input('code'),
            Carbon::parse($request->input('start_date')),
            Carbon::parse($request->input('due_date')),
            $request->input('limit_hours'),
            Status::find($request->input('status')),
            $request->input('description', ''),
            $request->input('assigned_users', []),
            $request->input('departments', [])
        ))->action();

        toast('Project created', 'success');
        return redirect()->route('dashboard.projects.index');
    }

    public function edit(Project $project)
    {
        [$users, $project_statuses, $departments, $clients] = $this->getData();

        return view('dashboard.projects.edit', compact(
            'project',
            'users',
            'project_statuses',
            'departments',
            'clients'
        ));
    }

    public function update(UpdateRequest $request, Project $project)
    {
        (new UpdateUseCase(
            $project,
            Auth::user(),
            Client::find($request->input('client_id')),
            $request->input('name'),
            Carbon::parse($request->input('start_date')),
            Carbon::parse($request->input('due_date')),
            $request->input('limit_hours'),
            Status::find($request->input('status')),
            $request->input('description', ''),
            $request->input('assigned_users', []),
            $request->input('departments', [])
        ))->action();

        toast('Project updated', 'success');
        return redirect()->route('dashboard.projects.index');
    }

    public function destroy(Project $project)
    {
        $project->delete();

        toast('Project deleted', 'success');
        return redirect()->route('dashboard.projects.index');
    }

    public function massDestroy(MassDestroyRequest $request)
    {
        $ids = $request->input('ids');
        Project::whereIn('id', $ids)->delete();

        toast('Projects deleted', 'success');
        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function updateStatus(Project $project, Status $status)
    {
        (new UpdateStatusUseCase($project, $status))->action();

        toast('Project status updated', 'success');
        return redirect()->route('dashboard.projects.index');
    }

    public function updateStatusOrder(Request $request, ProjectStatuses $projectStatus)
    {
        if($request->has('order') && is_numeric($request->input('order'))) {
            $projectStatus->update([
                'order' => $request->input('order')
            ]);
            toast('Statuses order updated', 'success');
        }

        if($request->has('collapsed')) {
            $collapsed = $request->input('collapsed');
            $collapsed = $collapsed === 'true' ? true : false;

            $projectStatus->update([
                'collapsed' => $collapsed
            ]);
        }

        return back();
    }

    private function getData(): array
    {
        $users = User::all();
        $project_statuses = Status::where('morphable', Project::class)->get();
        $departments = Department::all();
        $clients = Client::all()->prepend(new Client());

        return [$users, $project_statuses, $departments, $clients];
    }
}
