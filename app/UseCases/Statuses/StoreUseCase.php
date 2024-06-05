<?php

namespace App\UseCases\Statuses;

use App\Models\Invoice;
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
        $type = match ($this->type) {
            'task' => Task::class,
            'project' => Project::class,
            'invoice' => Invoice::class,
            default => throw new \Exception('Invalid type')
        };

        $status = Status::create([
            'title' => $this->title,
            'morphable' => $type,
            'data' => $this->data
        ]);

        return $status;
    }
}
