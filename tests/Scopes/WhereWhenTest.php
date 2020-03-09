<?php

namespace Tests\Scopes;

use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use Mockery as m;
use Illuminate\Database\Eloquent\Builder;
use Tests\Stubs\WhereWhen;

class WhereWhenTest extends TestCase
{
    public function tearDown(): void
    {
        m::close();
    }

    /** @test */
    public function dontSetWhenFieldIsMissing()
    {
        app()->bind('request', function () {
            return new Request();
        });

        $builder = $this->getBuilder();
        $builder->getQuery()->shouldReceive('from');
        $builder->getQuery()->shouldNotReceive('where');
        $builder->setModel($model = new WhereWhen());
        $result = $builder->whereWhen('foo');

        $this->assertEquals($builder, $result);
    }

    /** @test */
    public function setCorrectWhereForSingleField()
    {
        app()->bind('request', function () {
            return new Request(['foo' => 'bar']);
        });

        $builder = $this->getBuilder();
        $builder->getQuery()->shouldReceive('from');
        $builder->getQuery()->shouldReceive('where')->once()->with('foo', '=', 'bar');

        $builder->setModel($model = new WhereWhen());
        $result = $builder->whereWhen('foo');

        $this->assertEquals($builder, $result);
    }

    /** @test */
    public function setCorrectWhereForColumnAndKey()
    {
        app()->bind('request', function () {
            return new Request(['john' => 'bar']);
        });

        $builder = $this->getBuilder();
        $builder->getQuery()->shouldReceive('from');
        $builder->getQuery()->shouldReceive('where')->once()->with('foo', '=', 'bar');

        $builder->setModel($model = new WhereWhen());
        $result = $builder->whereWhen('foo', 'john');

        $this->assertEquals($builder, $result);
    }

    /** @test */
    public function setCorrectWhereForColumnAndKeyAndOperation()
    {
        app()->bind('request', function () {
            return new Request(['john' => 'bar']);
        });

        $builder = $this->getBuilder();
        $builder->getQuery()->shouldReceive('from');
        $builder->getQuery()->shouldReceive('where')->once()->with('foo', '<', 'bar');

        $builder->setModel($model = new WhereWhen());
        $result = $builder->whereWhen('foo', 'john', '<');

        $this->assertEquals($builder, $result);
    }

    /** @test */
    public function setCorrectWhereFromCustomRequestObject()
    {
        app()->bind('request', function () {
            return new Request();
        });

        $builder = $this->getBuilder();
        $builder->getQuery()->shouldReceive('from');
        $builder->getQuery()->shouldReceive('where')->once()->with('foo', '=', 'bar');

        $builder->setModel($model = new WhereWhen());
        $result = $builder->whereWhen('foo', 'john', null, new Request(['john' => 'bar']));

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
