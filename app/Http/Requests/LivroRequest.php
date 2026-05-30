<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LivroRequest extends FormRequest
{
    protected string $anoAtual;

    public function authorize(): bool
    {
        return true;
    }

    public function getAnoAtual()
    {
        $this->anoAtual = date('Y');
        return $this->anoAtual;
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
            'AnoPublicacao' => ['required', 'numeric', 'digits:4', 'min:1000', 'max:' . $this->getAnoAtual()],
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
            'Edicao.required' => 'O campo edição é obrigatório.',
            'Edicao.numeric' => 'O campo edição aceita somente números.',
            'Edicao.min' => 'O campo edição números acima de 1 (um).',
            'AnoPublicacao.required' => 'O campo ano de publicação é obrigatório.',
            'AnoPublicacao.numeric' => 'O campo ano de publicação aceita somente números.',
            'AnoPublicacao.max' => 'O campo ano de publicação suporta até 4 caracteres.',
            'AnoPublicacao.digits'   => 'O campo ano de publicação deve ter exatamente 4 dígitos.',
        ];
    }
}
