<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Statuses\StoreRequest;
use App\Http\Requests\Statuses\UpdateRequest;
use App\Models\Company;
use App\Models\Status;
use App\UseCases\Statuses\StoreUseCase;
use App\UseCases\Statuses\UpdateUseCase;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function index(Request $request)
    {
        $query = Status::query();

        [$query, $pagination] = PaginationHelper::getQueryPaginated($query, $request, Status::class);

        $statuses = $query->get();
        $total = $query->count();

        return view('dashboard.status.index', compact('statuses', 'total', 'pagination'));
    }

    public function create()
    {
        $status = new Status();

        return view('dashboard.status.create', compact('status'));
    }

    public function store(StoreRequest $request) {
        $status = (new StoreUseCase(
            $request->input('name'),
            $request->input('type'),
            ['background' => $request->input('background'), 'color' => $request->input('color')]
        ))->action();

        toast('Status created', 'success');
        return redirect()->route('dashboard.statuses.index');
    }

    public function edit(Status $status)
    {
        return view('dashboard.status.edit', compact('status'));
    }

    public function update(UpdateRequest $request, Status $status)
    {
        $status = (new UpdateUseCase(
            $status,
            $request->input('name'),
            ['background' => $request->input('background'), 'color' => $request->input('color')]
        ))->action();

        toast('Status updated', 'success');
        return redirect()->route('dashboard.statuses.index');
    }

    public function destroy(Status $status)
    {
        $status->delete();

        toast('Status deleted', 'success');
        return back();
    }
}
