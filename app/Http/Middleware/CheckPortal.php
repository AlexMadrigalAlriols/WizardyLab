<?php

namespace App\Http\Middleware;

use App\Helpers\SubdomainHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPortal
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $portal = SubdomainHelper::getPortal($request);

        if(!$portal || !$portal->active) {
            return response()->view('errors.404', [], 404);
        }

        if(!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::check() && Auth::user()->portal_id !== $portal->id) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['portal_id' => 'No tienes acceso a este portal.']);
        }

        return $next($request);
    }
}
