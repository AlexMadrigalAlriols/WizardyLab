<?php

namespace App\UseCases\Departments;

use App\Models\Department;
use App\UseCases\Core\UseCase;

class UpdateUseCase extends UseCase
{
    public function __construct(
        protected Department $department,
        protected string $name,
        protected bool $active = false
    ) {
    }

    public function action(): Department
    {
        $this->department->update([
            'name' => $this->name,
        ]);

        return $this->department;
    }
}
