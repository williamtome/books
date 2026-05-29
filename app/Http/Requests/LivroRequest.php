<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LivroRequest extends FormRequest
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
            'Titulo' => ['required', 'string', 'max:40'],
            'Editora' => ['required', 'string', 'max:40'],
            'Edicao' => ['required', 'numeric', 'min:1'],
            'AnoPublicacao' => ['required', 'string', 'max:4'],
        ];
    }

    public function messages(): array
    {
        return [
            'Titulo.required' => 'O campo título é obrigatório.',
            'Titulo.string' => 'O campo título aceita somente texto.',
            'Titulo.max' => 'O campo título suporta até 40 caracteres.',
            'Editora.required' => 'O campo editora é obrigatório.',
            'Editora.string' => 'O campo editora aceita somente texto.',
            'Editora.max' => 'O campo editora suporta até 40 caracteres.',
            'AnoPublicacao.required' => 'O campo ano de publicação é obrigatório.',
            'AnoPublicacao.string' => 'O campo ano de publicação aceita somente texto.',
            'AnoPublicacao.max' => 'O campo ano de publicação suporta até 40 caracteres.',
            'Edicao.required' => 'O campo edição é obrigatório.',
            'Edicao.string' => 'O campo edição aceita somente números.',
            'Edicao.min' => 'O campo edição números acima de 1 (um).',
        ];
    }
}
