<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApiResponse;
use App\Helpers\BoardAutomationHelper;
use App\Helpers\PaginationHelper;
use App\Helpers\TaskAttendanceHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\BoardRules\StoreRequest;
use App\Http\Requests\BoardRules\UpdateRequest;
use App\Models\BoardRule;
use App\Models\Client;
use App\Models\Department;
use App\Models\Project;
use App\Models\ProjectStatuses;
use App\Models\Status;
use App\Models\Task;
use App\Models\User;
use App\UseCases\BoardRules\StoreUseCase;
use App\UseCases\BoardRules\UpdateUseCase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoardController extends Controller
{
    public function index(Request $request, Project $project)
    {
        $user = Auth::user();

        $tasks = $user->tasks()->where('project_id', $project->id)->whereNull('archive_at')->orderBy('order')->get();
        $statuses = $project->avaiableStatuses()->orderBy('order')->get();

        return view('dashboard.projects.board.index', compact('project', 'tasks', 'statuses'));
    }

    public function configurations(Request $request, Project $project)
    {
        $user = Auth::user();

        return view('dashboard.projects.board.configurations.index', compact('project', 'user'));
    }
}
