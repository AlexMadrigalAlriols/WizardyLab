<?php

namespace App\UseCases\Companies;

use App\Models\Company;
use App\UseCases\Core\UseCase;

class UpdateUseCase extends UseCase
{
    public function __construct(
        protected Company $company,
        protected string $name,
        protected bool $active = false
    ) {
    }

    public function action(): Company
    {
        $this->company->update([
            'name' => $this->name,
            'active' => $this->active
        ]);

        return $this->company;
    }
}
