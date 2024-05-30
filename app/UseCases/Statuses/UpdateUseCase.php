<?php

namespace App\UseCases\Statuses;

use App\Models\Status;
use App\UseCases\Core\UseCase;

class UpdateUseCase extends UseCase
{
    public function __construct(
        protected Status $status,
        protected string $title,
        protected array $data = []
    ) {
    }

    public function action(): Status
    {
        $this->status->update([
            'title' => $this->title,
            'data' => $this->data
        ]);

        return $this->status;
    }
}
