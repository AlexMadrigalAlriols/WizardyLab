<?php

namespace App\Models\Traits;

use App\Helpers\AttendanceHelper;
use App\Models\Attendance;

trait HasAttendance {
    public function getTodayAttendance(): ?Attendance
    {
        return AttendanceHelper::getTodayAttendanceOrCreate();
    }

    public function getTimerAttribute(): string
    {
        return AttendanceHelper::getTimer();
    }

    public function getIsClockInAttribute(): bool
    {
        $attendance = $this->getTodayAttendance();

        return $attendance->check_in !== null && $attendance->check_out === null;
    }
}
