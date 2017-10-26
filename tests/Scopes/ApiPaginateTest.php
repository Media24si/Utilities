<?php

namespace Tests\Scopes;

use PHPUnit\Framework\TestCase;
use Mockery as m;
use Illuminate\Database\Eloquent\Builder;
use Media24si\Utilities\Pagination\ApiPaginator;

class ApiPaginate extends \Illuminate\Database\Eloquent\Model
{
    use \Media24si\Utilities\Scopes\ApiPaginate;
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

        $builder->getQuery()->shouldReceive('getCountForPagination')->once()->andReturn(2);
        $builder->getQuery()->shouldReceive('forPage')->once()->with(1, 2)->andReturnSelf();
        $builder->getQuery()->shouldReceive('get')->once()->with(['*'])->andReturn(collect(['item3', 'item4']));
        $builder->getQuery()->shouldReceive('paginate')->once()->andReturn($this->p);

        $result = $builder->apiPaginate($perPage);

        $this->assertEquals($builder, $result);
    }

    protected function getBuilder()
    {
        return new Builder($this->getMockQueryBuilder());
        // $builder = m::mock('Illuminate\Database\Eloquent\Builder', [$this->getMockQueryBuilder()]);
        // $builder->shouldReceive('setModel')->andReturn($this->model);
        // return $builder;
    }

    protected function getMockQueryBuilder()
    {
        // $query = m::mock(\Illuminate\Database\Query\Builder::class, [
        //     m::mock('Illuminate\Database\ConnectionInterface'),
        //     new \Illuminate\Database\Query\Grammars\Grammar,
        //     m::mock('Illuminate\Database\Query\Processors\Processor'),
        // ]);
        // $query->shouldReceive('from');
        $query = m::mock(\Illuminate\Database\Query\Builder::class);
        $query->shouldReceive('from');

        return $query;
    }
}
