<?php

namespace App\UseCases\LeaveTypes;

use App\Models\LeaveType;
use App\UseCases\Core\UseCase;

class UpdateUseCase extends UseCase
{
    public function __construct(
        protected LeaveType $leaveType,
        protected string $name,
        protected int $max_days = 0,
        protected array $data = []
    ) {
    }

    public function action(): LeaveType
    {
        $this->leaveType->update([
            'name' => $this->name,
            'max_days' => $this->max_days,
            'data' => $this->data
        ]);

        return $this->leaveType;
    }
}
