<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FileSystemHelper;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Inventories\StoreRequest;
use App\Http\Requests\Inventories\UpdateRequest;
use App\Models\Item;
use App\Models\ItemFile;
use App\UseCases\Inventories\StoreUseCase as InventoriesStoreUseCase;
use App\UseCases\Inventories\UpdateUseCase;
use App\UseCases\ItemFiles\StoreUseCase as ItemFilesStoreUseCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Item::query();

        [$query, $pagination] = PaginationHelper::getQueryPaginated($query, $request, Item::class);

        $items = $query->get();
        $counters = $this->getInventoriesCounters();

        return view('dashboard.items.index', compact('items', 'pagination', 'counters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $item = new Item();
        $request->session()->forget('dropzone_items_temp_paths');

        return view(
            'dashboard.items.create',
            compact(
                'item',
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $item = (
            new InventoriesStoreUseCase(
                $request->input('name'),
                $request->input('reference'),
                $request->input('stock'),
                $request->input('description'),
                $request->input('price'),
                $request->input('shop_place'),
            )
        )->action();

        $this->saveFiles($item, $request);

        toast('Item created', 'success');
        return redirect()->route('dashboard.items.index');
    }


    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        return view('dashboard.items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Item $item)
    {
        $request->session()->forget('dropzone_items_temp_paths');

        return view('dashboard.items.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Item $item)
    {
        $item = (
            new UpdateUseCase(
                $item,
                $request->input('name'),
                $request->input('reference'),
                $request->input('stock'),
                $request->input('description'),
                $request->input('price'),
                $request->input('shop_place'),
            )
        )->action();

        $this->saveFiles($item, $request);

        toast('Item edited', 'success');
        return redirect()->route('dashboard.items.index');
    }

    public function uploadFile(Request $request)
    {
        return FileSystemHelper::uploadFile($request, 'dropzone_items_temp_paths');
    }

    private function saveFiles(Item $item, Request $request)
    {
        $extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if ($request->session()->has('dropzone_items_temp_paths')) {
            foreach ($request->session()->get('dropzone_items_temp_paths', []) as $idx => $tempPath) {
                [$permanentPath, $originalName, $storaged] = FileSystemHelper::saveFile(
                    $request,
                    $tempPath,
                    'dropzone_items_temp_paths',
                    'item_files/' . $item->id . '/',
                    $extensions
                );

                if ($storaged) {
                    (new ItemFilesStoreUseCase(
                        $item,
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
    public function destroy(Item $item)
    {
        $item->delete();
        toast('Item deleted', 'success');
        return redirect()->route('dashboard.items.index');
    }

    private function getInventoriesCounters(): array
    {
        $items = Item::all();

        $counters = [
            'total' => $items->count(),
        ];

        return $counters;
    }

    public function downloadFile(ItemFile $itemFile)
    {
        return Storage::disk('public')->download($itemFile->path, $itemFile->title);
    }

    public function deleteFile(Request $request, ItemFile $itemFile)
    {
        $itemFile->delete();

        toast('File removed', 'success');
        return back();
    }
}
