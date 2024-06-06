<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApiResponse;
use App\Helpers\AttendanceHelper;
use App\Helpers\BoardHelper;
use App\Helpers\LogHelper;
use App\Helpers\PaginationHelper;
use App\Helpers\TaskAttendanceHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tasks\StoreRequest;
use App\Http\Requests\Tasks\UpdateRequest;
use App\Models\Department;
use App\Models\Label;
use App\Models\Project;
use App\Models\Status;
use App\Models\Task;
use App\Models\TaskFile;
use App\Models\User;
use App\UseCases\TaskFiles\StoreUseCase as TaskFilesStoreUseCase;
use App\UseCases\Tasks\StoreUseCase;
use App\UseCases\Tasks\UpdateStatusUseCase;
use App\UseCases\Tasks\UpdateUseCase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->tasks()->whereNull('tasks.task_id')->orderBy('duedate', 'desc');

        if($request->has('status') && is_numeric($request->input('status'))) {
            $query->where('status_id', $request->input('status'));
        }

        if($request->has('archived') && is_numeric($request->input('archived'))) {
            if($request->input('archived') == 1) {
                $query->whereNotNull('archive_at');
            } else {
                $query->whereNull('archive_at');
            }
        } else {
            $query->whereNull('archive_at');
        }

        [$query, $pagination] = PaginationHelper::getQueryPaginated($query, $request, Task::class);

        $tasks = $query->get();
        $counters = $this->getTaskCounters();
        $statuses = Status::where('morphable', Task::class)->get();

        return view('dashboard.tasks.index', compact('tasks', 'statuses', 'counters', 'pagination'));
    }

    public function show(Request $request, Task $task)
    {
        $task_comments = $task->comments()->orderBy('created_at', 'desc')->get();

        if($request->ajax()) {
            $actions = $this->getBoardActions($task);
            return view('partials.projects.board.show_modal', compact('task', 'actions', 'task_comments'));
        }

        $hoursChart = TaskAttendanceHelper::getTaskHoursChart($task);
        $activityChart = TaskAttendanceHelper::getTaskActivityChart($task);

        return view('dashboard.tasks.show', compact('task', 'hoursChart', 'activityChart', 'task_comments'));
    }

    public function create(Request $request)
    {
        $task = new Task();
        [$users, $tasks, $departments, $projects, $task_statuses, $priorities, $tags] = $this->getData();

        if($request->ajax()) {
            return view('partials.tasks.form', compact(
                'task',
                'users',
                'tasks',
                'departments',
                'projects',
                'task_statuses',
                'priorities',
                'tags'
            ));
        }

        return view('dashboard.tasks.create', compact(
            'task',
            'users',
            'tasks',
            'departments',
            'projects',
            'task_statuses',
            'priorities',
            'tags'
        ));
    }

    public function store(StoreRequest $request) {
        $parent_task = $request->input('parent_task') ? Task::find($request->input('parent_task')) : null;

        $task = (new StoreUseCase(
            Auth::user(),
            $request->input('title'),
            $request->input('description') ?? '',
            $request->input('priority'),
            Status::find($request->input('status')),
            $request->input('limit_hours'),
            $request->input('due_date') ? Carbon::parse($request->input('due_date')) : null,
            $request->input('start_date') ? Carbon::parse($request->input('start_date')) : null,
            $request->input('tags', []),
            $request->input('assigned_users', []),
            $request->input('departments', []),
            $parent_task ? $parent_task->project : Project::find($request->input('project')),
            $parent_task
        ))->action();

        $this->saveTaskFiles($task, $request);
        LogHelper::saveLogAction($task, 'created', 'Created Task On: <b>' . $task->status->title . '</b>');

        toast('Task created', 'success');

        if($request->has('board') && is_numeric($request->input('board'))) {
            return redirect()->route('dashboard.projects.board', $request->input('board'));
        }

        return redirect()->route('dashboard.tasks.index');
    }

    public function edit(Request $request, Task $task)
    {
        [$users, $tasks, $departments, $projects, $task_statuses, $priorities, $tags] = $this->getData();

        if($request->ajax()) {
            return view('partials.projects.board.edit_modal', compact(
                'task',
                'users',
                'tasks',
                'departments',
                'projects',
                'task_statuses',
                'priorities',
                'tags'
            ));
        }

        return view('dashboard.tasks.edit', compact(
            'task',
            'users',
            'tasks',
            'departments',
            'projects',
            'task_statuses',
            'priorities',
            'tags'
        ));
    }

    public function update(UpdateRequest $request, Task $task)
    {
        (new UpdateUseCase(
            $task,
            Auth::user(),
            $request->input('title'),
            $request->input('description') ?? '',
            $request->input('priority'),
            Status::find($request->input('status')),
            $request->input('limit_hours'),
            Carbon::parse($request->input('due_date')),
            Carbon::parse($request->input('start_date')),
            $request->input('tags', []),
            $request->input('assigned_users', []),
            $request->input('departments', []),
            Project::find($request->input('project')),
            Task::find($request->input('parent_task'))
        ))->action();

        $this->saveTaskFiles($task, $request);
        LogHelper::saveLogAction($task, 'updated', 'Modified Task');

        toast('Task updated', 'success');

        if($request->has('board') && is_numeric($request->input('board'))) {
            return redirect()->route('dashboard.projects.board', $request->input('board'));
        }

        return redirect()->route('dashboard.tasks.index');
    }

    public function updateStatus(Request $request, Task $task, Status $status)
    {
        LogHelper::saveLogAction($task, 'moved', 'Moved the task from <b>' . $task->status->title . '</b> to <b>' . $status->title . '</b>');
        (new UpdateStatusUseCase($task, $status, $request->input('order')))->action();

        toast('Task status updated', 'success');
        return back();
    }

    public function destroy(Task $task)
    {
        $task->delete();

        toast('Task deleted', 'success');
        return back();
    }

    public function taskClockIn(Task $task)
    {
        TaskAttendanceHelper::clockAllTaskTimers();
        $attendance = TaskAttendanceHelper::getTodayTaskAttendanceOrCreate($task);
        $user_attendance = AttendanceHelper::getTodayAttendanceOrCreate();

        if(!$user_attendance->check_in || ($user_attendance->check_out && $user_attendance->check_in)) {
            if($user_attendance->check_out) {
                $user_attendance = AttendanceHelper::createAttendance(auth()->user(), now()->format('Y-m-d'));
            }

            $user_attendance->update([
                'check_in' => now()
            ]);
        }

        if ($attendance->check_in) {
            toast('You have already clocked in', 'info');
        }

        if ($attendance->check_out) {
            $attendance = TaskAttendanceHelper::createTaskAttendance(auth()->user(), now()->format('Y-m-d'), $task);
        }

        $attendance->update([
            'check_in' => now()
        ]);

        toast('You have successfully clocked in', 'success');
        return back();
    }

    public function taskClockOut(Task $task) {
        $attendance = TaskAttendanceHelper::getTodayTaskAttendanceOrCreate($task);

        if (!$attendance->check_in) {
            toast('You have not clocked in yet', 'info');
            return redirect()->route('dashboard.tasks.index');
        }

        if ($attendance->check_out) {
            toast('You have already clocked out', 'info');
            return redirect()->route('dashboard.tasks.index');
        }

        $attendance->update([
            'check_out' => now()
        ]);

        toast('You have successfully clocked out', 'success');
        return back();
    }

    public function uploadFile(Request $request)
    {
        $request->session()->forget('dropzone_tasks_temp_paths');

        if ($request->hasFile('dropzone_image')) {
            $files = $request->file('dropzone_image');

            foreach ($files as $idx => $file) {
                $tempPath = $file->storeAs('temp', $file->getClientOriginalName());

                $dropzoneTasksTempPaths = $request->session()->get('dropzone_tasks_temp_paths', []);
                $dropzoneTasksTempPaths[] = $tempPath;
                $request->session()->put('dropzone_tasks_temp_paths', $dropzoneTasksTempPaths);
            }

            return response()->json(['path' => $tempPath], 200);
        }

        return response()->json(['error' => 'No file uploaded.'], 400);
    }

    public function deleteFile(Request $request, TaskFile $taskFile)
    {
        $taskFile->delete();

        toast('File removed', 'success');
        return back();
    }

    public function downloadFile(TaskFile $taskFile)
    {
        return Storage::disk('public')->download($taskFile->path, $taskFile->title);
    }

    public function sendAction(Request $request, Task $task, string $action) {
        $route = match($action) {
            'edit' => route('dashboard.tasks.edit', $task->id),
            'duplicate' => BoardHelper::duplicateTask($task),
            'jump-top' => BoardHelper::jumpTopTask($task),
            'archive' => BoardHelper::archiveTask($task),
            'unarchive' => BoardHelper::unarchiveTask($task),
            'delete' => $this->destroy($task),
            'download' => $this->download($task),
            'taskClockIn' => $this->taskClockIn($task),
            'taskClockOut' => $this->taskClockOut($task),
            default => ApiResponse::fail('Invalid action'),
        };

        if ($request->isMethod('get')) {
            return is_string($route) ? $route : $route;
        }

        return ApiResponse::ok(['redirect' => is_string($route) ? $route : $route->getTargetUrl()]);
    }

    private function getData(): array
    {
        $users = User::all();
        $tasks = Task::whereHas('status', function ($query) {
            $query->where('title', '!=', 'Completed');
        })->whereNull('task_id')->get();

        $departments = Department::all();
        $projects = Project::all();
        $task_statuses = Status::where('morphable', Task::class)->get();
        $priorities = Task::PRIORITIES;
        $tags = Label::all();

        return [$users, $tasks, $departments, $projects, $task_statuses, $priorities, $tags];
    }

    private function getTaskCounters(): array
    {
        $tasks = Auth::user()->tasks;

        $counters = [
            'total' => $tasks->count(),
            'in_progress' => $tasks->where('status_id', 1)->count(),
            'completed' => $tasks->where('status_id', 2)->count(),
            'not_started' => $tasks->where('status_id', 3)->count(),
        ];

        return $counters;
    }

    private function saveTaskFiles(Task $task, Request $request) {
        if ($request->session()->has('dropzone_tasks_temp_paths')) {
            foreach ($request->session()->get('dropzone_tasks_temp_paths', []) as $idx => $tempPath) {
                $originalName = pathinfo($tempPath, PATHINFO_BASENAME);
                $extension = pathinfo($tempPath, PATHINFO_EXTENSION);
                $fileName = uniqid() . '.' . $extension;
                $permanentPath = 'task_files/' . $task->id . '/' . $fileName;

                $storaged = Storage::disk('public')->put($permanentPath, Storage::disk('local')->get($tempPath));
                Storage::disk('local')->delete($tempPath);
                $request->session()->forget('dropzone_tasks_temp_paths');

                if ($storaged) {
                    (new TaskFilesStoreUseCase(
                        $task,
                        Auth::user(),
                        $originalName,
                        $permanentPath,
                        Storage::disk('public')->mimeType($permanentPath),
                        Storage::disk('public')->size($permanentPath)
                    ))->action();
                    LogHelper::saveLogAction($task, 'attached', 'Attached <b>' . $originalName . '</b> to the task.');
                }
            }
        }
    }

    private function getBoardActions(Task $task): array
    {
        $actions = [];

        if(!in_array($task->id, Auth::user()->activeTaskTimers()->pluck('task_id')->toArray())) {
            $actions['clock_in'] = [
                'label' => 'Clock In',
                'icon' => 'bx bx-time-five',
                'action' => 'taskClockIn'
            ];
        } else {
            $actions['clock_out'] = [
                'label' => 'Clock Out',
                'icon' => 'bx bx-time-five',
                'action' => 'taskClockOut'
            ];
        }

        $actions = array_merge($actions, [
            'edit' => [
                'label' => 'Edit',
                'icon' => 'bx bx-edit',
                'action' => 'edit'
            ],
            'duplicate' => [
                'label' => 'Duplicate',
                'icon' => 'bx bx-duplicate',
                'action' => 'duplicate'
            ],
            'jump-top' => [
                'label' => 'Jump to Top',
                'icon' => 'bx bx-arrow-from-bottom',
                'action' => 'jump-top'
            ],
            'archive' => [
                'label' => 'Move to Archive',
                'icon' => 'bx bx-archive',
                'action' => 'archive'
            ],
            'delete' => [
                'label' => 'Move to Trash',
                'icon' => 'bx bx-trash',
                'action' => 'delete'
            ],
            'download' => [
                'label' => 'Print/Download',
                'icon' => 'bx bx-download',
                'action' => 'download'
            ]
        ]);

        return $actions;
    }
}
