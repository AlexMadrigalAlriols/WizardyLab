<?php

namespace App\Helpers;

use App\Models\Project;
use App\Models\Task;

class TaskHelper {
    public static function generateCode(?Project $project = null): string
    {
        $query = Task::query()->withTrashed();

        if($project) {
            $query->where('project_id', $project->id);
        }
        $query->orderBy('id', 'desc');
        $task = $query->first();

        if($task) {
            $code = $task->code;
            $code = substr($code, strpos($code, '-') + 1);
            $code = (int) $code + 1;
            $code = str_pad($code, 5, '0', STR_PAD_LEFT);

            if($project) {
                return $project->code . '-' . $code;
            }

            return 'T-' . $code;
        }

        do {
            $num = 0001;

            $code = 'T-' . $num;

            if($project) {
                $code = $project->code . '-' . $num;
            }

            $num++;
        } while (Task::where('code', $code)->exists());

        return $code;
    }
}
