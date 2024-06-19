<?php

namespace App\Http\Middleware;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class HasPermission
{
    public function handle($request, $next, string $permission)
    {
        abort_if(Gate::denies($permission), Response::HTTP_FORBIDDEN, 'Forbidden');
        return $next($request);
    }
}
