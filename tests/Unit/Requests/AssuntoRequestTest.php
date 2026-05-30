<?php

namespace Tests\Unit\Requests;

use App\Http\Requests\AssuntoRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class AssuntoRequestTest extends TestCase
{
    use RefreshDatabase;

    private AssuntoRequest $requisicao;

    protected function setUp(): void
    {
        $this->requisicao = new AssuntoRequest;
        parent::setUp();
    }

    private function dadosValidos(array $sobrescrever = []): array
    {
        return array_merge([
            'Descricao' => 'Tecnologia',
        ], $sobrescrever);
    }

    private function validar(array $dados): \Illuminate\Validation\Validator
    {
        return Validator::make($dados, $this->requisicao->rules(), $this->requisicao->messages());
    }

    public function test_requisicao_esta_autorizada(): void
    {
        $this->assertTrue($this->requisicao->authorize());
    }

    public function test_descricao_deve_ser_obrigatorio(): void
    {
        $validador = $this->validar($this->dadosValidos(['Descricao' => '']));

        $this->assertFalse($validador->passes());
        $this->assertArrayHasKey('Descricao', $validador->errors()->toArray());
        $this->assertEquals('O campo descrição é obrigatório.', $validador->errors()->first('Descricao'));
    }

    public function test_descricao_nao_pode_ter_mais_de_20_caracteres(): void
    {
        $validador = $this->validar($this->dadosValidos([
            'Descricao' => str_repeat('A', 21),
        ]));

        $this->assertFalse($validador->passes());
        $this->assertEquals('O campo descrição suporta até 20 caracteres.', $validador->errors()->first('Descricao'));
    }

    public function test_descricao_valido_com_exatamente_20_caracteres(): void
    {
        $validador = $this->validar($this->dadosValidos([
            'Descricao' => str_repeat('A', 20),
        ]));

        $this->assertFalse($validador->fails());
    }
}
