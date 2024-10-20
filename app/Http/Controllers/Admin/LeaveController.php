<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\AdvancedFiltersHelper;
use App\Helpers\NotificationHelper;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\DataTables\LeavesDataTable;
use App\Http\Requests\Leaves\StoreRequest;
use App\Http\Requests\MassDestroyRequest;
use App\Models\Company;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Notification;
use App\Models\User;
use App\Traits\MiddlewareTrait;
use App\UseCases\Leaves\StoreUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LeaveController extends Controller
{
    use MiddlewareTrait;

    public function __construct()
    {
        $this->setMiddleware('leave');
    }

    public function index(Request $request)
    {
        if($request->ajax()) {
            $dataTable = new LeavesDataTable('leaves');
            return $dataTable->make();
        }

        $query = Leave::query();
        $total = $query->count();
        $advancedFilters = AdvancedFiltersHelper::getFilters(Leave::class);

        return view('dashboard.leaves.index', compact('total', 'advancedFilters'));
    }

    public function create()
    {
        $leave = new Leave();
        $leaveTypes = LeaveType::all();
        $users = User::all();

        return view('dashboard.leaves.create', compact('leave', 'leaveTypes', 'users'));
    }

    public function store(StoreRequest $request) {
        $user =  User::find($request->input('user_id'));
        $dates = explode(',', $request->input('date'));

        if($leaveType = LeaveType::findOrFail($request->input('type'))) {
            $total = $user->leaves()->where('leave_type_id', $leaveType->id)->where('approved', true)->count();

            if($total >= $leaveType->max_days) {
                toast('User has reached the maximum days for this leave type', 'error');
                return back();
            }
        }

        foreach ($dates as $date) {
            $leaf= (new StoreUseCase(
                $leaveType,
                $request->input('duration'),
                $date,
                $user,
                $request->input('reason')
            ))->action();

            if(auth()->user()->id !== $user->id) {
                NotificationHelper::notificate(
                    $user,
                    Notification::TYPES['leave'],
                    'Leave created',
                    $leaf->id
                );
            }
        }

        toast('Leave created', 'success');
        return redirect()->route('dashboard.leaves.index');
    }

    public function edit(Leave $leaf)
    {
        $leave = $leaf;
        return view('dashboard.leaves.edit', compact('leave'));
    }

    public function update(Request $request, Leave $leave)
    {
        if(auth()->user()->can('leave_approve')) {
            if($leaveType = LeaveType::findOrFail($leave->leave_type_id)) {
                $total = $leave->user->leaves()->where('leave_type_id', $leaveType->id)->where('approved', true)->count();

                if($total >= $leaveType->max_days) {
                    toast('User has reached the maximum days for this leave type', 'error');
                    return back();
                }
            }

            $leave->approved = true;
            $leave->save();

            if(auth()->user()->id !== $leave->user->id) {
                NotificationHelper::notificate(
                    $leave->user,
                    Notification::TYPES['leave'],
                    'Leave approved',
                    $leave->id
                );
            }

            toast('Leave approved', 'success');
            return redirect()->route('dashboard.leaves.index');
        }

        toast('You do not have permission to approve leaves', 'error');
        return back();
    }

    public function destroy(Leave $leaf)
    {
        try{
            $leaf->delete();
        } catch(\Exception $e) {
            toast('Error deleting leave', 'error');
            return back();
        }

        toast('Leave deleted', 'success');
        return back();
    }

    public function massDestroy(MassDestroyRequest $request)
    {
        $ids = $request->input('ids');
        Leave::whereIn('id', $ids)->delete();

        toast('Leaves deleted', 'success');
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
