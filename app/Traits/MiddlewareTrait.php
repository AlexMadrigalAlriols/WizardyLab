<?php

namespace App\Traits;

trait MiddlewareTrait
{
    private function setMiddleware(string $scope): void
    {
        $method = request()?->route()?->getActionMethod() ?? "";
        $permission = $this->getPermission($method);

        $this->middleware("has-permission:{$scope}_{$permission}");
    }

    private function getPermission(string $method): string
    {
        return match ($method) {
            'create', 'store' => 'create',
            'edit', 'update' => 'edit',
            'destroy', 'massDestroy' => 'delete',
            'show' => 'view',
            default => 'view',
        };
    }
}
