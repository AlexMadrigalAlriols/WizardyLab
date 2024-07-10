<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskCommentController;
use App\Http\Controllers\Api\TaskController;
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
    Route::post('tasks', [TaskController::class, 'store']);
    Route::post('tasks/{task}/comment', [TaskCommentController::class, 'store']);
    Route::delete('tasks/{task}/comment/{comment}', [TaskCommentController::class, 'destroy']);
    Route::post('tasks/{task}/clock-in', [TaskController::class, 'taskClockIn']);
    Route::post('tasks/{task}/clock-out', [TaskController::class, 'taskClockOut']);
    Route::post('tasks/{task}/update-status/{status}', [TaskController::class, 'updateStatus']);
    Route::get('/getData', [TaskController::class, 'getData']);
});
