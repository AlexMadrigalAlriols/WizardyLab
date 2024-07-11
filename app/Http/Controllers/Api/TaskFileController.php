<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskFile;
use Tymon\JWTAuth\Facades\JWTAuth;

class TaskFileController extends Controller
{
    public function destroy(Task $task, TaskFile $image)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $image->delete();

        return ApiResponse::done('Image deleted successfully');
    }
}
