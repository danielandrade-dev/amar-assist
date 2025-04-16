<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AllowedHtmlTags implements Rule
{
    /**
     * @var array
     */
    private $allowedTags = ['p', 'br', 'b', 'strong'];

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Verifica se há outras tags além das permitidas
        preg_match_all('/<([^>\s\/]+)[^>]*>/i', $value, $matches);
        if (!empty($matches[1])) {
            foreach ($matches[1] as $tag) {
                if (!in_array(strtolower($tag), $this->allowedTags)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'O campo :attribute só pode conter as tags: p, br, b, strong.';
    }
} 