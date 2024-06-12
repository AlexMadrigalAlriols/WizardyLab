<?php

namespace App\UseCases\Expenses;

use App\Models\Expense;
use App\Models\Item;
use App\Models\Project;
use App\UseCases\Core\UseCase;

class StoreUseCase extends UseCase
{
    public function __construct(
        protected Project $project,
        protected string $name,
        protected int $quantity,
        protected float $amount,
        protected ?int $item_id = null,
        protected bool $facturable = true
    ) {
    }

    public function action(): Expense
    {
        $item = null;
        if($this->item_id !== null) {
            $item = Item::find($this->item_id);
        }

        $expense = Expense::create([
            'project_id' => $this->project->id,
            'item_id' => $item?->id,
            'name' => $this->name,
            'quantity' => $this->quantity,
            'amount' => $this->amount,
            'facturable' => $this->facturable,
        ]);

        if($item) {
            $item->stock -= $this->quantity;
            $item->save();
        }

        return $expense;
    }
}
