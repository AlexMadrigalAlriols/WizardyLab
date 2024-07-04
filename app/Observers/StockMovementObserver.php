<?php

namespace App\Observers;

use App\Models\StockMovement;

class StockMovementObserver
{
    public function created(StockMovement $stockMovement)
    {
        if ($stockMovement->type == 'sub') {
            $stockMovement->item->stock -= $stockMovement->quantity;
        } else if($stockMovement->type == 'sum') {
            $stockMovement->item->stock += $stockMovement->quantity;
        } else if($stockMovement->type == 'set') {
            $stockMovement->item->stock = $stockMovement->quantity;
        }

        $stockMovement->item->save();
    }
}
