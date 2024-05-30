<?php

namespace App\UseCases\TaskFiles;

use App\Models\Project;
use App\Models\Status;
use App\Models\Task;
use App\Models\TaskFile;
use App\Models\User;
use App\UseCases\Core\UseCase;
use Carbon\Carbon;

class StoreUseCase extends UseCase
{
    public function __construct(
        protected Task $task,
        protected User $user,
        protected string $title,
        protected string $path,
        protected string $mime_type,
        protected float $size
    ) {
    }

    public function action(): TaskFile
    {
        $file = TaskFile::create([
            'task_id' => $this->task->id,
            'user_id' => $this->user->id,
            'title' => $this->title,
            'path' => $this->path,
            'mime_type' => $this->mime_type,
            'size' => $this->size
        ]);

        return $file;
    }
}
