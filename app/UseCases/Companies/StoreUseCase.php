<?php

namespace App\UseCases\Companies;

use App\Models\Company;
use App\UseCases\Core\UseCase;

class StoreUseCase extends UseCase
{
    public function __construct(
        protected string $name,
        protected bool $active = false
    ) {
    }

    public function action(): Company
    {
        $company = Company::create([
            'name' => $this->name,
            'active' => $this->active
        ]);

        return $company;
    }
}
