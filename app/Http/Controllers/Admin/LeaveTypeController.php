<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\DataTables\LeaveTypesDataTable;
use App\Http\Requests\LeaveTypes\StoreRequest;
use App\Http\Requests\LeaveTypes\UpdateRequest;
use App\Http\Requests\MassDestroyRequest;
use App\Models\LeaveType;
use App\UseCases\LeaveTypes\StoreUseCase;
use App\UseCases\LeaveTypes\UpdateUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LeaveTypeController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()) {
            $dataTable = new LeaveTypesDataTable('leaveTypes');
            return $dataTable->make();
        }

        $query = LeaveType::query();
        $total = $query->count();

        return view('dashboard.leaveTypes.index', compact('total'));
    }

    public function create()
    {
        $leaveType = new LeaveType();

        return view('dashboard.leaveTypes.create', compact('leaveType'));
    }

    public function store(StoreRequest $request) {
        $leaveType = (new StoreUseCase(
            $request->input('name'),
            $request->input('max_days'),
            ['background' => $request->input('background'), 'color' => $request->input('color')]
        ))->action();

        toast('Leave Type created', 'success');
        return redirect()->route('dashboard.leaveTypes.index');
    }

    public function edit(LeaveType $leaveType)
    {
        return view('dashboard.leaveTypes.edit', compact('leaveType'));
    }

    public function update(UpdateRequest $request, LeaveType $leaveType)
    {
        $leaveType = (new UpdateUseCase(
            $leaveType,
            $request->input('name'),
            $request->input('max_days'),
            ['background' => $request->input('background'), 'color' => $request->input('color')]
        ))->action();

        toast('Leave Type updated', 'success');
        return redirect()->route('dashboard.leaveTypes.index');
    }

    public function destroy(LeaveType $leaveType)
    {
        $leaveType->delete();

        toast('Leave Type deleted', 'success');
        return back();
    }

    public function massDestroy(MassDestroyRequest $request)
    {
        $ids = $request->input('ids');
        LeaveType::whereIn('id', $ids)->delete();

        toast('Leave Types deleted', 'success');
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
