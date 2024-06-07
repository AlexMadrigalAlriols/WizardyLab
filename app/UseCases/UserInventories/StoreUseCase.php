<?php

namespace App\UseCases\UserInventories;

use App\Models\UserInventories;
use App\UseCases\Core\UseCase;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;

class StoreUseCase extends UseCase
{
    public function __construct(
        protected int $users_id,
        protected int $inventories_id,
        protected int $quantity,
        protected ?Carbon $extract_date = null,
        protected ?Carbon $return_date = null,
    ) {
    }

    public function action(): UserInventories
    {
        $UserInventories = UserInventories::create([
            'users_id' => $this->users_id,
            'inventories_id' => $this->inventories_id,
            'quantity' => $this->quantity,
            'extract_date' => $this->extract_date,
            'return_date' => $this->return_date,
        ]);

        return $UserInventories;
    }
}
