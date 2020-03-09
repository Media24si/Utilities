<?php

namespace Media24si\Utilities\Pagination;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class ApiPaginator extends LengthAwarePaginator
{
    protected $params = [];

    public function toArray()
    {
        return array_merge([
            'data' => $this->items->toArray(),
            'pagination' => [
                'total' => $this->total(),
                'per_page' => (int)$this->perPage(),
                'current_page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
                'next_page_url' => $this->nextPageUrl(),
                'prev_page_url' => $this->previousPageUrl(),
                'from' => $this->firstItem(),
                'to' => $this->lastItem()
            ],
        ], $this->params);
    }

    public static function create($data, $perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $paginate = $data->paginate($perPage, $columns, $pageName, $page);

        return new self($paginate->items, $paginate->total, $paginate->perPage(), $paginate->currentPage(), [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => $paginate->getPageName(),
        ]);
    }

    public function __get($name)
    {
        return isset($this->params[$name]) ? $this->params[$name] : null;
    }

    public function __set($name, $value)
    {
        $this->params[$name] = $value;
    }
}
