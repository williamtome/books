<?php

namespace Tests\Unit\Requests;

use App\Http\Requests\LivroRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class LivroRequestTest extends TestCase
{
    use RefreshDatabase;

    private LivroRequest $requisicao;

    protected function setUp(): void
    {
        $this->requisicao = new LivroRequest;
        parent::setUp();
    }

    private function dadosValidos(array $sobrescrever = []): array
    {
        return array_merge([
            'Titulo' => 'Dom Casmurro',
            'Editora' => 'Editora Ática',
            'Edicao' => 1,
            'AnoPublicacao' => '2001',
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

    // Testes do campo título
    public function test_titulo_deve_ser_obrigatorio(): void
    {
        $validador = $this->validar($this->dadosValidos(['Titulo' => '']));

        $this->assertFalse($validador->passes());
        $this->assertArrayHasKey('Titulo', $validador->errors()->toArray());
        $this->assertEquals('O campo título é obrigatório.', $validador->errors()->first('Titulo'));
    }

    public function test_titulo_nao_pode_ter_mais_de_40_caracteres(): void
    {
        $validador = $this->validar($this->dadosValidos([
            'Titulo' => str_repeat('A', 41),
        ]));

        $this->assertFalse($validador->passes());
        $this->assertEquals('O campo título suporta até 40 caracteres.', $validador->errors()->first('Titulo'));
    }

    public function test_titulo_valido_com_exatamente_40_caracteres(): void
    {
        $validador = $this->validar($this->dadosValidos([
            'Titulo' => str_repeat('A', 40),
        ]));

        $this->assertFalse($validador->fails());
    }

    // Testes do campo Editora
    public function test_editora_deve_ser_obrigatoria(): void
    {
        $validador = $this->validar($this->dadosValidos(['Editora' => '']));

        $this->assertFalse($validador->passes());
        $this->assertArrayHasKey('Editora', $validador->errors()->toArray());
        $this->assertEquals('O campo editora é obrigatório.', $validador->errors()->first('Editora'));
    }

    public function test_editora_nao_pode_ter_mais_de_40_caracteres(): void
    {
        $validador = $this->validar($this->dadosValidos([
            'Editora' => str_repeat('B', 41),
        ]));

        $this->assertFalse($validador->passes());
        $this->assertEquals('O campo editora suporta até 40 caracteres.', $validador->errors()->first('Editora'));
    }

    public function test_editora_valida_com_exatamente_40_caracteres(): void
    {
        $validador = $this->validar($this->dadosValidos([
            'Editora' => str_repeat('B', 40),
        ]));

        $this->assertFalse($validador->fails());
    }

    // Testes do campo Edicao
    public function test_edicao_deve_ser_obrigatorio(): void
    {
        $validador = $this->validar($this->dadosValidos(['Edicao' => '']));

        $this->assertFalse($validador->passes());
        $this->assertArrayHasKey('Edicao', $validador->errors()->toArray());
        $this->assertEquals('O campo edição é obrigatório.', $validador->errors()->first('Edicao'));
    }

    public function test_edicao_nao_pode_ser_texto(): void
    {
        $validador = $this->validar($this->dadosValidos(['Edicao' => 'primeira']));

        $this->assertFalse($validador->passes());
        $this->assertEquals('O campo edição aceita somente números.', $validador->errors()->first('Edicao'));
    }

    public function test_edicao_nao_pode_ser_zero(): void
    {
        $validador = $this->validar($this->dadosValidos(['Edicao' => 0]));

        $this->assertFalse($validador->passes());
        $this->assertEquals('O campo edição deve ser maior ou igual a 1 (um).', $validador->errors()->first('Edicao'));
    }

    public function test_edicao_nao_pode_ser_negativa(): void
    {
        $validador = $this->validar($this->dadosValidos(['Edicao' => -1]));

        $this->assertFalse($validador->passes());
        $this->assertEquals('O campo edição deve ser maior ou igual a 1 (um).', $validador->errors()->first('Edicao'));
    }

    public function test_edicao_valida(): void
    {
        $validador = $this->validar($this->dadosValidos(['Edicao' => 1]));

        $this->assertFalse($validador->fails());
    }

    // Testes do campo AnoPublicacao
    public function test_ano_publicacao_deve_ser_obrigatorio(): void
    {
        $validador = $this->validar($this->dadosValidos(['AnoPublicacao' => '']));

        $this->assertFalse($validador->passes());
        $this->assertArrayHasKey('AnoPublicacao', $validador->errors()->toArray());
        $this->assertEquals('O campo ano de publicação é obrigatório.', $validador->errors()->first('AnoPublicacao'));
    }

    public function test_ano_publicacao_nao_pode_ter_menos_de_4_digitos(): void
    {
        $validador = $this->validar($this->dadosValidos(['AnoPublicacao' => '999']));

        $this->assertFalse($validador->passes());
        $this->assertEquals('O campo ano de publicação deve ter exatamente 4 dígitos.', $validador->errors()->first('AnoPublicacao'));
    }

    public function test_ano_publicacao_nao_pode_ter_mais_de_4_digitos(): void
    {
        $validador = $this->validar($this->dadosValidos(['AnoPublicacao' => '20261']));

        $this->assertFalse($validador->passes());
        $this->assertEquals('O campo ano de publicação deve ter exatamente 4 dígitos.', $validador->errors()->first('AnoPublicacao'));
    }

    public function test_ano_publicacao_nao_pode_ser_negativo(): void
    {
        $validador = $this->validar($this->dadosValidos(['AnoPublicacao' => '-999']));

        $this->assertFalse($validador->passes());
        $this->assertEquals('O campo ano de publicação deve ter exatamente 4 dígitos.', $validador->errors()->first('AnoPublicacao'));
    }

    public function test_ano_publicacao_nao_pode_ser_texto(): void
    {
        $validador = $this->validar($this->dadosValidos(['AnoPublicacao' => 'abcd']));

        $this->assertFalse($validador->passes());
        $this->assertEquals('O campo ano de publicação aceita somente números.', $validador->errors()->first('AnoPublicacao'));
    }

    public function test_ano_publicacao_nao_pode_ser_menor_que_1000(): void
    {
        $validador = $this->validar($this->dadosValidos(['AnoPublicacao' => '0999']));

        $this->assertFalse($validador->passes());
        $this->assertEquals(
            'O campo ano de publicação deve ser maior que 1000.',
            $validador->errors()->first('AnoPublicacao')
        );
    }

    public function test_ano_publicacao_nao_pode_ser_maior_que_ano_atual(): void
    {
        $anoFuturo = (int) date('Y') + 1;

        $validador = $this->validar($this->dadosValidos([
            'AnoPublicacao' => (string) $anoFuturo,
        ]));

        $this->assertFalse($validador->passes());
        $this->assertEquals(
            'O campo ano de publicação não pode ser maior que '.date('Y').'.',
            $validador->errors()->first('AnoPublicacao')
        );
    }

    public function test_ano_publicacao_valido(): void
    {
        $validador = $this->validar($this->dadosValidos(['AnoPublicacao' => '2020']));

        $this->assertFalse($validador->fails());
    }

    public function test_ano_publicacao_valido_com_ano_atual(): void
    {
        $validador = $this->validar($this->dadosValidos([
            'AnoPublicacao' => date('Y'),
        ]));

        $this->assertFalse($validador->fails());
    }

    // Teste geral com todos os campos válidos
    public function test_todos_os_campos_devem_ser_validos(): void
    {
        $validador = $this->validar($this->dadosValidos());

        $this->assertTrue($validador->passes());
        $this->assertEmpty($validador->errors()->toArray());
    }
}
