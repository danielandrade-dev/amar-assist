<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PriceGreaterThanCost implements Rule
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
        $cost = request()->input('cost');
        if (!is_numeric($cost)) {
            return false;
        }

        // Preço deve ser maior que o custo + 10%
        return $value > ($cost * 1.10);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'O preço de venda deve ser pelo menos 10% maior que o custo.';
    }
} 