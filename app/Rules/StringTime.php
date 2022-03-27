<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class StringTime implements Rule
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
        if($value == "") return true;

        if (!is_string($value) && ! is_numeric($value)) {
            return false;
        }

        $validation = true;
        $explodedParts = explode(' ', $value);

        $a = 0;
        while ($a < count($explodedParts) && $validation){
            $tested= $explodedParts[$a];
            $validation = preg_match('/^(\s?(\d{1,2}(h|m|s)))$/', $tested) > 0;
            $tested =  substr($tested, 0,-1);
            if(!is_numeric($tested)) return false;
            $a++;
        }

        return $validation;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'format : 2h (3m) (2s)';
    }
}
