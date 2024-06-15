<?php

namespace App\Observers;

use App\Models\Item;
use App\Models\ItemUserInventory;

class ItemInventoryObserver
{
    public function deleted(ItemUserInventory $itemUserInventory)
    {
        if($itemUserInventory->assignment->items()->count() == 0){
            $itemUserInventory->assignment->delete();
        }
    }
}
