<?php

namespace App\UseCases\ItemFiles;

use App\Models\Inventory;
use App\Models\InventoryFile;
use App\Models\User;
use App\UseCases\Core\UseCase;

class StoreUseCase extends UseCase
{
    public function __construct(
        protected Inventory $inventory,
        protected User $user,
        protected string $title,
        protected string $path,
        protected float $size
    ) {
    }

    public function action(): InventoryFile
    {
        $file = InventoryFile::create([
            'inventory_id' => $this->inventory->id,
            'user_id' => $this->user->id,
            'title' => $this->title,
            'path' => $this->path,
            'size' => $this->size
        ]);

        return $file;
    }
}
