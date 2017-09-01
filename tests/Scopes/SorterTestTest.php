<?php

use PHPUnit\Framework\TestCase;
use Mockery as m;
use Illuminate\Database\Query\Builder;

class Sorter {
    use \Media24si\Utilities\Scopes\Sorter;
}

class SorterTestTest extends TestCase
{
    /**
     * @var Sorter
     */
    private $sorter;

    protected function setUp()
    {
        parent::setUp();
        $this->sorter = new Sorter();
    }

    /** @test */
    public function doNotAddOrderForEmptyValue()
    {
        $qb = $this->getMySqlBuilder()->from('john');
        $this->sorter->scopeSorter($qb, '');

        $this->assertEquals(null, $qb->orders);
    }

    /** @test */
    public function addCorrectOrder()
    {
        $qb = $this->getMySqlBuilder()->from('john');

        $this->assertEquals(
            [['column' => 'foo', 'direction' => 'asc']],
            $this->sorter->scopeSorter(clone $qb, 'foo')->orders
        );

        $this->assertEquals(
            [['column' => 'foo', 'direction' => 'desc']],
            $this->sorter->scopeSorter(clone $qb, '-foo')->orders
        );

        $this->assertEquals(
            [
                ['column' => 'foo', 'direction' => 'asc'],
                ['column' => 'bar', 'direction' => 'desc'],
            ],
            $this->sorter->scopeSorter(clone $qb, 'foo,-bar')->orders
        );
    }

    protected function getMySqlBuilder()
    {
        $grammar = new \Illuminate\Database\Query\Grammars\MySqlGrammar;
        $processor = m::mock('Illuminate\Database\Query\Processors\Processor');
        return new Builder(m::mock('Illuminate\Database\ConnectionInterface'), $grammar, $processor);
    }
    
}
