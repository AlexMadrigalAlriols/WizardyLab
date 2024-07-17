<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\Route;
use App\Models\User;
use App\Traits\MiddlewareTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    use MiddlewareTrait;

    public function __construct()
    {
        $this->setMiddleware('client');
    }

    public function index(Request $request)
    {
        if(!auth()->user()->portal->hasModuleAccess('routes')) {
            abort(403);
        }

        $routes = Route::all();
        $users = User::all();

        $today = Carbon::today();
        $currentDate = Carbon::now();
        $startOfMonth = $currentDate->copy()->startOfMonth();
        $endOfMonth = $currentDate->copy()->endOfMonth();

        // Calcula la diferencia inicial para el primer día del mes
        $diffInitial = $startOfMonth->dayOfWeekIso - 1; // dayOfWeekIso: 1 (lunes) a 7 (domingo)

        // Calcula la diferencia final para llenar los días al final del calendario
        $diffFinal = 7 - $endOfMonth->dayOfWeekIso; // dayOfWeekIso: 1 (lunes) a 7 (domingo)

        $dates = [];
        for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay()) {
            $firstDay = 0;
            $lastDay = 0;
            $leaves = Leave::all();
            $holiday = Leave::where('date', $date->format('Y-m-d'))->first()?->leaveType;

            if($holiday){
                [$firstDay, $lastDay] = [$leaves->where('date', $date->copy()->subDay())->first(), $leaves->where('date', $date->copy()->addDay())->first()];
            }

            $dates[] = [
                'day' => $date->copy(),
                'is_holiday' => $holiday ?? false,
                'first_day' => $firstDay ? false : true,
                'last_day' => $lastDay ? false : true,
            ];
        }

        $calendar = [
            'currentDate' => $currentDate,
            'diffInitial' => $diffInitial,
            'diffFinal' => $diffFinal,
            'dates' => $dates,
            'today' => $today,
        ];

        return view('dashboard.routes.index', compact('routes', 'users', 'calendar', 'today', 'currentDate'));
    }
}
