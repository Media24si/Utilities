<?php

namespace Tests\Rules;

use Illuminate\Validation\Validator;
use Media24si\Utilities\Rules\Sorter;
use PHPUnit\Framework\TestCase;

class SorterTest extends TestCase
{
    /** @test */
    public function passWhenEmptyValue()
    {
        $trans = $this->getIlluminateArrayTranslator();
        $validator = new Validator($trans, ['field' => ''], [
            'field' => new Sorter(['foo', 'bar']),
        ]);
        $this->assertTrue($validator->passes());
    }

    /**
     * @test
     * @dataProvider validValues
     */
    public function passForValidValues($options, $validValue)
    {
        $trans = $this->getIlluminateArrayTranslator();
        $validator = new Validator($trans, ['field' => $validValue], [
            'field' => new Sorter($options),
        ]);
        $this->assertTrue($validator->passes());
    }

    /**
     * @test
     * @dataProvider invalidValues
     */
    public function failForInvalidValues($options, $invalidValue)
    {
        $trans = $this->getIlluminateArrayTranslator();
        $validator = new Validator($trans, ['field' => $invalidValue], [
            'field' => new Sorter($options),
        ]);
        $this->assertFalse($validator->passes());
        $this->assertEquals(['The field contains invalid field'], $validator->errors()->get('field'));
    }

    public function validValues()
    {
        return [
            [['foo', 'bar'], 'foo'],
            [['foo', 'bar'], '-foo'],
            [['foo', 'bar'], 'bar,foo'],
            [['foo', 'bar'], 'bar,-foo'],
            [['foo', 'bar'], '-bar,foo'],
            [['foo', 'bar'], '-bar,-foo'],
        ];
    }

    public function invalidValues()
    {
        return [
            [['foo', 'bar'], 'john'],
            [['foo', 'bar'], '-john'],
            [['foo', 'bar'], 'foo,john'],
            [['foo', 'bar'], 'john,foo'],
        ];
    }

    public function getIlluminateArrayTranslator()
    {
        return new \Illuminate\Translation\Translator(
            new \Illuminate\Translation\ArrayLoader(),
            'en'
        );
    }
}
