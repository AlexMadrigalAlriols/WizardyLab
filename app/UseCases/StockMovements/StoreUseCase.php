<?php

namespace App\UseCases\StockMovements;

use App\Models\Item;
use App\Models\StockMovement;
use App\Models\User;
use App\UseCases\Core\UseCase;
use Carbon\Carbon;

class StoreUseCase extends UseCase
{
    public function __construct(
        protected Item $item,
        protected User $user,
        protected int $quantity,
        protected string $type,
        protected string $reason,
        protected ?Carbon $date = null
    ) {
    }

    public function action(): StockMovement
    {
        return StockMovement::create([
            'item_id' => $this->item->id,
            'user_id' => $this->user->id,
            'quantity' => $this->quantity,
            'type' => $this->type,
            'date' => $this->date ?? Carbon::now(),
            'reason' => $this->reason
        ]);
    }
}
