<?php

namespace App\UseCases\Leaves;

use App\Models\Company;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\User;
use App\UseCases\Core\UseCase;
use Carbon\Carbon;

class StoreUseCase extends UseCase
{
    public function __construct(
        protected string $name,
        protected LeaveType $leaveType,
        protected string $duration,
        protected string $date,
        protected User $user,
        protected ?string $reason = null
    ) {
    }

    public function action(): Leave
    {
        $dates = explode(',', $this->date);

        foreach ($dates as $date) {
            $leave = Leave::create([
                'name' => $this->name,
                'leave_type_id' => $this->leaveType->id,
                'date' => Carbon::parse($date),
                'user_id' => $this->user->id,
                'reason' => $this->reason
            ]);
        }

        return $leave;
    }
}
