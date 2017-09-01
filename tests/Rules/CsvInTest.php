<?php

use PHPUnit\Framework\TestCase;

class CsvInTest extends TestCase
{
    /** @test */
    public function passWhenEmptyValue()
    {
        $rule = new \Media24si\Utilities\Rules\CsvIn(['foo', 'bar']);
        $this->assertTrue( $rule->passes('sort', '') );
    }

    /** @test */
    public function passForValidValues()
    {
        $rule = new \Media24si\Utilities\Rules\CsvIn(['foo', 'bar']);

        $this->assertTrue( $rule->passes('sort', 'foo') );
        $this->assertTrue( $rule->passes('sort', 'bar') );
        $this->assertTrue( $rule->passes('sort', 'bar,foo') );
    }

    /** @test */
    public function failForInvalidValues()
    {
        $rule = new \Media24si\Utilities\Rules\CsvIn(['foo', 'bar']);

        $this->assertFalse( $rule->passes('sort', 'john') );
        $this->assertFalse( $rule->passes('sort', 'foo,-john') );
        $this->assertFalse( $rule->passes('sort', 'foo,john') );
    }
}
