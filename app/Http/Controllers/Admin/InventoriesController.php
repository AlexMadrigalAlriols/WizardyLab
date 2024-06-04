<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventories\StoreRequest;
use App\Models\Inventories;
use App\UseCases\Inventories\UpdateUseCase;
use Illuminate\Http\Request;

class InventoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventories = Inventories::all();

        return view('dashboard.inventories.index', compact('inventories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $inventory = new Inventories();

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
    public function store(Request $request)
    {
        $item = new Inventories();
        $item->name = $request->input('name');
        $item->stock = $request->input('stock');
        $item->reference = $request->input('reference');
        $item->description = $request->input('description');
        $item->price = $request->input('price');
        $item->shop_place = $request->input('shop_place');
        $item->save();

        toast('Item created', 'success');
        return redirect()->route('dashboard.inventories.index');
    }


    /**
     * Display the specified resource.
     */
    public function show(inventories $inventory)
    {

        return view('dashboard.inventories.show', compact('inventory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventories $inventory)
    {
        return view('dashboard.inventories.edit', compact('inventory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inventories $inventory)
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
    public function destroy(Inventories $inventory)
    {
        $inventory->delete();
        toast('Item deleted', 'success');
        return back();
    }
}
