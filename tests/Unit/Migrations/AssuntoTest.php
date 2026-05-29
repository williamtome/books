<?php

namespace Tests\Unit\Migrations;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AssuntoTest extends TestCase
{
    use RefreshDatabase;

    public function test_existe_tabela_de_assuntos(): void
    {
        $this->assertTrue(Schema::hasTable('assuntos'));
    }

    public function test_tabela_de_assuntos_possui_os_campos_esperados()
    {
        $this->assertTrue(Schema::hasColumns('assuntos', [
            'CodAs',
            'Descricao',
        ]));
    }

    public function test_retorna_os_tipos_de_dados_das_colunas_da_tabela_assuntos(): void
    {
        $this->assertEquals('int', Schema::getColumnType('assuntos', 'CodAs'));
        $this->assertEquals('varchar',  Schema::getColumnType('assuntos', 'Descricao'));
    }
}
