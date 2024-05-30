<?php

namespace App\UseCases\BoardRules;

use App\Models\BoardRule;
use App\Models\Project;
use App\Models\Status;
use App\Models\Task;
use App\Models\User;
use App\UseCases\Core\UseCase;
use Carbon\Carbon;

class UpdateUseCase extends UseCase
{
    public function __construct(
        protected BoardRule $boardRule,
        protected Project $project,
        protected string $name,
        protected string $type = 'TRIGGER',
        protected array $data = [],
        protected array $actions = []
    ) {
    }

    public function action(): BoardRule
    {
        $sentence = $this->getRuleSentence();

        $this->boardRule->update([
            'name' => $sentence,
            'type' => $this->type,
            'data'=> $this->data
        ]);

        $this->createActions($this->boardRule);

        return $this->boardRule;
    }

    private function createActions(BoardRule $boardRule): void
    {
        $boardRule->actions()->delete();

        foreach($this->actions as $idx => $action) {
            $boardRuleLine = $boardRule->actions()->create([
                'operator' => 'ACTION',
                'order' => $idx,
                'data' => $action
            ]);
        }
    }

    private function getRuleSentence(): string
    {
        $sentence = $this->name . ', ';

        foreach($this->actions as $action) {
            $sentence .= $action['sentence'] . ', ';
        }

        return $sentence;
    }
}
