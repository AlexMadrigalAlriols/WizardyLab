<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FileSystemHelper;
use App\Http\Controllers\Controller;
use App\Http\DataTables\ExpensesDataTable;
use App\Http\Requests\Expenses\StoreRequest;
use App\Http\Requests\MassDestroyRequest;
use App\Models\Expense;
use App\Models\ExpenseBill;
use App\Models\Item;
use App\Models\Project;
use App\Traits\MiddlewareTrait;
use App\UseCases\ExpenseBills\StoreUseCase as ExpenseBillsStoreUseCase;
use App\UseCases\Expenses\StoreUseCase;
use App\UseCases\Inventories\UpdateUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    use MiddlewareTrait;

    public function __construct()
    {
        $this->setMiddleware('expense');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            $dataTable = new ExpensesDataTable('expenses');
            return $dataTable->make();
        }

        $query = Expense::query();
        $total = $query->count();

        return view('dashboard.expenses.index', compact('total'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $expense = new Expense();
        $request->session()->forget('dropzone_items_temp_paths');

        [$projects, $inventories] = $this->getData();
        $inventoryArray = array_values(array_filter($inventories->toArray()));

        return view(
            'dashboard.expenses.create',
            compact(
                'expense',
                'projects',
                'inventories',
                'inventoryArray'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $project = Project::find($request->input('project_id'));
        $extensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
        $storaged = false;

        if ($request->session()->has('dropzone_bills_temp_paths')) {
            foreach ($request->session()->get('dropzone_bills_temp_paths', []) as $idx => $tempPath) {
                [$permanentPath, $originalName, $storaged] = FileSystemHelper::saveFile(
                    $request,
                    $tempPath,
                    'dropzone_bills_temp_paths',
                    'bill_files/',
                    $extensions
                );
            }
        }

        foreach ($request->input('items', []) as $item) {
            $expense = (
                new StoreUseCase(
                    $project,
                    $item['name'],
                    $item['qty'],
                    $item['amount'],
                    $item['id'],
                    $request->input('facturable', true)
                )
            )->action();

            if ($storaged) {
                (new ExpenseBillsStoreUseCase(
                    $expense,
                    Auth::user(),
                    $originalName,
                    $permanentPath,
                    Storage::disk('public')->size($permanentPath)
                ))->action();
            }
        }

        toast('Expenses created', 'success');
        return redirect()->route('dashboard.expenses.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        return view('dashboard.expenses.show', compact('expense'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();
        toast('Expense deleted', 'success');
        return redirect()->route('dashboard.expenses.index');
    }

    public function massDestroy(MassDestroyRequest $request)
    {
        $ids = $request->input('ids');
        Expense::whereIn('id', $ids)->delete();

        toast('Expenses deleted', 'success');
        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function deleteFile(Request $request, ExpenseBill $expenseBill)
    {
        $expenseBill->delete();

        toast('File removed', 'success');
        return back();
    }

    public function uploadFile(Request $request)
    {
        return FileSystemHelper::uploadFile($request, 'dropzone_bills_temp_paths');
    }

    private function getData()
    {
        $projects = Project::all();
        $inventories = Item::all();

        $inventories = $inventories->filter(function ($item) {
            return $item->remaining_stock > 0;
        });
        foreach ($inventories ?? [] as $item) {
            $item->remaining_stock = $item->remaining_stock;
            $item->cover = $item->cover;
            $item->name = $item->name . ' (' . $item->reference . ')';
        }

        return [$projects, $inventories];
    }
}

