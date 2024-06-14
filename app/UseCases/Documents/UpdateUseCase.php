<?php

namespace App\UseCases\Documents;

use App\Models\Note;
use App\UseCases\Core\UseCase;
use Carbon\Carbon;

class UpdateUseCase extends UseCase
{
    public function __construct(
        protected Note $note,
        protected string $content,
        protected ?Carbon $date = null
    ) {
    }

    public function action(): Note
    {
        $this->note->update([
            'content' => $this->content,
            'date' => $this->date
        ]);

        return $this->note;
    }
}
