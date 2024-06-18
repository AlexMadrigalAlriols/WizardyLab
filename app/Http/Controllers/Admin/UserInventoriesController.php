<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FileSystemHelper;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\DataTables\AssignmentsDataTable;
use App\Http\Requests\MassDestroyRequest;
use App\Http\Requests\UserInventories\StoreRequest;
use App\Http\Requests\UserInventories\UpdateRequest;
use App\Models\Item;
use App\Models\ItemFile;
use App\Models\ItemUserInventory;
use App\Models\User;
use App\Models\UserInventories;
use App\Models\UserInventory;
use App\UseCases\Inventories\UpdateUseCase;
use App\UseCases\UserInventories\StoreUseCase;
use App\UseCases\UserInventories\UpdateUseCase as UserInventoriesUpdateUseCase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserInventoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(request $request)
    {
        if($request->ajax()) {
            $dataTable = new AssignmentsDataTable('assignments');
            return $dataTable->make();
        }

        $query = UserInventory::query();
        $total = $query->count();

        return view('dashboard.assignments.index', compact('total'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $assignment = new UserInventory();
        $users = User::all();
        $inventories = Item::all();
        $inventories = $inventories->filter(function ($item) {
            return $item->remaining_stock > 0;
        });
        foreach ($inventories ?? [] as $item) {
            $item->remaining_stock = $item->remaining_stock;
            $item->name = $item->name . ' (' . $item->reference . ')';
        }
        $inventoryArray = array_values(array_filter($inventories->toArray()));

        return view('dashboard.assignments.create', compact('users','inventories', 'inventoryArray', 'assignment'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $item = (
            new StoreUseCase(
                User::find($request->input('user_id')),
                Carbon::parse($request->input('extract_date')),
                Carbon::parse($request->input('return_date')),
                $request->input('items')
            )
        )->action();

        toast('Assignment save correctly', 'success');
        return redirect()->route('dashboard.assignments.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(UserInventory $assignment)
    {
        return view("dashboard.assignments.show", compact('assignment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserInventory $assignment)
    {
        $users = User::all();
        $inventories = Item::all();

        $inventories = $inventories->filter(function ($item) {
            return $item->remaining_stock > 0;
        });
        foreach ($inventories ?? [] as $item) {
            $item->remaining_stock = $item->remaining_stock;
            $item->name = $item->name . ' (' . $item->reference . ')';
        }

        foreach ($assignment->items as $assig) {
            $assig->remaining_stock = $assig->item->remaining_stock;
            $assig->name = $assig->item->name;
        }
        $inventoryArray = array_values(array_filter($inventories->toArray()));

        return view('dashboard.assignments.edit', compact('users','inventories', 'inventoryArray', 'assignment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, UserInventory $assignment)
    {
        $item = (
            new UserInventoriesUpdateUseCase(
                $assignment,
                User::find($request->input('user_id')),
                Carbon::parse($request->input('extract_date')),
                Carbon::parse($request->input('return_date')),
                $request->input('items')
            )
        )->action();

        toast('Item edited', 'success');
        return redirect()->route('dashboard.assignments.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserInventory $assignment)
    {
        $assignment->delete();
        toast('Item deleted', 'success');

        return redirect()->route('dashboard.assignments.index');
    }

        private function getInventoriesCounters(): array
    {
        $inventories = UserInventory::all();

        $counters = [
            'total' => $inventories->count(),
        ];

        return $counters;
    }

        /**
     * Remove the specified resource from storage.
     */
    public function destroyLine(ItemUserInventory $assignment)
    {
        $assignment->delete();
        toast('Item deleted', 'success');

        return redirect()->route('dashboard.items.show', $assignment->item_id);
    }

    public function massDestroy(MassDestroyRequest $request)
    {
        $ids = $request->input('ids');
        UserInventory::whereIn('id', $ids)->delete();

        toast('Assignments deleted', 'success');
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
