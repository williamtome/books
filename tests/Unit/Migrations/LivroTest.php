<?php

namespace Tests\Unit\Migrations;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class LivroTest extends TestCase
{
    use RefreshDatabase;

    public function test_existe_tabela_de_livros(): void
    {
        $this->assertTrue(Schema::hasTable('livros'));
    }

    public function test_tabela_de_livros_tem_os_campos_esperados()
    {
        $this->assertTrue(Schema::hasColumns('livros', [
            'CodL',
            'Titulo',
            'Editora',
            'Edicao',
            'AnoPublicacao',
        ]));
    }

    public function test_retorna_os_tipos_de_dados_das_colunas_da_tabela_livros(): void
    {
        $this->assertEquals('int', Schema::getColumnType('livros', 'CodL'));
        $this->assertEquals('varchar', Schema::getColumnType('livros', 'Titulo'));
        $this->assertEquals('varchar', Schema::getColumnType('livros', 'Editora'));
        $this->assertEquals('int', Schema::getColumnType('livros', 'Edicao'));
        $this->assertEquals('varchar', Schema::getColumnType('livros', 'AnoPublicacao'));
    }
}
