<?php

namespace App\Http\Requests;

use App\Rules\AllowedHtmlTags;
use App\Rules\ImageOrUrl;
use App\Rules\PriceGreaterThanCost;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => [
                'required',
                'string',
                'max:255',
                new AllowedHtmlTags
            ],
            'price' => ['required', 'numeric', 'min:0', new PriceGreaterThanCost],
            'cost' => 'required|numeric|min:0',
            'status' => 'sometimes|boolean',
            'image' => ['sometimes', new ImageOrUrl]
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O nome é obrigatório',
            'description.required' => 'A descrição é obrigatória',
            'price.required' => 'O preço é obrigatório',
            'cost.required' => 'O custo é obrigatório',
        ];
    }
}
