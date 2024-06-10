<?php

namespace App\Observers;

use App\Models\ItemFile;
use Illuminate\Support\Facades\Storage;

class ItemFilesObserver
{
    public function deleting(ItemFile $itemFile)
    {
        Storage::disk('public')->delete($itemFile->path);
    }
}
