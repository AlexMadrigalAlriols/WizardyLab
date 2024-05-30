<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PaginationHelper {
    public static function getPages(Collection $results, int $perPage) {
        $total = $results->count();
        $pages = ceil($total / $perPage);

        return $pages;
    }

    public static function getQueryPaginated($query, Request $request, string $model, &$pagination = []): array
    {
        $pagination['pages'] = self::getPages($query->get(), $model::PAGE_SIZE);
        $page = $request->input('page', 1);

        if($page && $page != 'all' && is_numeric($page)) {
            $pagination['skip'] = $model::PAGE_SIZE * ($page - 1);
            $pagination['take'] = $model::PAGE_SIZE;
            $query->skip($pagination['skip']);
            $query->take($pagination['take']);
        }

        return [$query, $pagination];
    }
}
