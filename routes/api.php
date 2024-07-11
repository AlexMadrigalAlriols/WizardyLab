<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskCommentController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\TaskFileController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login', [AuthController::class, 'login']);

Route::middleware('jwt.auth')->group(function() {
    Route::get('portal', [AuthController::class, 'getPortal']);

    // User
    Route::post('clock-out', [UserController::class, 'clockOut']);
    Route::post('clock-in', [UserController::class, 'clockIn']);

    // Tasks
    Route::get('tasks', [TaskController::class, 'index']);
    Route::get('tasks/{task}', [TaskController::class, 'show']);
    Route::put('tasks/{task}', [TaskController::class, 'update']);
    Route::post('tasks', [TaskController::class, 'store']);
    Route::delete('tasks/{task}', [TaskController::class, 'destroy']);
    Route::post('tasks/{task}/archive', [TaskController::class, 'archive']);

    // Task Images
    Route::delete('tasks/{task}/image/{image}', [TaskFileController::class, 'destroyImage']);

    //Coments
    Route::post('tasks/{task}/comment', [TaskCommentController::class, 'store']);
    Route::delete('tasks/{task}/comment/{comment}', [TaskCommentController::class, 'destroy']);

    // ClockIn
    Route::post('tasks/{task}/clock-in', [TaskController::class, 'taskClockIn']);
    Route::post('tasks/{task}/clock-out', [TaskController::class, 'taskClockOut']);
    Route::post('tasks/{task}/update-status/{status}', [TaskController::class, 'updateStatus']);
    Route::get('/getData', [TaskController::class, 'getData']);
});
