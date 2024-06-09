<?php

namespace App\Observers;

use App\Models\InventoryFile;
use Illuminate\Support\Facades\Storage;

class InventoryFilesObserver
{
    public function deleting(InventoryFile $inventoryFile)
    {
        Storage::disk('public')->delete($inventoryFile->path);
    }
}
