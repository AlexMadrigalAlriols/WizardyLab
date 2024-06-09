<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FileSystemHelper;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Inventories\StoreRequest;
use App\Http\Requests\Inventories\U;
use App\Http\Requests\Inventories\UpdateRequest;
use App\Models\Inventory;
use App\Models\InventoryFile;
use App\Models\TaskFile;
use App\UseCases\AuditLogs\StoreUseCase;
use App\UseCases\Inventories\StoreUseCase as InventoriesStoreUseCase;
use App\UseCases\Inventories\UpdateUseCase;
use App\UseCases\ItemFiles\StoreUseCase as ItemFilesStoreUseCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Inventory::query();

        [$query, $pagination] = PaginationHelper::getQueryPaginated($query, $request, Inventory::class);

        $inventories = $query->get();
        $counters = $this->getInventoriesCounters();

        return view('dashboard.inventories.index', compact('inventories', 'pagination', 'counters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $inventory = new Inventory();
        $request->session()->forget('dropzone_inventories_temp_paths');

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

        $this->saveFiles($inventory, $request);

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
    public function edit(Request $request, Inventory $inventory)
    {
        $request->session()->forget('dropzone_inventories_temp_paths');

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

        $this->saveFiles($inventory, $request);

        toast('Item edited', 'success');
        return redirect()->route('dashboard.inventories.index');
    }

    public function uploadFile(Request $request)
    {
        return FileSystemHelper::uploadFile($request, 'dropzone_inventories_temp_paths');
    }

    private function saveFiles(Inventory $inventory, Request $request)
    {
        if ($request->session()->has('dropzone_inventories_temp_paths')) {
            foreach ($request->session()->get('dropzone_inventories_temp_paths', []) as $idx => $tempPath) {
                [$permanentPath, $originalName, $storaged] = FileSystemHelper::saveFile(
                    $request,
                    $tempPath,
                    'dropzone_inventories_temp_paths',
                    'item_files/' . $inventory->id . '/'
                );

                if ($storaged) {
                    (new ItemFilesStoreUseCase(
                        $inventory,
                        Auth::user(),
                        $originalName,
                        $permanentPath,
                        Storage::disk('public')->size($permanentPath)
                    ))->action();
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        toast('Item deleted', 'success');
        return redirect()->route('dashboard.inventories.index');
    }

    private function getInventoriesCounters(): array
    {
        $inventories = Inventory::all();

        $counters = [
            'total' => $inventories->count(),
        ];

        return $counters;
    }

    public function downloadFile(InventoryFile $taskFile)
    {
        return Storage::disk('public')->download($taskFile->path, $taskFile->title);
    }
    public function deleteFile(Request $request, InventoryFile $taskFile)
    {
        $taskFile->delete();

        toast('File removed', 'success');
        return back();
    }
}

