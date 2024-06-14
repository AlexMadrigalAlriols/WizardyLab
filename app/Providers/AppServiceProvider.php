<?php

namespace App\Providers;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskFile;
use App\Models\User;
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
    }
}
