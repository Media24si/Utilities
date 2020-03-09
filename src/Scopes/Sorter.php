<?php

namespace Media24si\Utilities\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait Sorter
{
    public function scopeSorter(Builder $query, $sortString): Builder
    {
        if (!$sortString) {
            return $query;
        }

        collect(explode(',', $sortString))
            ->each(function ($field) use ($query) {
                $field = trim($field);
                $dir = ($field[0] == '-') ? 'desc' : 'asc';
                $field = ltrim($field, '-');
                $query->orderBy($field, $dir);
            });

        return $query;
    }
}
