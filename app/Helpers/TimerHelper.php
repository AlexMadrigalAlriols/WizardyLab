<?php

namespace App\Helpers;

use App\Models\Attendance;
use App\Models\TaskAttendance;

class TimerHelper {

    public static function addTime(string $time1, string $time2): string
    {
        $time1 = explode(':', $time1);
        $time2 = explode(':', $time2);

        $seconds = $time1[2] + $time2[2];
        $minutes = $time1[1] + $time2[1];
        $hours = $time1[0] + $time2[0];

        if ($seconds >= 60) {
            $seconds -= 60;
            $minutes++;
        }

        if ($minutes >= 60) {
            $minutes -= 60;
            $hours++;
        }

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }

    public static function getAttendanceTime(Attendance|TaskAttendance $attendance)
    {
        if(!$attendance->check_in) {
            return '00:00:00';
        }
        $checkIn = $attendance->check_in;

        if(!$attendance->check_out) {
            $checkOut = now();
        } else {
            $checkOut = $attendance->check_out;
        }

        $diff = $checkOut->diff($checkIn);

        return $diff->format('%H:%I:%S');
    }

    public static function getAttendanceTimeDecimal(Attendance|TaskAttendance $attendance, ?int $decimals = null): float
    {
        $time = self::getAttendanceTime($attendance);
        $time = explode(':', $time);

        $hours = $time[0];
        $minutes = $time[1];
        $seconds = $time[2];

        $minutes = $minutes / 60;
        $seconds = $seconds / 3600;

        if($decimals !== null) {
            return round($hours + $minutes + $seconds, $decimals);
        }

        return $hours + $minutes + $seconds;
    }
}
