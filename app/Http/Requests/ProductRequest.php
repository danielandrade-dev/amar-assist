<?php

namespace App\Http\Requests;

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
            'description' => ['required', 'string', 'max:255', 'regex:/^(?:<p>.*?<\/p>|\s*<br\s*\/?>|\s*<b>.*?<\/b>|\s*<strong>.*?<\/strong>|\s*[^<>])$/i'],
            'price' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'status' => 'sometimes|boolean',
            'images' => 'array',
            'images.*' => 'image|mimes:jpg,png',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O nome é obrigatório',
            'description.required' => 'A descrição é obrigatória',
            'description.regex' => 'A descrição só pode conter as tags HTML: p, br, b e strong',
            'price.required' => 'O preço é obrigatório',
            'cost.required' => 'O custo é obrigatório',
            'images.*.image' => 'O arquivo deve ser uma imagem',
            'images.*.mimes' => 'Apenas imagens JPG e PNG são permitidas'
        ];
    }
}
