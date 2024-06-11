<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\DataTables\StatusesDataTable;
use App\Http\Requests\MassDestroyRequest;
use App\Http\Requests\Statuses\StoreRequest;
use App\Http\Requests\Statuses\UpdateRequest;
use App\Models\Company;
use App\Models\Leave;
use App\Models\Status;
use App\UseCases\Statuses\StoreUseCase;
use App\UseCases\Statuses\UpdateUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StatusController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()) {
            $dataTable = new StatusesDataTable('statuses');
            return $dataTable->make();
        }

        $query = Status::query();
        $total = $query->count();

        return view('dashboard.status.index', compact('total'));
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

    public function massDestroy(MassDestroyRequest $request)
    {
        $ids = $request->input('ids');
        Status::whereIn('id', $ids)->delete();

        toast('Status deleted', 'success');
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
