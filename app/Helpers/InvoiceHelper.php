<?php

namespace App\Helpers;

use App\Models\GlobalConfiguration;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

class InvoiceHelper {
    public static function generateProjectInvoice(Project $project): array
    {
        $amount = 0;
        $tasksIds = [];
        $stausId = ConfigurationHelper::get('completed_task_status');
        $tasks = $project->tasks()
            ->where('status_id', $stausId)
            ->whereNull('archive_at')
            ->whereNull('task_id')
            ->get();

        $amount = self::getTotalTasksAmount($tasks);

        foreach ($tasks as $task) {
            $tasksIds[] = $task->id;
        }

        $tax = ($amount * ConfigurationHelper::get('tax_value', 21)) / 100;
        $total = $amount + $tax;

        return [
            'amount' => $amount,
            'tax' => $tax,
            'total' => $total,
            'tasks_ids' => $tasksIds
        ];
    }

    public static function getTotalTasksAmount(Collection $tasks): float
    {
        $amount = 0;

        foreach ($tasks as $task) {
            $amount += $task->total_hours * ConfigurationHelper::get('price_per_hour', 15);
            $task->status_id = ConfigurationHelper::get('facturated_task_status');
            $task->save();
        }

        return $amount;
    }
}
