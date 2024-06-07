<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventories\StoreRequest;
use App\Http\Requests\Inventories\U;
use App\Http\Requests\Inventories\UpdateRequest;
use App\Models\Inventory;
use App\UseCases\AuditLogs\StoreUseCase;
use App\UseCases\Inventories\StoreUseCase as InventoriesStoreUseCase;
use App\UseCases\Inventories\UpdateUseCase;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventories = Inventory::all();

        return view('dashboard.inventories.index', compact('inventories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $inventory = new Inventory();

        return view(
            'dashboard.inventories.create',
            compact(
                'inventory',
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $inventory = (
            new InventoriesStoreUseCase(
                $request->input('name'),
                $request->input('reference'),
                $request->input('stock'),
                $request->input('description'),
                $request->input('price'),
                $request->input('shop_place'),
            )
        )->action();
        toast('Item created', 'success');
        return redirect()->route('dashboard.inventories.index');
    }


    /**
     * Display the specified resource.
     */
    public function show(Inventory $inventory)
    {

        return view('dashboard.inventories.show', compact('inventory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventory $inventory)
    {
        return view('dashboard.inventories.edit', compact('inventory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Inventory $inventory)
    {
        $inventory = (
            new UpdateUseCase(
                $inventory,
                $request->input('name'),
                $request->input('reference'),
                $request->input('stock'),
                $request->input('description'),
                $request->input('price'),
                $request->input('shop_place'),
            )
        )->action();
        toast('Item edited', 'success');
        return redirect()->route('dashboard.inventories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        toast('Item deleted', 'success');
        return back();
    }
}
