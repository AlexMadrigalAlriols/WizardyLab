<?php

namespace App\Helpers;

use App\Models\Attendance;
use App\Models\Task;
use App\Models\TaskAttendance;
use App\Models\User;
use Carbon\Carbon;

class TaskAttendanceHelper {
    public static function getTodayTaskAttendanceOrCreate(Task $task): TaskAttendance
    {
        $user = auth()->user();
        $today = now()->format('Y-m-d');

        $attendance = $user->taskAttendance()->where('date', $today)->where('task_id', $task->id)->orderBy('updated_at', 'desc')->first();

        if (!$attendance) {
            $attendance = self::createTaskAttendance($user, $today, $task);
        }

        return $attendance;
    }

    public static function createTaskAttendance(User $user, string $date, Task $task): TaskAttendance
    {
        return $user->taskAttendance()->create([
            'date' => $date,
            'task_id' => $task->id
        ]);
    }

    public static function clockAllTaskTimers(): void
    {
        $tasks = auth()->user()->activeTaskTimers;

        foreach ($tasks as $attendance) {
            $attendance->update([
                'check_out' => now()
            ]);
        }
    }

    public static function getTotalHoursDecimal(Task $task): float
    {
        $attendances = $task->taskAttendances()->whereNotNull('check_out')->get();
        $total = 0;

        foreach ($attendances as $attendance) {
            $total += TimerHelper::getAttendanceTimeDecimal($attendance);
        }

        // return rounded total max 2 decimals
        return round($total, 2);
    }

    public static function getTaskHoursChart(Task $task): array
    {
        $attendances = $task->taskAttendances()->whereNotNull('check_out')->get();
        $chart = [];

        foreach ($attendances as $attendance) {
            if(!isset($chart['values'][$attendance->user->name])) {
                $chart['values'][$attendance->user->name] = 0;
            }

            $chart['values'][$attendance->user->name] += TimerHelper::getAttendanceTimeDecimal($attendance, 2);
        }

        $labels = array_keys($chart['values']);
        $quotedLabels = array_map(function($label) {
            return "'" . htmlspecialchars_decode($label) . "'";
        }, $labels);
        $chart['labels'] = implode(',', $quotedLabels);

        return $chart;
    }

    // Get week task activity chart
    public static function getTaskActivityChart(Task $task): array
    {
        $attendances = $task->taskAttendances()->whereNotNull('check_out')->get();
        $chart = [];

        foreach ($attendances as $attendance) {
            $day = Carbon::parse($attendance->date)->format('l');
            if(!isset($chart['values'][$day])) {
                $chart['values'][$day] = 0;
            }

            $chart['values'][$day] += TimerHelper::getAttendanceTimeDecimal($attendance, 2);
        }

        return $chart;
    }
}
