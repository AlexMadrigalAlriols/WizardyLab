<?php

namespace App\UseCases\Statuses;

use App\Models\Project;
use App\Models\Status;
use App\Models\Task;
use App\UseCases\Core\UseCase;

class StoreUseCase extends UseCase
{
    public function __construct(
        protected string $title,
        protected string $type,
        protected array $data = []
    ) {
    }

    public function action(): Status
    {
        $status = Status::create([
            'title' => $this->title,
            'morphable' => $this->type == 'task' ? Task::class : Project::class,
            'data' => $this->data
        ]);

        return $status;
    }
}
