<?php

namespace Tests\Unit\Requests;

use App\Http\Requests\AutorRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class AutorRequestTest extends TestCase
{
    use RefreshDatabase;

    private AutorRequest $requisicao;

    protected function setUp(): void
    {
        $this->requisicao = new AutorRequest;
        parent::setUp();
    }

    private function dadosValidos(array $sobrescrever = []): array
    {
        return array_merge([
            'Nome' => 'Martin Fowler',
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

    public function test_nome_deve_ser_obrigatorio(): void
    {
        $validador = $this->validar($this->dadosValidos(['Nome' => '']));

        $this->assertFalse($validador->passes());
        $this->assertArrayHasKey('Nome', $validador->errors()->toArray());
        $this->assertEquals('O campo nome é obrigatório.', $validador->errors()->first('Nome'));
    }

    public function test_nome_nao_pode_ter_mais_de_40_caracteres(): void
    {
        $validador = $this->validar($this->dadosValidos([
            'Nome' => str_repeat('A', 41),
        ]));

        $this->assertFalse($validador->passes());
        $this->assertEquals('O campo nome suporta até 40 caracteres.', $validador->errors()->first('Nome'));
    }

    public function test_nome_valido_com_exatamente_40_caracteres(): void
    {
        $validador = $this->validar($this->dadosValidos([
            'Nome' => str_repeat('A', 40),
        ]));

        $this->assertFalse($validador->fails());
    }

    public function test_todos_os_campos_devem_ser_validos(): void
    {
        $validador = $this->validar($this->dadosValidos());

        $this->assertTrue($validador->passes());
        $this->assertEmpty($validador->errors()->toArray());
    }
}
