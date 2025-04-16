<?php

namespace App\Http\Requests;

use App\Rules\AllowedHtmlTags;
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
            'price' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'status' => 'sometimes|boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O nome é obrigatório',
            'description.required' => 'A descrição é obrigatória',
            'price.required' => 'O preço é obrigatório',
            'cost.required' => 'O custo é obrigatório',
            'image.image' => 'O arquivo deve ser uma imagem',
            'image.mimes' => 'Apenas imagens JPG e PNG são permitidas',
            'image.max' => 'A imagem não pode ser maior que 2MB'
        ];
    }
}
