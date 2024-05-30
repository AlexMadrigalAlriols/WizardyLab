<?php

namespace App\UseCases\BoardRules;

use App\Models\BoardRule;
use App\Models\Project;
use App\UseCases\Core\UseCase;

class StoreUseCase extends UseCase
{
    public function __construct(
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

        $boardRule = BoardRule::create([
            'name' => $sentence,
            'type' => $this->type,
            'data'=> $this->data
        ]);

        $this->project->boardRules()->attach($boardRule);

        $this->createActions($boardRule);

        return $boardRule;
    }

    private function createActions(BoardRule $boardRule): void
    {
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
