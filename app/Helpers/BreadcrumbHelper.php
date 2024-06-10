<?php

namespace App\Helpers;

class BreadcrumbHelper
{
    public static function generate(array $routes): array
    {
        $breadcrumbs = [];

        foreach ($routes as $idx => $route) {
            $breadcrumbs[] = [
                'label' => ucfirst(str_replace('global.', '', trans('global.' . $route))),
                'url' => $idx === count($routes) - 1 ? false : self::findRoute($route)
            ];
        }

        return $breadcrumbs;
    }

    private static function findRoute(string $route)
    {
        return match ($route) {
            'home' => route('home'),
            'settings' => route('user.settings'),
            'categories' => route('categories.list'),
            'qr' => route('user.qr'),
            default => self::getTaskUrl($route),
        };
    }

    private static function getTaskUrl(?string $task = null): string
    {
        // if($task) {
        //     return route('products.category.list', $task);
        // }

        return route('dashboard.index');
    }
}
