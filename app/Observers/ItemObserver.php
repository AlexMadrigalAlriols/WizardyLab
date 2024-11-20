<?php

namespace App\Observers;

use App\Models\Item;

class ItemObserver
{
    public function deleting(Item $item)
    {
        $item->stock_movements()->delete();
        $item->files()->delete();
        $item->assignments()->delete();
    }
}
