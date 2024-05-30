<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Leaves\StoreRequest;
use App\Models\Company;
use App\Models\Leave;
use App\Models\LeaveType;
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
        $client = (new StoreUseCase(
            $request->input('name'),
            LeaveType::find($request->input('type')),
            $request->input('duration'),
            $request->input('date'),
            User::find($request->input('user_id')),
            $request->input('reason')
        ))->action();

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
