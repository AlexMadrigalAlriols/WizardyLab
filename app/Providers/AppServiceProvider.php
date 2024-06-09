<?php

namespace App\Providers;

use App\Models\Department;
use App\Models\InventoryFile;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskFile;
use App\Observers\DepartmentObserver;
use App\Observers\InventoryFilesObserver;
use App\Observers\ProjectObserver;
use App\Observers\TaskFilesObserver;
use App\Observers\TaskObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        TaskFile::observe(TaskFilesObserver::class);
        Task::observe(TaskObserver::class);
        Project::observe(ProjectObserver::class);
        Department::observe(DepartmentObserver::class);
        InventoryFile::observe(InventoryFilesObserver::class);
    }
}
