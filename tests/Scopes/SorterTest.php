<?php

namespace Tests\Scopes;

use PHPUnit\Framework\TestCase;
use Mockery as m;
use Illuminate\Database\Eloquent\Builder;

class Sorter extends \Illuminate\Database\Eloquent\Model
{
    use \Media24si\Utilities\Scopes\Sorter;
}

class SorterTest extends TestCase
{
    public function tearDown()
    {
        m::close();
    }

    /** @test */
    public function doNotAddOrderForEmptyValue()
    {
        $builder = $this->getBuilder();
        $builder->getQuery()->shouldReceive('from');
        $builder->getQuery()->shouldNotReceive('orderBy');
        $builder->setModel($model = new Sorter());
        $result = $builder->sorter('');

        $this->assertEquals($builder, $result);
    }

    /** @test */
    public function addCorrectOrderForOneAscField()
    {
        $builder = $this->getBuilder();
        $builder->getQuery()->shouldReceive('from');
        $builder->getQuery()->shouldReceive('orderBy')->once()->with('foo', 'asc');
        $builder->setModel($model = new Sorter());
        $result = $builder->sorter('foo');

        $this->assertEquals($builder, $result);
    }

    /** @test */
    public function addCorrectOrderForOneDescField()
    {
        $builder = $this->getBuilder();
        $builder->getQuery()->shouldReceive('from');
        $builder->getQuery()->shouldReceive('orderBy')->once()->with('foo', 'desc');
        $builder->setModel($model = new Sorter());
        $result = $builder->sorter('-foo');

        $this->assertEquals($builder, $result);
    }

    /** @test */
    public function addCorrectOrderForMoreFields()
    {
        $builder = $this->getBuilder();
        $builder->getQuery()->shouldReceive('from');
        $builder->getQuery()->shouldReceive('orderBy')->once()->with('foo', 'asc');
        $builder->getQuery()->shouldReceive('orderBy')->once()->with('bar', 'desc');
        $builder->getQuery()->shouldReceive('orderBy')->once()->with('john', 'desc');
        $builder->setModel($model = new Sorter());
        $result = $builder->sorter('foo,-bar,-john');

        $this->assertEquals($builder, $result);
    }

    protected function getBuilder()
    {
        return new Builder($this->getMockQueryBuilder());
    }

    protected function getMockQueryBuilder()
    {
        $query = m::mock(\Illuminate\Database\Query\Builder::class);
        $query->shouldReceive('from')->with('foo_table');

        return $query;
    }
}
