<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\UploadedFile;

class ImageOrUrl implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Se for um arquivo, verifica se é uma imagem válida
        if ($value instanceof UploadedFile) {
            return in_array($value->getClientMimeType(), [
                'image/jpeg',
                'image/png',
                'image/jpg'
            ]);
        }

        // Se for uma string, verifica se é uma URL válida
        if (is_string($value)) {
            return filter_var($value, FILTER_VALIDATE_URL) !== false;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'O campo :attribute deve ser uma imagem válida (JPG, PNG) ou uma URL.';
    }
} 