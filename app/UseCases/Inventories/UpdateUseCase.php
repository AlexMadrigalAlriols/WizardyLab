<?php

namespace App\UseCases\Inventories;

use App\Models\Inventory;
use App\UseCases\Core\UseCase;

class UpdateUseCase extends UseCase
{
    public function __construct(
        protected Inventory $inventory,
        protected string $name,
        protected string $reference,
        protected int $stock,
        protected ?string $description = null,
        protected ?int $price = null,
        protected ?string $store_place = null,
    ) {
    }

    public function action(): Inventory
    {
        $this->inventory->update([
            'name' => $this->name,
            'reference' => $this->reference,
            'stock' => $this->stock,
            'description' => $this->description,
            'price' => $this->price,
            'store_place' => $this->store_place,
        ]);

        return $this->inventory;
    }
}
