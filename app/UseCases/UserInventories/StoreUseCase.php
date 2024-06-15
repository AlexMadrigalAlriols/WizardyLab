<?php

namespace App\UseCases\UserInventories;

use App\Models\ItemUserInventory;
use App\Models\User;
use App\Models\UserInventory;
use App\UseCases\Core\UseCase;
use Carbon\Carbon;

class StoreUseCase extends UseCase
{
    public function __construct(
        protected User $user,
        protected ?Carbon $extract_date = null,
        protected ?Carbon $return_date = null,
        protected array $items = []
    ) {
    }

    public function action(): UserInventory
    {
        $user_inventory = UserInventory::create([
            'user_id' => $this->user->id,
            'extract_date' => $this->extract_date,
            'return_date' => $this->return_date,
        ]);

        foreach ($this->items as $item) {
            $assignmentExists = ItemUserInventory::where('user_inventory_id', $user_inventory->id)
                ->where('item_id', $item['id'])
                ->first();

            if($assignmentExists) {
                $assignmentExists->update([
                    'quantity' => $assignmentExists->quantity + $item['qty']
                ]);
                continue;
            }

            ItemUserInventory::create([
                'user_inventory_id' => $user_inventory->id,
                'item_id' => $item['id'],
                'quantity' => $item['qty'],
            ]);
        }

        return $user_inventory;
    }
}
