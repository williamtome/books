<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AssuntoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'Descricao' => ['required', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'Descricao.required' => 'O campo descrição é obrigatório.',
            'Descricao.string' => 'O campo descrição aceita somente texto.',
            'Descricao.max' => 'O campo descrição suporta até 20 caracteres.',
        ];
    }
}
