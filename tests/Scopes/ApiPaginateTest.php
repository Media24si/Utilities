<?php

namespace Tests\Scopes;

use PHPUnit\Framework\TestCase;
use Mockery as m;
use Illuminate\Database\Eloquent\Builder;
use Media24si\Utilities\Pagination\ApiPaginator;

class ApiPaginate extends \Illuminate\Database\Eloquent\Model
{
    use \Media24si\Utilities\Scopes\ApiPaginate;

    public function hydrate()
    {
        return collect(['item3', 'item4']);
    }
}

class ApiPaginateTest extends TestCase
{
    public function setUp()
    {
        $this->model = new ApiPaginate();
        $this->p = new ApiPaginator($array = ['item3', 'item4'], 6, 2, 2);
    }

    public function tearDown()
    {
        unset($this->p);
        unset($this->model);
        m::close();
    }

    /** @test */
    public function scopeShouldReturnCorrectResults()
    {
        $perPage = 2;
        $builder = $this->getBuilder();
        $builder->setModel($this->model);

        \Illuminate\Database\Eloquent\Model::setConnectionResolver($resolver = m::mock('Illuminate\Database\ConnectionResolverInterface'));
        $resolver->shouldReceive('connection')->andReturn($mockConnection = m::mock('stdClass'));

        $builder->getQuery()->shouldReceive('getCountForPagination')->once()->andReturn(2);
        $builder->getQuery()->shouldReceive('forPage')->once()->with(1, 2)->andReturnSelf();
        $builder->getQuery()->shouldReceive('get')->once()->with(['*'])->andReturn(collect(['item3', 'item4']));

        $result = $builder->apiPaginate($perPage);

        $pageInfo = [
            'data' => ['item3', 'item4'],
            'pagination' => [
                'total' => 2,
                'per_page' => 2,
                'current_page' => 1,
                'last_page' => 1,
                'next_page_url' => null,
                'prev_page_url' => null,
                'from' => 1,
                'to' => 2
            ]
        ];
        $this->assertEquals($pageInfo, $result->toArray());
    }

    protected function getBuilder()
    {
        return new Builder($this->getMockQueryBuilder());
    }

    protected function getMockQueryBuilder()
    {
        $query = m::mock(\Illuminate\Database\Query\Builder::class, [
            m::mock('Illuminate\Database\ConnectionInterface'),
            new \Illuminate\Database\Query\Grammars\Grammar,
            m::mock('Illuminate\Database\Query\Processors\Processor'),
        ])->makePartial();

        return $query;
    }
}
