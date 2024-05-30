<?php

namespace App\UseCases\Notes;

use App\Models\Note;
use App\Models\User;
use App\UseCases\Core\UseCase;
use Carbon\Carbon;

class StoreUseCase extends UseCase
{
    public function __construct(
        protected string $content,
        protected Carbon $date,
        protected User $user
    ) {
    }

    public function action(): Note
    {
        $note = Note::create([
            'content' => $this->content,
            'date' => $this->date,
            'user_id' => $this->user->id
        ]);

        return $note;
    }
}
