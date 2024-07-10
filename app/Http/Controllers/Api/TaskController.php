<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Helpers\AttendanceHelper;
use App\Helpers\ConfigurationHelper;
use App\Helpers\LogHelper;
use App\Helpers\PaginationHelper;
use App\Helpers\TaskAttendanceHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tasks\StoreRequest;
use App\Http\Resources\StatusResource;
use App\Http\Resources\TaskResource;
use App\Models\Department;
use App\Models\Label;
use App\Models\Project;
use App\Models\Status;
use App\Models\Task;
use App\Models\User;
use App\UseCases\Tasks\StoreUseCase;
use App\UseCases\Tasks\UpdateStatusUseCase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class TaskController extends Controller
{
    public function index(Request $request) {
        $user = JWTAuth::parseToken()->authenticate();

        $query = $user->tasks()->whereNull('tasks.task_id')
            ->where('status_id', '!=', ConfigurationHelper::get('completed_task_status'))
            ->whereNull('archive_at')
            ->orderBy('updated_at', 'desc');
        $task_statuses = Status::where('morphable', Task::class)->get();
        [$query, $pagination] = PaginationHelper::getQueryPaginated($query, $request, Task::class);
        $tasks = $query->get();

        return ApiResponse::ok([
            'tasks' => TaskResource::collection($tasks),
            'statuses' => StatusResource::collection($task_statuses),
            'pagination' => $pagination
        ]);
    }

    public function show(Task $task) {
        $user = JWTAuth::parseToken()->authenticate();

        return ApiResponse::ok(new TaskResource($task));
    }

    public function store(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $parent_task = $request->input('parent_task') ? Task::find($request->input('parent_task')) : null;
        $task = (new StoreUseCase(
            $user,
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

        LogHelper::saveLogAction($task, 'created', 'Created Task On: <b>' . $task->status->title . '</b>');

        return ApiResponse::done('Task created successfully');
    }

    public function taskClockIn(Task $task)
    {
        $user = JWTAuth::parseToken()->authenticate();

        TaskAttendanceHelper::clockAllTaskTimers($user);
        $attendance = TaskAttendanceHelper::getTodayTaskAttendanceOrCreate($task, $user);
        $user_attendance = AttendanceHelper::getTodayAttendanceOrCreate([], $user);

        if(!$user_attendance->check_in || ($user_attendance->check_out && $user_attendance->check_in)) {
            if($user_attendance->check_out) {
                $user_attendance = AttendanceHelper::createAttendance($user, now()->format('Y-m-d'));
            }

            $user_attendance->update([
                'check_in' => now()
            ]);
        }

        if ($attendance->check_out) {
            $attendance = TaskAttendanceHelper::createTaskAttendance($user, now()->format('Y-m-d'), $task);
        }

        $attendance->update([
            'check_in' => now()
        ]);

        return ApiResponse::done('You have successfully clocked in');
    }

    public function taskClockOut(Task $task) {
        $user = JWTAuth::parseToken()->authenticate();

        $attendance = TaskAttendanceHelper::getTodayTaskAttendanceOrCreate($task);

        if (!$attendance->check_in) {
            return ApiResponse::fail('You have not clocked in yet');
        }

        if ($attendance->check_out) {
            return ApiResponse::fail('You have already clocked out');
        }

        $attendance->update([
            'check_out' => now()
        ]);

        return ApiResponse::done('You have successfully clocked out');
    }

    public function updateStatus(Request $request, Task $task, Status $status)
    {
        $user = JWTAuth::parseToken()->authenticate();
        LogHelper::saveLogAction($task, 'moved', 'Moved the task from <b>' . $task->status->title . '</b> to <b>' . $status->title . '</b>');
        (new UpdateStatusUseCase($task, $status))->action();

        return ApiResponse::done('Task status updated successfully');
    }

    public function getData()
    {
        $user = JWTAuth::parseToken()->authenticate();

        $users = User::all();
        $tasks = Task::whereHas('status', function ($query) {
            $query->where('title', '!=', 'Completed');
        })->whereNull('task_id')->get();

        $departments = Department::all();
        $projects = Project::all();
        $task_statuses = Status::where('morphable', Task::class)->get();
        $priorities = Task::PRIORITIES;
        $tags = Label::all();

        return ApiResponse::ok([
            'users' => $users,
            'tasks' => $tasks,
            'departments' => $departments,
            'projects' => $projects,
            'statuses' => $task_statuses,
            'priorities' => $priorities,
            'tags' => $tags
        ]);
    }
}
