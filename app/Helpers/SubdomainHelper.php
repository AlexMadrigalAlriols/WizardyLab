<?php

namespace App\Helpers;

use App\Models\Portal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SubdomainHelper
{
    public static function getPortal(Request $request): ?Portal
    {
        $subdomain = explode('.', $request->getHost())[0];

        if (config('app.env') === 'local' && config('app.local_portal')) {
            $portal = Portal::find(env('LOCAL_PORTAL'));

            if ($portal) {
                session(['portal_id' => $portal->id]);
            }

            return $portal;
        }

        return Cache::lock('portal_subdomain_' . $subdomain)->get(function () use ($subdomain) {
            return Portal::where('subdomain', $subdomain)->where('active', 1)->first();
        });
    }
}
