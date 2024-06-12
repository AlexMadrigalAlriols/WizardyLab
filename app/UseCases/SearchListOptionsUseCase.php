<?php

namespace App\UseCases;

use App\Models\Status;
use App\UseCases\Core\UseCase as CoreUseCase;

class SearchListOptionsUseCase extends CoreUseCase
{
    public function __construct(
        protected ?string $search,
        protected string $model,
        protected string $field,
        protected ?string $method = null
    ) {
    }

    public function action(): array
    {
        if ($this->method) {
            try {
                return $this->{$this->method}();
            } catch (\Exception $th) {
                throw new \RuntimeException($th->getMessage());
            }
        }

        $model = 'App\\Models\\' . $this->model;
        $query = $model::query();

        if ($this->search) {
            $query->where($this->field, 'like', "%{$this->search}%");
        }

        if ($model !== Status::class) {
            $query->limit(10);
        }

        $results = $query
            ->select($this->field)
            ->groupBy($this->field)
            ->get()
            ->pluck($this->field)
            ->toArray();

        if (!empty($results) && is_bool($results[0])) {
            $results = [
                [0 => trans('global.no')],
                [1 => trans('global.yes')]
            ];
        }

        return $results;
    }
}
