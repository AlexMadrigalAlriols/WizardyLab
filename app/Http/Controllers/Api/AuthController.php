<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Helpers\AttendanceHelper;
use App\Helpers\ConfigurationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        $portal = auth()->user()->portal;
        $data = [
            'logo' => $portal->logo,
            'primary_light_color' => $portal->primary_light,
            'secondary_light_color' => $portal->secondary_light
        ];
        $portal->data = array_merge($portal->data, $data);

        if(!$portal->active) {
            return ApiResponse::fail('Portal not active!');
        }

        return ApiResponse::ok([
            'token' => $token,
            'portal' => auth()->user()->portal,
        ]);
    }

    public function getPortal() {
        $user = JWTAuth::parseToken()->authenticate();

        $portal = $user->portal;
        $data = [
            'logo' => $portal->logo,
            'primary_light_color' => $portal->primary_light,
            'secondary_light_color' => $portal->secondary_light
        ];
        $portal->data = array_merge($portal->data, $data);
        $portal->language = ConfigurationHelper::get('language') ?? 'es';

        if(!$portal->active) {
            return ApiResponse::fail('Portal not active!');
        }

        $user = auth()->user();
        $user->profile_img = $user->profile_url;
        $user->is_clockIn = $user->is_clockIn;
        $user->timer = $user->timer;
        $user->break = $user->attendanceTemplate->getBreakPerDay(now()->format('l'));
        $user->estimated_work_hours = $user->attendanceTemplate->getHoursPerDay(now()->format('l'));

        return ApiResponse::ok([
            'portal' => auth()->user()->portal,
            'user' => $user
        ]);
    }
}
