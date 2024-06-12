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

        if(str_contains($request->getHost(), 'wizardylab')) {
            $portal = Portal::find(1);

            if($portal) {
                session(['portal_id' => $portal->id]);
            }

            return $portal;
        }

        $portal = Cache::get('portal_subdomain_' . $subdomain, function () use($subdomain) {
            return  Portal::where('subdomain', $subdomain)->where('active', 1)->first();;
        });

        if($portal) {
            session(['portal_id' => $portal->id]);
        }

        return $portal;
    }
}
