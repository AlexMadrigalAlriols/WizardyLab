<?php

namespace App\UseCases\TaskComments;

use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;
use App\UseCases\Core\UseCase;

class StoreUseCase extends UseCase
{
    public function __construct(
        protected Task $task,
        protected User $user,
        protected string $comment
    ) {
    }

    public function action(): TaskComment
    {
        $comment = TaskComment::create([
            'user_id' => $this->user->id,
            'task_id' => $this->task->id,
            'text'=> $this->comment
        ]);

        return $comment;
    }
}
