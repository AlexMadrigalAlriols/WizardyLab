<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\DataTables\LabelsDataTable;
use App\Http\Requests\Labels\StoreRequest;
use App\Http\Requests\Labels\UpdateRequest;
use App\Http\Requests\MassDestroyRequest;
use App\Models\Label;
use App\UseCases\Labels\StoreUseCase;
use App\UseCases\Labels\UpdateUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LabelController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()) {
            $dataTable = new LabelsDataTable('labels');
            return $dataTable->make();
        }

        $query = Label::query();
        $total = $query->count();

        return view('dashboard.labels.index', compact('total'));
    }

    public function create()
    {
        $label = new Label();

        return view('dashboard.labels.create', compact('label'));
    }

    public function store(StoreRequest $request) {
        $status = (new StoreUseCase(
            $request->input('name'),
            ['background' => $request->input('background'), 'color' => $request->input('color')]
        ))->action();

        toast('Labels created', 'success');
        return redirect()->route('dashboard.labels.index');
    }

    public function edit(Label $label)
    {
        return view('dashboard.labels.edit', compact('label'));
    }

    public function update(UpdateRequest $request, Label $label)
    {
        $label = (new UpdateUseCase(
            $label,
            $request->input('name'),
            ['background' => $request->input('background'), 'color' => $request->input('color')]
        ))->action();

        toast('Label updated', 'success');
        return redirect()->route('dashboard.labels.index');
    }

    public function destroy(Label $label)
    {
        $label->delete();

        toast('Label deleted', 'success');
        return back();
    }

    public function massDestroy(MassDestroyRequest $request)
    {
        $ids = $request->input('ids');
        Label::whereIn('id', $ids)->delete();

        toast('Labels deleted', 'success');
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
