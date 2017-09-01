<?php

use PHPUnit\Framework\TestCase;

class SorterTest extends TestCase
{
    /** @test */
    public function passWhenEmptyValue()
    {
        $rule = new \Media24si\Utilities\Rules\Sorter(['foo', 'bar']);
        $this->assertTrue( $rule->passes('sort', '') );
    }

    /** @test */
    public function passForValidValues()
    {
        $rule = new \Media24si\Utilities\Rules\Sorter(['foo', 'bar']);

        $this->assertTrue( $rule->passes('sort', 'foo') );
        $this->assertTrue( $rule->passes('sort', '-foo') );
        $this->assertTrue( $rule->passes('sort', 'bar,foo') );
        $this->assertTrue( $rule->passes('sort', 'bar,-foo') );
        $this->assertTrue( $rule->passes('sort', '-bar,-foo') );
        $this->assertTrue( $rule->passes('sort', '-foo,bar') );
    }

    /** @test */
    public function failForInvalidValues()
    {
        $rule = new \Media24si\Utilities\Rules\Sorter(['foo', 'bar']);

        $this->assertFalse( $rule->passes('sort', 'john') );
        $this->assertFalse( $rule->passes('sort', '-john') );
        $this->assertFalse( $rule->passes('sort', 'foo,john') );
        $this->assertFalse( $rule->passes('sort', 'john,foo') );
    }
}
