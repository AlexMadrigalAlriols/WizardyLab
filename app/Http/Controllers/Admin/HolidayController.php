<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leaves = Auth::user()->leaves()->with('leaveType')->orderBy('date', 'desc')->get();

        $year = Carbon::now()->year;
        $calendars = [];

        for ($month = 1; $month <= 12; $month++) {
            $currentDate = Carbon::createFromDate($year, $month, 1);

            // Get the first day of the month
            $firstDayOfMonth = $currentDate->copy()->firstOfMonth();

            // Get the last day of the month
            $lastDayOfMonth = $currentDate->copy()->endOfMonth();

            // Get the first day of the week (Sunday) of the first day of the month
            $startOfWeek = $firstDayOfMonth->copy()->startOfWeek();

            // Get the last day of the week (Saturday) of the last day of the month
            $endOfWeek = $lastDayOfMonth->copy()->endOfWeek();

            // Generate dates for the calendar
            $dates = [];
            $current = $firstDayOfMonth->copy();
            [$diferenciaDiasInicio, $diferenciaDiasFinal] = [$startOfWeek->diffInDays($firstDayOfMonth), $lastDayOfMonth->diffInDays($endOfWeek)];


            while ($current <= $lastDayOfMonth) {
                $firstDay = 0;
                $lastDay = 0;
                $isholiday = $leaves->where('date', $current)->first()?->leaveType;
                if($isholiday){
                    [$firstDay, $lastDay] = [$leaves->where('date', $current->copy()->subDay())->first(), $leaves->where('date', $current->copy()->addDay())->first()];
                }

                $dates[] = [
                    'day' => $current->copy(),
                    'is_holiday' => $isholiday ?? false,
                    'first_day' => $firstDay?false:true,
                    'last_day' => $lastDay?false:true,
                ];
                $current->addDay();
            }

            $oldLeaves = $leaves;

            $calendars[] = [
                'dates' => $dates,
                'currentDate' => $currentDate,
                'diffInitial' => $diferenciaDiasInicio,
                'diffFinal' => $diferenciaDiasFinal,
            ];
        }

        return view('dashboard.holidays.index', compact('calendars', 'oldLeaves', 'leaves'));
    }

}
