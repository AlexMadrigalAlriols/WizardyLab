<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApiResponse;
use App\Helpers\AttendanceHelper;
use App\Helpers\TaskAttendanceHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('dashboard.index');
    }

    public function userAttendance(Request $request) {
        $attendance = AttendanceHelper::getTodayAttendanceOrCreate();

        return ApiResponse::ok(
            [
                'clock' => [
                    'in' => $attendance->check_in,
                    'out' => $attendance->check_out,
                    'timer' => AttendanceHelper::getTimer($attendance)
                ]
            ]
        );
    }

    public function userClockIn(Request $request) {
        $attendance = AttendanceHelper::getTodayAttendanceOrCreate();

        if ($attendance->check_in) {
            toast('You have already clocked in', 'info');
        }

        if ($attendance->check_out) {
            $attendance = AttendanceHelper::createAttendance(auth()->user(), now()->format('Y-m-d'));
        }

        $attendance->update([
            'check_in' => now()
        ]);

        toast('You have successfully clocked in', 'success');
        return redirect()->route('dashboard.index');
    }

    public function userClockOut(Request $request) {
        $attendance = AttendanceHelper::getTodayAttendanceOrCreate();

        if (!$attendance->check_in) {
            toast('You have not clocked in yet', 'info');
            return redirect()->route('dashboard.index');
        }

        if ($attendance->check_out) {
            toast('You have already clocked out', 'info');
            return redirect()->route('dashboard.index');
        }

        $attendance->update([
            'check_out' => now()
        ]);

        TaskAttendanceHelper::clockAllTaskTimers();
        toast('You have successfully clocked out', 'success');
        return redirect()->route('dashboard.index');
    }
}
