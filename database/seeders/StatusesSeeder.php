<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Status;
use App\Models\Task;
use Illuminate\Database\Seeder;

class StatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['title' => 'New', 'data' => json_encode(['background' => 'primary']), 'morphable' => Project::class],
            ['title' => 'In Progress', 'data' => json_encode(['background' => 'warning']), 'morphable' => Project::class],
            ['title' => 'Completed', 'data' => json_encode(['background' => 'success']), 'morphable' => Project::class],
            ['title' => 'Canceled', 'data' => json_encode(['background' => 'danger']), 'morphable' => Project::class],
            ['title' => 'New', 'data' => json_encode(['background' => 'primary']), 'morphable' => Task::class],
            ['title' => 'In Progress', 'data' => json_encode(['background' => 'warning']), 'morphable' => Task::class],
            ['title' => 'Completed', 'data' => json_encode(['background' => 'success']), 'morphable' => Task::class],
            ['title' => 'Canceled', 'data' => json_encode(['background' => 'danger']), 'morphable' => Task::class],
        ];

        Status::insert($statuses);
    }
}
