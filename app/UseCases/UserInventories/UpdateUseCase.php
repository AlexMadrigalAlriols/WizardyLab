<?php

namespace App\UseCases\UserInventories;

use App\Models\Inventory;
use App\Models\UserInventories;
use App\UseCases\Core\UseCase;
use Carbon\Carbon;

class UpdateUseCase extends UseCase
{
    public function __construct(
        protected UserInventories $UserInventories,
        protected int $users_id,
        protected int $inventories_id,
        protected int $quantity,
        protected ?Carbon $extract_date = null,
        protected ?Carbon $return_date = null,
    ) {
    }

    public function action(): UserInventories
    {
        $this->UserInventories->update([
            'user_id' => $this->users_id,
            'inventories_id' => $this->inventories_id,
            'quantity' => $this->quantity,
            'extract_date' => $this->extract_date,
            'return_date' => $this->return_date,
        ]);

        return $this->UserInventories;
    }
}
