<?php

namespace Media24si\Utilities\Rules;

use Illuminate\Contracts\Validation\Rule;

class Sorter implements Rule
{
    /**
     * @var array
     */
    private $options;

    /**
     * Sorter constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }


    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return !$value ? true : collect(explode(',', $value))->transform(function ($item) {
                return ltrim($item, '-');
        })->diff($this->options)->count() === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute contains invalid field';
    }
}
