<?php

namespace App\Helpers;

use App\Models\Attendance;
use App\Models\Task;
use App\Models\TaskAttendance;
use App\Models\User;
use Carbon\Carbon;

class AttendanceHelper {
    public static function getTodayAttendanceOrCreate(?array $ubication = []): Attendance
    {
        $user = auth()->user();
        $today = now()->format('Y-m-d');

        $attendance = $user->attendances()->where('date', $today)->orderBy('updated_at', 'desc')->first();

        if (!$attendance) {
            $attendance = self::createAttendance($user, $today, $ubication);
        }

        return $attendance;
    }

    public static function createAttendance(User $user, string $date, array $ubication = []): Attendance
    {
        return $user->attendances()->create([
            'date' => $date,
            'data' => $ubication
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

    public static function getDayAttendance(User $user, Carbon $date): string
    {
        $attendances = $user->attendances()->where('date', $date)->get();
        $total = '00h 00m';

        // Want to
        foreach ($attendances ?? [] as $attendance) {
            $total = TimerHelper::addDailyTime($total, TimerHelper::getAttendanceTime($attendance));
        }

        return $total;
    }

    public static function getProductivity(Carbon $date)
    {

    }
}
