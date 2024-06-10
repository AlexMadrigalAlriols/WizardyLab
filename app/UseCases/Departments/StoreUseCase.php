<?php

namespace App\UseCases\Departments;

use App\Models\Department;
use App\UseCases\Core\UseCase;

class StoreUseCase extends UseCase
{
    public function __construct(
        protected string $name,
    ) {
    }

    public function action(): Department
    {
        $department = Department::create([
            'name' => $this->name,
        ]);

        return $department;
    }
}
