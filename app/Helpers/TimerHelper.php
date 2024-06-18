<?php

namespace App\Helpers;

use App\Models\Attendance;
use App\Models\TaskAttendance;
use Carbon\Carbon;

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

    public static function addDailyTime(string $time1, string $time2, $carbonFormat = true): string
    {
        preg_match('/(\d+)h (\d{2})m/', $time1, $matches1);

        if ($carbonFormat) {
            preg_match('/(\d+)h (\d{2})m/', Carbon::parse($time2)->format('H\h i\m'), $matches2);
        } else {
            preg_match('/(\d+)h (\d{2})m/', $time2, $matches2);
        }

        $hours1 = intval($matches1[1]);
        $minutes1 = intval($matches1[2]);
        $hours2 = intval($matches2[1]);
        $minutes2 = intval($matches2[2]);

        // Sumar horas y minutos
        $totalHours = $hours1 + $hours2;
        $totalMinutes = $minutes1 + $minutes2;

        // Manejar el carry-over de los minutos
        if ($totalMinutes >= 60) {
            $totalHours += floor($totalMinutes / 60);
            $totalMinutes = $totalMinutes % 60;
        }

        if($totalHours > 99) {
            return sprintf('%dh %02dm', $totalHours, $totalMinutes);
        }

        return sprintf('%02dh %02dm', $totalHours, $totalMinutes);
    }

    public static function addExcessTime(string $totalTime, string $addTime)
    {
        preg_match('/([+-]?\d+)h (\d{2})m/', $totalTime, $matches1);
        preg_match('/([+-]?\d+)h (\d{2})m/', $addTime, $matches2);

        $hoursTotal = intval($matches1[1]);
        $hoursToAdd = intval($matches2[1]);
        $totalHours = $hoursTotal + $hoursToAdd;
        $sign = ($totalHours >= 0) ? '+' : '-';
        $minutes1 = $sign . intval($matches1[2]);
        $minutes2 = $sign . intval($matches2[2]);

        $totalMinutes = (int) ($minutes1) + ($minutes2);

        if (abs($totalMinutes) >= 60) {
            if($sign === '-') {
                $totalHours -= (abs($totalMinutes) / 60);
                $totalMinutes = $totalMinutes + 60;
            } else {
                $totalHours += ($totalMinutes / 60);
                $totalMinutes = $totalMinutes - 60;
            }
        }

        // Formatear el resultado
        if (abs($totalHours) > 99) {
            return sprintf("%s%dh %02dm", $sign, abs($totalHours), abs($totalMinutes));
        }

        return sprintf("%s%02dh %02dm", $sign, abs($totalHours), abs($totalMinutes));
    }

    public static function getExcessTime($workedHours, $normalHoursPerDay = '8h 00m', $returnFalse = true): string|bool
    {
        // Extraer horas y minutos de workedHours
        preg_match('/(\d+)h (\d+)m/', $workedHours, $matchesWorked);
        $workedHoursInt = (int) $matchesWorked[1];
        $workedMinutesInt = (int) $matchesWorked[2];

        // Extraer horas y minutos de normalHoursPerDay
        preg_match('/(\d+)h (\d+)m/', $normalHoursPerDay, $matchesNormal);
        $normalHoursInt = (int) $matchesNormal[1];
        $normalMinutesInt = (int) $matchesNormal[2];

        // Calcular el tiempo trabajado y normal en minutos
        $workedTotalMinutes = $workedHoursInt * 60 + $workedMinutesInt;
        $normalTotalMinutes = $normalHoursInt * 60 + $normalMinutesInt;

        // Calcular la diferencia en minutos
        $minutesDifference = $workedTotalMinutes - $normalTotalMinutes;

        if ($minutesDifference != 0) {
            $sign = ($minutesDifference >= 0) ? '+' : '-';
            $minutesDifference = abs($minutesDifference);
            $hoursDifference = floor($minutesDifference / 60);
            $remainingMinutesDifference = $minutesDifference % 60;

            // Formatear resultado
            $excessTime = sprintf("%s%dh %02dm", $sign, $hoursDifference, $remainingMinutesDifference);
        } else {
            $excessTime = $returnFalse ? false : '0h 00m';
        }

        return $excessTime;
    }

    public static function getPercentage(string $time, string $total): string
    {
        if($total === '00h 00m') {
            return '0%';
        }

        preg_match('/(\d+)h (\d{2})m/', $time, $matches1);
        preg_match('/(\d+)h (\d{2})m/', $total, $matches2);

        $hours = intval($matches1[1]);
        $minutes = intval($matches1[2]);
        $totalHours = intval($matches2[1]);
        $totalMinutes = intval($matches2[2]);

        $percentage = ($hours * 60 + $minutes) / ($totalHours * 60 + $totalMinutes) * 100;

        return round($percentage, 2) . '%';
    }
}
