<?php

namespace App\Helpers;

use App\Models\Attendance;
use App\Models\Task;
use App\Models\TaskAttendance;
use App\Models\User;
use Carbon\Carbon;

class AttendanceHelper {
    public static function getTodayAttendanceOrCreate(): Attendance
    {
        $user = auth()->user();
        $today = now()->format('Y-m-d');

        $attendance = $user->attendances()->where('date', $today)->orderBy('updated_at', 'desc')->first();

        if (!$attendance) {
            $attendance = self::createAttendance($user, $today);
        }

        return $attendance;
    }

    public static function createAttendance(User $user, string $date): Attendance
    {
        return $user->attendances()->create([
            'date' => $date,
        ]);
    }

    public static function getTimer(): string
    {
        $attendances = self::getAllAttendances();
        $total = '00:00:00';

        foreach ($attendances as $attendance) {
            $total = TimerHelper::addTime($total, TimerHelper::getAttendanceTime($attendance));
        }

        return $total;
    }

    public static function getAllAttendances()
    {
        return auth()->user()->attendances()->where('date', now()->format('Y-m-d'))->get();
    }
}
