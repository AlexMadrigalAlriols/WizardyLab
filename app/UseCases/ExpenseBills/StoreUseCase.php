<?php

namespace App\UseCases\ExpenseBills;

use App\Models\Expense;
use App\Models\ExpenseBill;
use App\Models\User;
use App\UseCases\Core\UseCase;

class StoreUseCase extends UseCase
{
    public function __construct(
        protected Expense $expense,
        protected User $user,
        protected string $title,
        protected string $path,
        protected float $size
    ) {
    }

    public function action(): ExpenseBill
    {
        $bill = ExpenseBill::create([
            'expense_id' => $this->expense->id,
            'user_id' => $this->user->id,
            'title' => $this->title,
            'path' => $this->path,
            'size' => $this->size
        ]);

        return $bill;
    }
}
