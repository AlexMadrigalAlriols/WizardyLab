<?php

namespace App\UseCases\LeaveTypes;

use App\Models\Label;
use App\Models\LeaveType;
use App\UseCases\Core\UseCase;

class StoreUseCase extends UseCase
{
    public function __construct(
        protected string $name,
        protected int $max_days = 0,
        protected array $data = []
    ) {
    }

    public function action(): LeaveType
    {
        $leaveType = LeaveType::create([
            'name' => $this->name,
            'max_days' => $this->max_days,
            'data' => $this->data
        ]);

        return $leaveType;
    }
}
