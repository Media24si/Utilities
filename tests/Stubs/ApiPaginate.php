<?php

namespace Tests\Stubs;

class ApiPaginate extends \Illuminate\Database\Eloquent\Model
{
    use \Media24si\Utilities\Scopes\ApiPaginate;

    public function hydrate()
    {
        return collect(['item3', 'item4']);
    }
}
