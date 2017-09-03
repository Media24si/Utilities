<?php

namespace Media24si\Utilities\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait WhereWhen
{
    public function scopeWhereWhen(Builder $query, $column, $key = null, $operator = null, Request $request = null): Builder
    {
        $key = $key ?? $column;
        $request = $request ?? \request();

        if (!$request->input($key)) {
            return $query;
        }

        return $query->where($column, $operator ?? '=', $request->input($key));
    }
}
