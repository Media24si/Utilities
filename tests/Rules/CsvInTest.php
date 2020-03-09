<?php

namespace Tests\Rules;

use Illuminate\Validation\Validator;
use Media24si\Utilities\Rules\CsvIn;
use PHPUnit\Framework\TestCase;

class CsvInTest extends TestCase
{
    /** @test */
    public function passWhenEmptyValue()
    {
        $trans = $this->getIlluminateArrayTranslator();
        $validator = new Validator($trans, ['field' => ''], [
            'field' => new CsvIn(['foo', 'bar']),
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
            'field' => new CsvIn($options),
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
            'field' => new CsvIn($options),
        ]);
        $this->assertFalse($validator->passes());
        $this->assertEquals(['The field contains invalid field'], $validator->errors()->get('field'));
    }

    public function validValues()
    {
        return [
            [['foo', 'bar'], 'foo'],
            [['foo', 'bar'], 'bar'],
            [['foo', 'bar'], 'bar,foo'],
        ];
    }

    public function invalidValues()
    {
        return [
            [['foo', 'bar'], 'john'],
            [['foo', 'bar'], '-john'],
            [['foo', 'bar'], 'foo,john'],
            [['foo', 'bar'], 'john,-foo'],
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
