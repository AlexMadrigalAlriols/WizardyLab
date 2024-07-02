<?php

use App\Helpers\ConfigurationHelper;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\AttendanceTemplateController;
use App\Http\Controllers\Admin\BoardController;
use App\Http\Controllers\Admin\BoardRuleController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeliveryNoteController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\DocumentFolderController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\GlobalConfigurationController;
use App\Http\Controllers\Admin\HolidayController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\LabelController;
use App\Http\Controllers\Admin\LeaveController;
use App\Http\Controllers\Admin\LeaveTypeController;
use App\Http\Controllers\Admin\NoteController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SearchListOptionsController;
use App\Http\Controllers\Admin\StatusController;
use App\Http\Controllers\Admin\StockMovementController;
use App\Http\Controllers\Admin\TaskCommentController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserInventoriesController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TranslationController;
use App\Http\Controllers\Admin\LandingController;
use App\Models\Invoice;
use App\Models\Task;
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

Route::get('/template/{deliveryNote}', [DeliveryNoteController::class, 'download'])->name('template');

Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.', 'middleware' => ['checkPortal', 'throttle']], static function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    //Item
    Route::resource('items', ItemController::class);
    Route::post('/items-files/upload_file', [ItemController::class, 'uploadFile'])->name('items.upload_file');
    Route::get('/items-files/download_file/{itemFile}', [ItemController::class, 'downloadFile'])->name('items.download_file');
    Route::get('/items-files/delete_file/{itemFile}', [ItemController::class, 'deleteFile'])->name('items.delete_file');
    Route::delete('massDestroy/items', [ItemController::class, 'massDestroy'])->name('items.massDestroy');

    // Stock Movements
    Route::post('/stock-movements/{item}', [StockMovementController::class, 'store'])->name('stock-movements.store');

    //Assignments
    Route::resource('assignments', UserInventoriesController::class);
    Route::delete('/assignments-line/{assignment}/delete', [UserInventoriesController::class, 'destroyLine'])->name('assignments.line.delete');
    Route::delete('massDestroy/assignments', [UserInventoriesController::class, 'massDestroy'])->name('assignments.massDestroy');

    //Tasks
    Route::resource('tasks', TaskController::class);
    Route::get('/tasks/{task}/update-status/{status}', [TaskController::class, 'updateStatus'])->name('tasks.update-status');
    Route::get('/tasks/{task}/clock-in', [TaskController::class, 'taskClockIn'])->name('task-clock-in');
    Route::get('/tasks/{task}/clock-out', [TaskController::class, 'taskClockOut'])->name('task-clock-out');
    Route::post('/tasks/upload_file', [TaskController::class, 'uploadFile'])->name('task.upload_file');
    Route::delete('/tasks/delete_file/{taskFile}', [TaskController::class, 'deleteFile'])->name('task.delete_file');
    Route::get('/tasks/download_file/{taskFile}', [TaskController::class, 'downloadFile'])->name('task.download_file');
    Route::post('/tasks/{task}/{action}', [TaskController::class, 'sendAction'])->name('tasks.action');
    Route::get('/tasks/{task}/{action}/get', [TaskController::class, 'sendAction'])->name('tasks.action.get');
    Route::delete('massDestroy/tasks', [TaskController::class, 'massDestroy'])->name('tasks.massDestroy');

    //Comments
    Route::resource('{task}/comments', TaskCommentController::class)->only(['store', 'destroy']);

    // Clients
    Route::resource('clients', ClientController::class);
    Route::delete('massDestroy/clients', [ClientController::class, 'massDestroy'])->name('clients.massDestroy');

    // Companies
    Route::resource('companies', CompanyController::class);
    Route::delete('massDestroy/companies', [CompanyController::class, 'massDestroy'])->name('companies.massDestroy');

    // Leaves
    Route::resource('leaves', LeaveController::class)->except(['update']);
    Route::get('/leaves/{leave}/approve', [LeaveController::class, 'update'])->name('leaves.approve');
    Route::delete('massDestroy/leaves', [LeaveController::class, 'massDestroy'])->name('leaves.massDestroy');

    // Status
    Route::resource('statuses', StatusController::class)->except(['show']);
    Route::delete('massDestroy/statuses', [StatusController::class, 'massDestroy'])->name('statuses.massDestroy');

    // Labels
    Route::resource('labels', LabelController::class)->except(['show']);
    Route::delete('massDestroy/labels', [LabelController::class, 'massDestroy'])->name('labels.massDestroy');

    // Leave Types
    Route::resource('leaveTypes', LeaveTypeController::class)->except(['show']);
    Route::delete('massDestroy/leaveTypes', [LeaveTypeController::class, 'massDestroy'])->name('leaveTypes.massDestroy');

    //Departments
    Route::resource('departments', DepartmentController::class);
    Route::delete('massDestroy/departments', [DepartmentController::class, 'massDestroy'])->name('departments.massDestroy');

    // Notes
    Route::resource('notes', NoteController::class);

    // Notifications
    Route::get('/notifications/read', [DashboardController::class, 'readNotifications'])->name('notifications.read');

    //Project
    Route::resource('projects', ProjectController::class);
    Route::get('/projects/{project}/update-status/{status}', [ProjectController::class, 'updateStatus'])->name('projects.update-status');
    Route::get('/projects/update-order/{projectStatus}', [ProjectController::class, 'updateStatusOrder'])->name('projects.update-order');
    Route::delete('massDestroy/projects', [ProjectController::class, 'massDestroy'])->name('projects.massDestroy');

    // Board
    Route::get('/projects/{project}/board', [BoardController::class, 'index'])->name('projects.board');
    Route::resource('/projects/{project}/board/automation', BoardRuleController::class)->except(['update']);
    Route::post('/projects/{project}/board/automation/{automation}/update', [BoardRuleController::class, 'update'])->name('automation.update');
    Route::get('/projects/{project}/board/automation/{automation}/copy', [BoardRuleController::class, 'copy'])->name('automation.copy');
    Route::post('/projects/{project}/board/automation/{automation}/changeActive', [BoardRuleController::class, 'changeActive'])->name('automation.changeActive');
    Route::get('/projects/{project}/board/configurations', [BoardController::class, 'configurations'])->name('projects.board.configurations');

    Route::get('/user/clock-in', [UserController::class, 'userClockIn'])->name('user-clock-in');
    Route::get('/user/clock-out', [UserController::class, 'userClockOut'])->name('user-clock-out');

    // Global Configs
    Route::get('/global-configurations', [GlobalConfigurationController::class, 'index'])->name('global-configurations.index');
    Route::post('/global-configurations', [GlobalConfigurationController::class, 'store'])->name('global-configurations.store');
    Route::post('/global-configurations/upload_file', [GlobalConfigurationController::class, 'uploadFile'])->name('global-configurations.upload_file');

    // Invoices
    Route::resource('invoices', InvoiceController::class);
    Route::get('/projects/{project}/generate-invoice', [InvoiceController::class, 'generateProjectInvoice'])->name('projects.generate-invoice');
    Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'downloadInvoice'])->name('invoices.download');
    Route::delete('massDestroy/invoices', [InvoiceController::class, 'massDestroy'])->name('invoices.massDestroy');

    // Delivery notes
    Route::resource('deliveryNotes', DeliveryNoteController::class);
    Route::get('/deliveryNotes/{deliveryNote}/download', [DeliveryNoteController::class, 'download'])->name('deliveryNotes.download');
    Route::delete('massDestroy/deliveryNotes', [DeliveryNoteController::class, 'massDestroy'])->name('deliveryNotes.massDestroy');

    //users
    Route::resource('users', UserController::class);
    Route::post('/users/upload_file', [UserController::class, 'uploadFile'])->name('user.upload_file');
    Route::delete('massDestroy/users', [UserController::class, 'massDestroy'])->name('users.massDestroy');

    Route::resource('expenses', ExpenseController::class)->except(['update', 'edit']);
    Route::post('/expenses/upload_file', [ExpenseController::class, 'uploadFile'])->name('expenses.upload_file');
    Route::delete('/expensesBill/delete_file/{expenseBill}', [ExpenseController::class, 'deleteFile'])->name('expenses.delete_file');
    Route::delete('massDestroy/expenses', [ExpenseController::class, 'massDestroy'])->name('expenses.massDestroy');

    //Documents
    Route::resource('documents', DocumentFolderController::class);
    Route::get('/documents-update-order/{folder}', [DocumentFolderController::class, 'updateOrder'])->name('documents.update-order');
    Route::get('/my-documents/{folder}', [DocumentController::class, 'index'])->name('documents.list');
    Route::post('/my-documents/{folder}/upload-file', [DocumentController::class, 'uploadFile'])->name('documents.upload-file');
    Route::post('/my-documents/{folder}/assign-file', [DocumentController::class, 'store'])->name('documents.assign-file');
    Route::delete('/my-documents/{folder}/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy-document');
    Route::get('/my-documents/{folder}/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::get('/my-documents/{folder}/{document}', [DocumentController::class, 'show'])->name('documents.view');
    Route::put('/my-documents/{folder}/{document}', [DocumentController::class, 'update'])->name('documents.update-file');
    Route::get('/my-documents/{folder}/{document}/view-sign', [DocumentController::class, 'viewSignFile'])->name('documents.view-sign');
    Route::post('/my-documents/{document}/sign', [DocumentController::class, 'signDocument'])->name('documents.sign');

    //Atendance Front
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::resource('/attendanceTemplates', AttendanceTemplateController::class);
    Route::delete('massDestroy/attendanceTemplates', [AttendanceTemplateController::class, 'massDestroy'])->name('attendanceTemplates.massDestroy');
    Route::get('/attendance/download-extract', [AttendanceController::class, 'downloadPdfExtract'])->name('attendance.download-extract');
    Route::put('/attendance/{user}', [AttendanceController::class, 'update'])->name('attendance.update');

    // Roles
    Route::resource('roles', RoleController::class);
    Route::delete('massDestroy/roles', [RoleController::class, 'massDestroy'])->name('roles.massDestroy');

    // Select 2 Search list
    Route::get('search-list-options', SearchListOptionsController::class)->name('searchListOptions.index');

    //holidayscalendar
    Route::get('holidays', [HolidayController::class, 'index'])->name('holiday.index');
});

// Password reset link request routes...
 Route::get('password/emailsend', [ForgotPasswordController::class, "sendResetLinkEmail"])->name('sendResetLink');


Auth::routes(['register' => false, 'verify' => false, 'confirm' => false]);

//Translations
Route::get('js/translations.js', [TranslationController::class, 'index'])->name('translations');

Route::group(['middleware' => ['checkPortalExists', 'throttle.login']], static function () {
    Auth::routes(['register' => false, 'reset' => false, 'verify' => false, 'confirm' => false, 'logout' => false]);
});

Route::get('/landing', [LandingController::class, 'index'])->name('landing');
Route::post('/landing', [LandingController::class, 'store'])->name('landing.store');

Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
