<?php

namespace App\Observers;

use App\Models\Item;

class ItemObserver
{
    public function deleting(Item $item)
    {
        $item->files()->delete();
        $item->assignments()->delete();
    }
}
