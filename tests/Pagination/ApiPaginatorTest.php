<?php

namespace Illuminate\Tests\Pagination;

use PHPUnit\Framework\TestCase;
use Mockery as m;
use Media24si\Utilities\Pagination\ApiPaginator;

class ApiPaginatorTest extends TestCase
{
    public function setUp()
    {
        $this->p = new ApiPaginator($array = ['item3', 'item4'], 6, 2, 2);
    }

    public function tearDown()
    {
        unset($this->p);
        m::close();
    }

    /** @test */
    public function paginatorReturnsRelevantContextInformation()
    {
        $this->assertEquals(2, $this->p->currentPage());
        $this->assertTrue($this->p->hasPages());
        $this->assertTrue($this->p->hasMorePages());
        $this->assertEquals(['item3', 'item4'], $this->p->items());
        $pageInfo = [
            'data' => ['item3', 'item4'],
            'pagination' => [
                'total' => 6,
                'per_page' => 2,
                'current_page' => 2,
                'last_page' => 3,
                'next_page_url' => '/?page=3',
                'prev_page_url' => '/?page=1',
                'from' => 3,
                'to' => 4
            ]
        ];
        $this->assertEquals($pageInfo, $this->p->toArray());
    }

    /** @test */
    public function paginatorCreatesValidPagination()
    {
        $columns = ['*'];
        $perPage = 2;
        $builder = $this->getMockQueryBuilder();

        $builder->shouldReceive('paginate')->once()->andReturn($this->p);

        $results = $this->p->create($builder, $perPage);

        $pageInfo = [
            'data' => ['item3', 'item4'],
            'pagination' => [
                'total' => 6,
                'per_page' => 2,
                'current_page' => 2,
                'last_page' => 3,
                'next_page_url' => '/?page=3',
                'prev_page_url' => '/?page=1',
                'from' => 3,
                'to' => 4
            ]
        ];
        $this->assertEquals($pageInfo, $this->p->toArray());
    }

    /**
     * @return m\MockInterface
     */
    protected function getMockQueryBuilder()
    {
        $builder = m::mock('Illuminate\Database\Query\Builder', [
            m::mock('Illuminate\Database\ConnectionInterface'),
            new \Illuminate\Database\Query\Grammars\Grammar,
            m::mock('Illuminate\Database\Query\Processors\Processor'),
        ])->makePartial();

        return $builder;
    }
}

