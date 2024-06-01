<?php

namespace App\UseCases\TaskComments;

use App\Helpers\NotificationHelper;
use App\Models\Notification;
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

        foreach ($this->task->users as $user) {
            if($this->user->id != $user->id) {
                NotificationHelper::notificate(
                    $user,
                    Notification::TYPES['comment'],
                    'Added comment on the task',
                    $comment->id
                );
            }
        }

        return $comment;
    }
}
