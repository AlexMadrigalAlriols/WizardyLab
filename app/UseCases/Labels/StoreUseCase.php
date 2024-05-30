<?php

namespace App\UseCases\Labels;

use App\Models\Label;
use App\UseCases\Core\UseCase;

class StoreUseCase extends UseCase
{
    public function __construct(
        protected string $title,
        protected array $data = []
    ) {
    }

    public function action(): Label
    {
        $label = Label::create([
            'title' => $this->title,
            'data' => $this->data
        ]);

        return $label;
    }
}
