<?php

namespace App\Providers;

use App\Models\Department;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\ItemFile;
use App\Models\ItemUserInventory;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskFile;
use App\Models\User;
use App\Observers\DepartmentObserver;
use App\Observers\InvoiceObserver;
use App\Observers\ItemFilesObserver;
use App\Observers\ItemInventoryObserver;
use App\Observers\ItemObserver;
use App\Observers\ProjectObserver;
use App\Observers\TaskFilesObserver;
use App\Observers\TaskObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        TaskFile::observe(TaskFilesObserver::class);
        Task::observe(TaskObserver::class);
        Project::observe(ProjectObserver::class);
        User::observe(UserObserver::class);
        Department::observe(DepartmentObserver::class);
        ItemFile::observe(ItemFilesObserver::class);
        Item::observe(ItemObserver::class);
        ItemUserInventory::observe(ItemInventoryObserver::class);
        Invoice::observe(InvoiceObserver::class);
    }
}
