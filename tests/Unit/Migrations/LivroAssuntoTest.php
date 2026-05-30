<?php

namespace Tests\Unit\Migrations;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class LivroAssuntoTest extends TestCase
{
    use RefreshDatabase;

    public function test_tabela_livro_assunto_existe(): void
    {
        $this->assertTrue(Schema::hasTable('livro_assunto'));
    }

    public function test_tabela_livro_assunto_possui_os_campos_esperados(): void
    {
        $this->assertTrue(Schema::hasColumns('livro_assunto', [
            'Livro_CodL',
            'Assunto_CodAs',
        ]));
    }

    public function test_retorna_os_tipos_de_dados_das_colunas_da_tabela_livro_assunto(): void
    {
        $this->assertEquals('int', Schema::getColumnType('livro_assunto', 'Livro_CodL'));
        $this->assertEquals('int', Schema::getColumnType('livro_assunto', 'Assunto_CodAs'));
    }

    public function test_retornar_erro_ao_criar_livro_assunto_inexistente(): void
    {
        $this->expectException(QueryException::class);

        \DB::table('livro_assunto')->insert([
            'Livro_CodL' => 9999, // não existe
            'Assunto_CodAs' => 9999,
        ]);
    }
}
