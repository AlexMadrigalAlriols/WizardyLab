<?php

namespace App\Observers;

use App\Models\Inventory;
use App\Models\InventoryFile;
use Illuminate\Support\Facades\Storage;

class InventoryFilesObserver
{
    public function deleting(Inventory $inventory)
    {
        $inventory->inventory_files()->delete();
        Storage::disk('public')->delete($inventoryFile->path);
    }
}
