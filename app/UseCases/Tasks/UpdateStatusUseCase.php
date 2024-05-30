<?php

namespace App\UseCases\Tasks;

use App\Models\Status;
use App\Models\Task;
use App\UseCases\Core\UseCase;

class UpdateStatusUseCase extends UseCase
{
    public function __construct(
        protected Task $task,
        protected Status $status,
        protected ?int $order = null
    ) {
    }

    public function action(): Task
    {
        $this->task->update([
            'status_id'=> $this->status->id,
            'order' => $this->order ?? $this->task->order,
        ]);

        if($this->task->project && !$this->task->project->avaiableStatuses()->where('status_id', $this->status->id)->exists()) {
            $lastOrder = $this->task->project->avaiableStatuses()->max('order');
            $this->task->project->avaiableStatuses()->create([
                'status_id' => $this->status->id,
                'order' => $lastOrder + 1,
            ]);
        }

        return $this->task;
    }
}
