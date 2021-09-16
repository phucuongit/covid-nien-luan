<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Is_identity implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return strlen((string)$value) == 9 
            || strlen((string)$value) == 12;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Identity'length equal 9 or 12";
    }
}
