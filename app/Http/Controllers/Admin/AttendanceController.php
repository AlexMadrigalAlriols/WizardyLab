<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApiResponse;
use App\Helpers\AttendanceHelper;
use App\Helpers\SubdomainHelper;
use App\Helpers\TaskAttendanceHelper;
use App\Helpers\TimerHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Attendances\UpdateRequest;
use App\Models\Attendance;
use App\Traits\MiddlewareTrait;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;
use Spatie\FlareClient\Api;

class AttendanceController extends Controller
{
    use MiddlewareTrait;

    public function __construct()
    {
        $this->setMiddleware('attendance');
    }

    public function index(Request $request)
    {
        $portal = SubdomainHelper::getPortal($request);
        $user = auth()->user();

        $actualDate = Carbon::now();
        $currentMonth = $actualDate->month;
        if($request->has('month')) {
            $month = $request->month;
        } else {
            $month = $currentMonth;
        }
        $month = $month > 12 || $month < 1 ? $currentMonth : $month;
        $year = $actualDate->year;
        [$dates, $totals] = $this->getDates($month, $year);

        return view('dashboard.attendance.index', compact('dates', 'totals', 'month', 'year', 'currentMonth', 'user'));
    }

    public function downloadPdfExtract(Request $request)
    {
        $portal = SubdomainHelper::getPortal($request);
        $user = auth()->user();
        $actualDate = Carbon::now();
        $currentMonth = $actualDate->month;
        if($request->has('month')) {
            $month = $request->month;
        } else {
            $month = $currentMonth;
        }

        $month = $month > 12 || $month < 1 ? $currentMonth : $month;

        if($month == $currentMonth) {
            toast('No puedes descargar el extracto del mes actual', 'error');
            return redirect()->route('dashboard.attendance.index');
        }

        $year = $actualDate->year;
        [$dates, $totals] = $this->getDates($month, $year);
        $month = Carbon::create($year, $month, 1);
        $logoPath = $portal->logo;
        $logoBase64 = base64_encode(file_get_contents($logoPath));

        $dompdf = new Dompdf();
        $html = view('templates.mensualHourExtract', compact(
            'user',
            'portal',
            'dates',
            'currentMonth',
            'month',
            'year',
            'totals',
            'logoBase64'
        ))->render();
        $dompdf->loadHtml($html);
        $dompdf->render();
        $output = $dompdf->output();
        $fileName = 'extract_' . $user->code . '_' . $month->isoFormat('MMMM') . '_' . $year . '.pdf';
        $headers = [
            'Content-Type' => 'application/pdf',
        ];

        // Retornar la respuesta HTTP con el PDF como contenido descargable
        return Response::make($output, 200, $headers)->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    private function getDates($month, $year): array
    {
        $workTemplate = auth()->user()->attendanceTemplate;

        [$totalWorkedHours, $totalEstimatedHours, $totalExcessTime] = ['00h 00m', '00h 00m', '00h 00m'];
        $startDate = Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();
        $dates = [];

        while ($startDate->lte($endDate)) {
            $holiday = false;
            $workedHours = AttendanceHelper::getDayAttendance(auth()->user(), $startDate);
            $hoursPerDay = $workTemplate->getHoursPerDay($startDate->isoFormat('dddd'));
            if($leaveDay = auth()->user()->leaveDays()->where('date', $startDate->toDateString())->where('approved', true)->first()) {
                $holiday = $leaveDay;
                $hoursPerDay = '00h 00m';
            }
            $excessTime = TimerHelper::getExcessTime($workedHours, $holiday != false ? '00h 00m' : $hoursPerDay);
            $totalWorkedHours = TimerHelper::addDailyTime($totalWorkedHours, $workedHours, false);
            $totalEstimatedHours = TimerHelper::addDailyTime($totalEstimatedHours, $hoursPerDay, false);
            $productivity = TaskAttendanceHelper::getDayAttendance(auth()->user(), $startDate);
            $productivityPercentage = TimerHelper::getPercentage($productivity, $hoursPerDay);

            if(!$startDate->isFuture()) {
                $totalExcessTime = TimerHelper::addExcessTime($totalExcessTime, $excessTime ? $excessTime : '00h 00m');
            }

            $dates[] = [
                'day' => $startDate->copy(),
                'worked_hours' => $workedHours,
                'hours_per_day' => $hoursPerDay,
                'excess_time' => !$startDate->isFuture() ? $excessTime : false,
                'holiday' => $holiday,
                'productivity' => $productivity,
                'productivity_percentage' => $productivityPercentage,
                'attendances' => auth()->user()->attendances()->where('date', $startDate->toDateString())->get(),
            ];
            $startDate->addDay();
        }

        $totals = [
            'worked_hours' => $totalWorkedHours,
            'estimated_hours' => $totalEstimatedHours,
            'balance' => $totalExcessTime,
        ];

        return [$dates, $totals];
    }

    public function update(UpdateRequest $request)
    {
        $user = auth()->user();
        $date = Carbon::parse($request->input('date'));

        foreach ($request->input('check_in') ?? [] as $idx => $timer) {
            if($timer != null && $request->input('check_out')[$idx] != null && !$date->isFuture()) {
                if($request->input('ids', []) && ($request->ids[$idx] ?? null) != null) {
                    $attendance = Attendance::find($request->ids[$idx]);

                    if($attendance->user_id != $user->id) {
                        continue;
                    }
                } else {
                    $attendance = new Attendance();
                    $attendance->user_id = $user->id;
                    $attendance->date = $request->input('date');

                }

                $attendance->check_in = Carbon::parse($timer);
                $attendance->check_out = Carbon::parse($request->input('check_out')[$idx]);
                $attendance->data = ['edited' => true];
                $attendance->save();
                $attendance->refresh();
            }
        }

        $holiday = false;
        $workTemplate = $user->attendanceTemplate;
        $workedHours = AttendanceHelper::getDayAttendance(auth()->user(), $date);
        $hoursPerDay = $workTemplate->getHoursPerDay($date->isoFormat('dddd'));
        if($leaveDay = auth()->user()->leaveDays()->where('date', $date->toDateString())->where('approved', true)->first()) {
            $holiday = $leaveDay;
            $hoursPerDay = '00h 00m';
        }
        $excessTime = TimerHelper::getExcessTime($workedHours, $holiday != false ? '00h 00m' : $hoursPerDay);

        return ApiResponse::ok([
            'worked_hours' => $workedHours,
            'hours_per_day' => $hoursPerDay,
            'excess_time' => $excessTime
        ]);
    }
}
