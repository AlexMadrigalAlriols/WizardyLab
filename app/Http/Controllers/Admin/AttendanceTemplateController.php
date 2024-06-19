<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\AttendanceHelper;
use App\Helpers\SubdomainHelper;
use App\Helpers\TimerHelper;
use App\Http\Controllers\Controller;
use App\Http\DataTables\AttendanceTemplatesDataTable;
use App\Http\Requests\AttendanceTemplates\StoreRequest;
use App\Http\Requests\MassDestroyRequest;
use App\Models\AttendanceTemplate;
use App\Models\AttendanceTemplateDay;
use App\Models\Note;
use App\Traits\MiddlewareTrait;
use App\UseCases\AttendanceTemplates\StoreUseCase;
use App\UseCases\AttendanceTemplates\UpdateUseCase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AttendanceTemplateController extends Controller
{
    use MiddlewareTrait;

    public function __construct()
    {
        $this->setMiddleware('attendanceTemplate');
    }

    public function index(Request $request)
    {
        if($request->ajax()) {
            $dataTable = new AttendanceTemplatesDataTable('attendanceTemplates');
            return $dataTable->make();
        }

        $query = AttendanceTemplate::query();
        $total = $query->count();

        return view('dashboard.attendanceTemplates.index', compact('total'));
    }

    public function create()
    {
        $attendanceTemplate = new AttendanceTemplate();
        $weekDays = [];

        foreach (AttendanceTemplate::WEEKDAYS as $weekday) {
            $day = new AttendanceTemplateDay();
            $day->weekday = $weekday;

            if($day->weekday == 'Saturday' || $day->weekday == 'Sunday') {
                $day->start_time = '00:00';
                $day->end_time = '00:00';
                $day->start_break = '00:00';
                $day->end_break = '00:00';
            }

            $weekDays[] = $day;
        }
        $attendanceTemplate->weekDays = $weekDays;
        return view('dashboard.attendanceTemplates.create', compact('attendanceTemplate'));
    }

    public function store(StoreRequest $request)
    {
        $attendanceTemplate = (new StoreUseCase(
            $request->input('name'),
            ['background' => $request->input('background'), 'color' => $request->input('color')],
            $request->input('start_time'),
            $request->input('end_time'),
            $request->input('start_break'),
            $request->input('end_break')
        ))->action();

        toast('Jornada created', 'success');
        return redirect()->route('dashboard.attendanceTemplates.index');
    }

    public function edit(AttendanceTemplate $attendanceTemplate)
    {
        $weekDays = [];
        foreach (AttendanceTemplate::WEEKDAYS as $weekday) {
            $day = $attendanceTemplate->days()->where('weekday', $weekday)->first();

            if(!$day) {
                $day = new AttendanceTemplateDay();
                $day->weekday = $weekday;
            }

            $weekDays[] = $day;
        }
        $attendanceTemplate->weekDays = $weekDays;
        return view('dashboard.attendanceTemplates.edit', compact('attendanceTemplate'));
    }

    public function update(StoreRequest $request, AttendanceTemplate $attendanceTemplate)
    {
        $attendanceTemplate = (new UpdateUseCase(
            $attendanceTemplate,
            $request->input('name'),
            ['background' => $request->input('background'), 'color' => $request->input('color')],
            $request->input('start_time'),
            $request->input('end_time'),
            $request->input('start_break'),
            $request->input('end_break')
        ))->action();

        toast('Jornada updated', 'success');
        return redirect()->route('dashboard.attendanceTemplates.index');
    }

    public function destroy(AttendanceTemplate $attendanceTemplate)
    {
        $attendanceTemplate->delete();

        toast('Attendance Template deleted', 'success');
        return back();
    }

    public function massDestroy(MassDestroyRequest $request)
    {
        $ids = $request->input('ids');
        AttendanceTemplate::whereIn('id', $ids)->delete();

        toast('Attendance Templates deleted', 'success');
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
