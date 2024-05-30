<?php

namespace App\UseCases\Labels;

use App\Models\Label;
use App\UseCases\Core\UseCase;

class UpdateUseCase extends UseCase
{
    public function __construct(
        protected Label $label,
        protected string $title,
        protected array $data = []
    ) {
    }

    public function action(): Label
    {
        $this->label->update([
            'title' => $this->title,
            'data' => $this->data
        ]);

        return $this->label;
    }
}
