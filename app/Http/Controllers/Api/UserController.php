<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Helpers\AttendanceHelper;
use App\Helpers\TaskAttendanceHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function clockIn(Request $request) {
        $user = JWTAuth::parseToken()->authenticate();

        $ubication = [
            'latitude' => $request->get('latitude'),
            'longitude' => $request->get('longitude'),
            'api' => true
        ];

        $attendance = AttendanceHelper::getTodayAttendanceOrCreate($ubication, $user);

        if ($attendance->check_in) {
            ApiResponse::fail('You have already clocked in');
        }

        if ($attendance->check_out) {
            $attendance = AttendanceHelper::createAttendance(auth()->user(), now()->format('Y-m-d'), $ubication);
        }

        $attendance->update([
            'check_in' => now()
        ]);
        return ApiResponse::done('You have successfully clocked in');
    }

    public function clockOut() {
        $user = JWTAuth::parseToken()->authenticate();

        $attendance = AttendanceHelper::getTodayAttendanceOrCreate(['api' => true], $user);

        if (!$attendance->check_in) {
            return ApiResponse::fail('You have not clocked in yet');
        }

        if ($attendance->check_out) {
            return ApiResponse::fail('You have already clocked out');
        }

        $attendance->update([
            'check_out' => now()
        ]);

        TaskAttendanceHelper::clockAllTaskTimers($user);
        return ApiResponse::done('You have successfully clocked out');
    }
}
