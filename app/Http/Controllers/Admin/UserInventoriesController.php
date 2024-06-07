<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserInventories\StoreRequest;
use App\Http\Requests\UserInventories\UpdateRequest;
use App\Models\Inventory;
use App\Models\User;
use App\Models\UserInventories;
use App\UseCases\Inventories\UpdateUseCase;
use App\UseCases\UserInventories\StoreUseCase;
use App\UseCases\UserInventories\UpdateUseCase as UserInventoriesUpdateUseCase;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserInventoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $UserInventories = UserInventories::all();
        return view("dashboard.assignments.index", compact('UserInventories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $assignment = new UserInventories;
        $users = User::all();
        $inventories = Inventory::all();
        $inventories = $inventories->filter(function ($inventory) {
            return $inventory->remaining_stock > 0;
        });
        return view('dashboard.assignments.create', compact('users','inventories', 'assignment'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $inventory = (
            new StoreUseCase(
                $request->input('users_id'),
                $request->input('inventories_id'),
                $request->input('quantity'),
                Carbon::parse($request->input('extract_date')),
                Carbon::parse($request->input('return_date')),
            )
        )->action();

        toast('Assignment save correctly', 'success');
        return redirect()->route('dashboard.assignments.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(UserInventories $assignment)
    {
            return view("dashboard.assignments.show", compact('assignment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserInventories $assignment)
    {
        $users = User::all();
        $inventories = Inventory::all();
        return view('dashboard.assignments.edit', compact('users','inventories', 'assignment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, UserInventories $assignment)
    {
        $inventory = (
            new UserInventoriesUpdateUseCase(
                $assignment,
                $request->input('users_id'),
                $request->input('inventories_id'),
                $request->input('quantity'),
                Carbon::parse($request->input('extract_date')),
                Carbon::parse($request->input('return_date')),
            )
        )->action();
        toast('Item edited', 'success');
        return redirect()->route('dashboard.assignments.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserInventories $assignment)
    {
        $assignment->delete();
        toast('Item deleted', 'success');
        return redirect()->route('dashboard.assignments.index');


    }
}
