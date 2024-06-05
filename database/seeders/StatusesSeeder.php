<?php

namespace Database\Seeders;

use App\Models\Invoice;
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
            ['title' => 'New', 'data' => json_encode(['background' => '#00bfff', 'color' => '#ffffff']), 'morphable' => Project::class],
            ['title' => 'In Progress', 'data' => json_encode(['background' => '#ff7e38', 'color' => '#ffffff']), 'morphable' => Project::class],
            ['title' => 'Completed', 'data' => json_encode(['background' => '#088743', 'color' => '#ffffff']), 'morphable' => Project::class],
            ['title' => 'Canceled', 'data' => json_encode(['background' => '#ae2121', 'color' => '#ffffff']), 'morphable' => Project::class],

            ['title' => 'New', 'data' => json_encode(['background' => '#00bfff', 'color' => '#ffffff']), 'morphable' => Task::class],
            ['title' => 'In Progress', 'data' => json_encode(['background' => '#ff7e38', 'color' => '#ffffff']), 'morphable' => Task::class],
            ['title' => 'Completed', 'data' => json_encode(['background' => '#088743', 'color' => '#ffffff']), 'morphable' => Task::class],
            ['title' => 'Canceled', 'data' => json_encode(['background' => '#ae2121', 'color' => '#ffffff']), 'morphable' => Task::class],
            ['title' => 'Facturated', 'data' => json_encode(['background' => '#e06666', 'color' => '#ffffff']), 'morphable' => Task::class],
            ['title' => 'Bugs', 'data' => json_encode(['background' => '#470d7d', 'color' => '#ffffff']), 'morphable' => Task::class],

            ['title' => 'Paid', 'data' => json_encode(['background' => '#088743', 'color' => '#ffffff']), 'morphable' => Invoice::class],
            ['title' => 'No Paid', 'data' => json_encode(['background' => '#b63737', 'color' => '#ffffff']), 'morphable' => Invoice::class]
        ];

        Status::insert($statuses);
    }
}
