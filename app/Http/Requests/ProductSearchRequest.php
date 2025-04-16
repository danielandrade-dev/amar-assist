<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'search' => 'nullable|string',
            'startDate' => 'nullable|date',
            'endDate' => 'nullable|date',
            'page' => 'nullable|integer',
            'perPage' => 'nullable|integer',
        ];
    }

    public function messages()
    {
        return [
            'search.string' => 'O campo de busca deve ser uma string.',
            'startDate.date' => 'A data de início deve ser uma data válida.',
            'endDate.date' => 'A data de término deve ser uma data válida.',
        ];
    }
}
