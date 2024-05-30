<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskComments\StoreRequest;
use App\Models\Project;
use App\Models\Status;
use App\Models\Task;
use App\Models\TaskComment;
use App\UseCases\TaskComments\StoreUseCase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskCommentController extends Controller
{
    public function store(StoreRequest $request, Task $task) {
        $comment = (new StoreUseCase(
            $task,
            Auth::user(),
            $request->input('comment')
        ))->action();

        if($request->ajax()) {
            return view('partials.comments.index', ['task' => $task, 'comment' => $comment]);
        }

        toast('Comment created', 'success');
        return redirect()->route('dashboard.tasks.show', $task->id);
    }

    public function destroy(Request $request, Task $task, TaskComment $comment)
    {
        $comment->delete();

        if($request->ajax()) {
            return response()->json(['message' => 'Comment deleted']);
        }

        toast('Comment deleted', 'success');
        return redirect()->route('dashboard.tasks.show', $task->id);
    }
}
