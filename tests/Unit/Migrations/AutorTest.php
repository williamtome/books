<?php

namespace Tests\Unit\Migrations;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AutorTest extends TestCase
{
    use RefreshDatabase;

    public function test_existe_tabela_de_autores(): void
    {
        $this->assertTrue(Schema::hasTable('autores'));
    }

    public function test_tabela_de_autores_possui_os_campos_esperados()
    {
        $this->assertTrue(Schema::hasColumns('autores', [
            'CodAu',
            'Nome',
        ]));
    }

    public function test_retorna_os_tipos_de_dados_das_colunas_da_tabela_autores(): void
    {
        $this->assertEquals('int', Schema::getColumnType('autores', 'CodAu'));
        $this->assertEquals('varchar', Schema::getColumnType('autores', 'Nome'));
    }
}
