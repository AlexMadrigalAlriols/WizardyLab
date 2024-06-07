<?php

namespace App\Observers;

use App\Models\Task;

class TaskObserver
{
    public function deleting(Task $task)
    {
        $task->comments()->delete();
        $task->files()->delete();
        $task->activity()->delete();
        $task->subtasks()->delete();
        $task->labels()->detach();
    }
}
