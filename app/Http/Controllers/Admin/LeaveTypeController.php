<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveTypes\StoreRequest;
use App\Http\Requests\LeaveTypes\UpdateRequest;
use App\Models\LeaveType;
use App\UseCases\LeaveTypes\StoreUseCase;
use App\UseCases\LeaveTypes\UpdateUseCase;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = LeaveType::query();

        [$query, $pagination] = PaginationHelper::getQueryPaginated($query, $request, LeaveType::class);

        $leaveTypes = $query->get();
        $total = $query->count();

        return view('dashboard.leaveTypes.index', compact('leaveTypes', 'total', 'pagination'));
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
}
