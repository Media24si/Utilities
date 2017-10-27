<?php

namespace Media24si\Utilities\Scopes;

use Media24si\Utilities\Pagination\ApiPaginator;
use Illuminate\Database\Eloquent\Builder;

trait ApiPaginate
{
    public function scopeApiPaginate(Builder $query, int $perPage, $columns = ['*'], $pageName = 'page', $page = null)
    {
        return ApiPaginator::create($query, $perPage, $columns, $pageName, $page);
    }
}
