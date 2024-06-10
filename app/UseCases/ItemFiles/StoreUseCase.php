<?php

namespace App\UseCases\ItemFiles;

use App\Models\Item;
use App\Models\ItemFile;
use App\Models\User;
use App\UseCases\Core\UseCase;

class StoreUseCase extends UseCase
{
    public function __construct(
        protected Item $item,
        protected User $user,
        protected string $title,
        protected string $path,
        protected float $size
    ) {
    }

    public function action(): ItemFile
    {
        $file = ItemFile::create([
            'item_id' => $this->item->id,
            'user_id' => $this->user->id,
            'title' => $this->title,
            'path' => $this->path,
            'size' => $this->size
        ]);

        return $file;
    }
}
