<?php

namespace App\UseCases\Users;

use App\Models\User;
use App\UseCases\Core\UseCase;

class GetQueryBuilderUseCase extends UseCase
{
    public function __construct(
        protected ?string $search,
        protected ?string $order_param = null,
        protected ?string $order_direction = null,
    ) {
        $this->order_param = $order_param ?? 'first_name';
        $this->order_direction = $order_direction ?? 'asc';
    }

    public function action(): \Illuminate\Database\Eloquent\Builder
    {
        $query = User::query();

        if($this->search) {
            $query->where('first_name', 'like', "%{$this->search}%")
                ->orWhere('last_name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%")
                ->orWhere('phone', 'like', "%{$this->search}%");
        }

        return $query->orderBy($this->order_param, $this->order_direction);
    }
}
