<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AutorRequest extends FormRequest
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
            'Nome' => ['required', 'string', 'max:40'],
        ];
    }

    public function messages(): array
    {
        return [
            'Nome.required' => 'O campo nome é obrigatório.',
            'Nome.string' => 'O campo nome aceita somente texto.',
            'Nome.max' => 'O campo nome suporta até 40 caracteres.',
        ];
    }
}
