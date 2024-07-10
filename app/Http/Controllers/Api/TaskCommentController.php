<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskComments\StoreRequest;
use App\Models\Task;
use App\Models\TaskComment;
use App\UseCases\TaskComments\StoreUseCase;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class TaskCommentController extends Controller
{
    public function store(StoreRequest $request, Task $task) {
        $user = JWTAuth::parseToken()->authenticate();

        $comment = (new StoreUseCase(
            $task,
            $user,
            $request->input('comment')
        ))->action();

        return ApiResponse::done('Comment added successfully');
    }

    public function destroy(Task $task, TaskComment $comment)
    {
        $comment->delete();

        return ApiResponse::done('Comment deleted successfully');
    }
}
