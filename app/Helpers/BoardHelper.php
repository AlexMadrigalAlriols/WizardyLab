<?php

namespace App\Helpers;

use App\Models\Task;

class BoardHelper {
    public static function archiveTask(Task $task)
    {
        $task->update([
            'archive_at' => now(),
        ]);

        toast('Task archived successfully!', 'success');
        return back();
    }

    public static function unarchiveTask(Task $task)
    {
        $task->update([
            'archive_at' => null,
        ]);

        toast('Task unarchived successfully!', 'success');
        return back();
    }

    public static function jumpTopTask(Task $task)
    {
        $tasks = Task::where('project_id', $task->project_id)
            ->where('status_id', $task->status_id)
            ->get();

        foreach ($tasks as $t) {
            $t->update([
                'order' => $t->order + 1,
            ]);
        }

        $task->update([
            'order' => 0,
        ]);

        toast('Task moved to top successfully!', 'success');
        return back();
    }

    public static function duplicateTask(Task $task)
    {
        $newTask = $task->replicate();
        $newTask->code = TaskHelper::generateCode($task->project);
        $newTask->order = $task->order + 1;
        $newTask->save();

        // ALL RELATIONS
        $newTask->users()->attach($task->users->pluck('id')->toArray());
        $newTask->labels()->attach($task->labels->pluck('id')->toArray());

        // duplicate images
        foreach ($task->files as $image) {
            $newTask->files()->create([
                'title' => $image->title,
                'path' => $image->path,
                'mime_type' => $image->mime_type,
                'size' => $image->size,
                'user_id' => $image->user_id,
            ]);
        }

        LogHelper::saveLogAction($newTask, 'created', 'Duplicated Task On: <b>' . $newTask->status->title . '</b>');

        toast('Task duplicated successfully!', 'success');
        return back();
    }
}
