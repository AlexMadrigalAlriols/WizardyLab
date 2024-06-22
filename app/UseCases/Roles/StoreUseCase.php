<?php

namespace App\UseCases\Roles;

use App\Models\Role;
use App\UseCases\Core\UseCase;

class StoreUseCase extends UseCase
{
    public function __construct(
        protected string $name,
        protected array $permissions = [],
    ) {
    }

    public function action(): Role
    {
        $role = Role::create([
            'name' => $this->name,
            'guard_name' => 'web',
        ]);

        $role->permissions()->sync($this->permissions);

        return $role;
    }
}
