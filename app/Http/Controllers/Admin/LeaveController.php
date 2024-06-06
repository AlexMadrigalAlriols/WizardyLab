<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\NotificationHelper;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Leaves\StoreRequest;
use App\Models\Company;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Notification;
use App\Models\User;
use App\UseCases\Leaves\StoreUseCase;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $query = Leave::query();

        [$query, $pagination] = PaginationHelper::getQueryPaginated($query, $request, Leave::class);

        $leaves = $query->get();
        $total = $query->count();

        return view('dashboard.leaves.index', compact('leaves', 'total', 'pagination'));
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

        foreach ($dates as $date) {
            $leave = (new StoreUseCase(
                $request->input('name'),
                LeaveType::find($request->input('type')),
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
                    $leave->id
                );
            }
        }

        toast('Leave created', 'success');
        return redirect()->route('dashboard.leaves.index');
    }

    public function edit(Leave $leave)
    {
        return view('dashboard.leaves.edit', compact('leave'));
    }

    public function update(Request $request, Leave $leave)
    {
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

    public function destroy(Leave $leave)
    {
        $leave->delete();

        toast('Leave deleted', 'success');
        return back();
    }
}
