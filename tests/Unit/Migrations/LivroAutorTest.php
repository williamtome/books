<?php

namespace Tests\Unit\Migrations;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class LivroAutorTest extends TestCase
{
    use RefreshDatabase;

    public function test_tabela_livro_autor_existe(): void
    {
        $this->assertTrue(Schema::hasTable('livro_autor'));
    }

    public function test_tabela_livro_autor_possui_os_campos_esperados(): void
    {
        $this->assertTrue(Schema::hasColumns('livro_autor', [
            'Livro_CodL',
            'Autor_CodAu',
        ]));
    }

    public function test_retorna_os_tipos_de_dados_das_colunas_da_tabela_livro_autor(): void
    {
        $this->assertEquals('int', Schema::getColumnType('livro_autor', 'Livro_CodL'));
        $this->assertEquals('int', Schema::getColumnType('livro_autor', 'Autor_CodAu'));
    }

    public function test_retornar_erro_ao_criar_livro_autor_inexistente(): void
    {
        $this->expectException(QueryException::class);

        \DB::table('livro_autor')->insert([
            'Livro_CodL'   => 9999, // não existe
            'Autor_CodAu'  => 9999,
        ]);
    }
}
