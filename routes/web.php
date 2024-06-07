<?php

use App\Http\Controllers\Admin\BoardController;
use App\Http\Controllers\Admin\BoardRuleController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\LabelController;
use App\Http\Controllers\Admin\LeaveController;
use App\Http\Controllers\Admin\LeaveTypeController;
use App\Http\Controllers\Admin\NoteController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\StatusController;
use App\Http\Controllers\Admin\TaskCommentController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserInventoriesController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::redirect('/', '/dashboard');
Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.', 'middleware' => ['auth']], static function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    //Inventory
    Route::resource('inventories', InventoryController::class);
    Route::resource('assignments', UserInventoriesController::class);

    //Tasks
    Route::resource('tasks', TaskController::class);
    Route::get('/tasks/{task}/update-status/{status}', [TaskController::class, 'updateStatus'])->name('tasks.update-status');
    Route::get('/tasks/{task}/clock-in', [TaskController::class, 'taskClockIn'])->name('task-clock-in');
    Route::get('/tasks/{task}/clock-out', [TaskController::class, 'taskClockOut'])->name('task-clock-out');
    Route::post('/tasks/upload_file', [TaskController::class, 'uploadFile'])->name('task.upload_file');
    Route::delete('/tasks/delete_file/{taskFile}', [TaskController::class, 'deleteFile'])->name('task.delete_file');
    Route::get('/tasks/download_file/{taskFile}', [TaskController::class, 'downloadFile'])->name('task.download_file');
    Route::post('/tasks/{task}/{action}', [TaskController::class, 'sendAction'])->name('tasks.action');

    //Comments
    Route::resource('{task}/comments', TaskCommentController::class)->only(['store', 'destroy']);

    // Clients
    Route::resource('clients', ClientController::class);

    // Companies
    Route::resource('companies', CompanyController::class);

    // Leaves
    Route::resource('leaves', LeaveController::class)->except(['update']);
    Route::get('/leaves/{leave}/approve', [LeaveController::class, 'update'])->name('leaves.approve');

    // Status
    Route::resource('statuses', StatusController::class)->except(['show']);

    // Labels
    Route::resource('labels', LabelController::class)->except(['show']);

    // Leave Types
    Route::resource('leaveTypes', LeaveTypeController::class)->except(['show']);

    // Notes
    Route::resource('notes', NoteController::class);

    // Notifications
    Route::get('/notifications/read', [DashboardController::class, 'readNotifications'])->name('notifications.read');

    //Project
    Route::resource('projects', ProjectController::class);
    Route::get('/projects/{project}/update-status/{status}', [ProjectController::class, 'updateStatus'])->name('projects.update-status');
    Route::get('/projects/update-order/{projectStatus}', [ProjectController::class, 'updateStatusOrder'])->name('projects.update-order');

    // Board
    Route::get('/projects/{project}/board', [BoardController::class, 'index'])->name('projects.board');
    Route::resource('/projects/{project}/board/automation', BoardRuleController::class)->except(['update']);
    Route::post('/projects/{project}/board/automation/{automation}/update', [BoardRuleController::class, 'update'])->name('automation.update');
    Route::get('/projects/{project}/board/automation/{automation}/copy', [BoardRuleController::class, 'copy'])->name('automation.copy');
    Route::post('/projects/{project}/board/automation/{automation}/changeActive', [BoardRuleController::class, 'changeActive'])->name('automation.changeActive');
    Route::get('/projects/{project}/board/configurations', [BoardController::class, 'configurations'])->name('projects.board.configurations');

    Route::get('/user/clock-in', [UserController::class, 'userClockIn'])->name('user-clock-in');
    Route::get('/user/clock-out', [UserController::class, 'userClockOut'])->name('user-clock-out');

    //inventaries

});

Auth::routes(['register' => false, 'reset' => false, 'verify' => false, 'confirm' => false]);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
