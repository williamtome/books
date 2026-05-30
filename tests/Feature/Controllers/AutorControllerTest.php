<?php

namespace Tests\Feature\Controllers;

use App\Models\Autor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutorControllerTest extends TestCase
{
    use RefreshDatabase;

    // Métodos auxiliares
    private function dadosValidos(array $sobrescrever = []): array
    {
        return array_merge([
            'Nome' => 'Robert C. Martin',
        ], $sobrescrever);
    }

    private function criarAutor(array $atributos = []): Autor
    {
        return Autor::create($this->dadosValidos($atributos));
    }

    // Testes do método index (listagem)
    public function test_pagina_de_listagem_retorna_status_200(): void
    {
        $resposta = $this->get(route('autores.index'));

        $resposta->assertStatus(200);
    }

    public function test_pagina_de_listagem_exibe_a_view_correta(): void
    {
        $resposta = $this->get(route('autores.index'));

        $resposta->assertViewIs('autores.index');
    }

    public function test_pagina_de_listagem_passa_variavel_autores_para_a_view(): void
    {
        $resposta = $this->get(route('autores.index'));

        $resposta->assertViewHas('autores');
    }

    public function test_pagina_de_listagem_exibe_autores_cadastrados(): void
    {
        $this->criarAutor(['Nome' => 'Martin Fowler']);
        $this->criarAutor(['Nome' => 'Eric Evans']);

        $resposta = $this->get(route('autores.index'));

        $resposta->assertSee('Martin Fowler');
        $resposta->assertSee('Eric Evans');
    }

    public function test_pagina_de_listagem_sem_autores_cadastrados(): void
    {
        $resposta = $this->get(route('autores.index'));

        $resposta->assertViewHas('autores', function ($autores) {
            return $autores->isEmpty();
        });
    }

    // Testes do método create (formulário de cadastro)
    public function test_pagina_de_cadastro_retorna_status_200(): void
    {
        $resposta = $this->get(route('autores.cadastro'));

        $resposta->assertStatus(200);
    }

    public function test_pagina_de_cadastro_exibe_a_view_correta(): void
    {
        $resposta = $this->get(route('autores.cadastro'));

        $resposta->assertViewIs('autores.cadastro');
    }

    // Testes do método store (salvar cadastro)
    public function test_cadastro_salva_autor_no_banco_de_dados(): void
    {
        $this->post(route('autores.cadastrar'), $this->dadosValidos());

        $this->assertDatabaseHas('autores', [
            'Nome' => 'Robert C. Martin',
        ]);
    }

    public function test_cadastro_redireciona_para_listagem_apos_salvar(): void
    {
        $resposta = $this->post(route('autores.cadastrar'), $this->dadosValidos());

        $resposta->assertRedirect(route('autores.index'));
    }

    public function test_cadastro_exibe_mensagem_de_sucesso_apos_salvar(): void
    {
        $resposta = $this->post(route('autores.cadastrar'), $this->dadosValidos());

        $resposta->assertSessionHas('sucesso', 'Autor cadastrado com sucesso!');
    }

    public function test_cadastro_falha_quando_nome_nao_informado(): void
    {
        $resposta = $this->post(route('autores.cadastrar'), $this->dadosValidos([
            'Nome' => '',
        ]));

        $resposta->assertSessionHasErrors('Nome');
        $this->assertDatabaseMissing('autores', ['Nome' => 'Robert C. Martin']);
    }

    public function test_cadastro_falha_quando_nome_possui_mais_de_40_caracteres(): void
    {
        $resposta = $this->post(route('autores.cadastrar'), $this->dadosValidos([
            'Nome' => str_repeat('A', 41),
        ]));

        $resposta->assertSessionHasErrors('Nome');
        $this->assertDatabaseMissing('autores', ['Nome' => 'Robert C. Martin']);
    }

    // Testes do método edit (formulário de edição)
    public function test_pagina_de_edicao_retorna_status_200(): void
    {
        $autor = $this->criarAutor();

        $resposta = $this->get(route('autores.editar', $autor->CodAu));

        $resposta->assertStatus(200);
    }

    public function test_pagina_de_edicao_exibe_a_view_correta(): void
    {
        $autor = $this->criarAutor();

        $resposta = $this->get(route('autores.editar', $autor->CodAu));

        $resposta->assertViewIs('autores.edicao');
    }

    public function test_pagina_de_edicao_retorna_404_para_autor_inexistente(): void
    {
        $resposta = $this->get(route('autores.editar', 99999));

        $resposta->assertStatus(404);
    }

    // Testes do método update (salvar edição)
    public function test_atualizacao_salva_dados_no_banco_de_dados(): void
    {
        $autor = $this->criarAutor();

        $this->put(route('autores.atualizar', $autor->CodAu), $this->dadosValidos([
            'Nome' => 'Nome Atualizado',
        ]));

        $this->assertDatabaseHas('autores', [
            'CodAu' => $autor->CodAu,
            'Nome' => 'Nome Atualizado',
        ]);
    }

    public function test_atualizacao_redireciona_para_listagem_apos_salvar(): void
    {
        $autor = $this->criarAutor();

        $resposta = $this->put(route('autores.atualizar', $autor->CodAu), $this->dadosValidos());

        $resposta->assertRedirect(route('autores.index'));
    }

    public function test_atualizacao_exibe_mensagem_de_sucesso_apos_salvar(): void
    {
        $autor = $this->criarAutor();

        $resposta = $this->put(route('autores.atualizar', $autor->CodAu), $this->dadosValidos());

        $resposta->assertSessionHas('sucesso', 'Autor atualizado com sucesso!');
    }

    public function test_atualizacao_retorna_404_para_autor_inexistente(): void
    {
        $resposta = $this->put(route('autores.atualizar', 99999), $this->dadosValidos());

        $resposta->assertStatus(404);
    }

    public function test_atualizacao_falha_quando_nome_nao_informado(): void
    {
        $autor = $this->criarAutor();

        $resposta = $this->put(route('autores.atualizar', $autor->CodAu), $this->dadosValidos([
            'Nome' => '',
        ]));

        $resposta->assertSessionHasErrors('Nome');
        $this->assertDatabaseHas('autores', ['Nome' => 'Robert C. Martin']);
    }

    public function test_atualizacao_falha_quando_nome_possui_mais_de_40_caracteres(): void
    {
        $autor = $this->criarAutor();

        $resposta = $this->put(route('autores.atualizar', $autor->CodAu), $this->dadosValidos([
            'Nome' => str_repeat('B', 41),
        ]));

        $resposta->assertSessionHasErrors('Nome');
        $this->assertDatabaseHas('autores', ['Nome' => 'Robert C. Martin']);
    }

    // Testes do método destroy (exclusão)
    public function test_exclusao_remove_autor_do_banco_de_dados(): void
    {
        $autor = $this->criarAutor();

        $this->delete(route('autores.excluir', $autor->CodAu));

        $this->assertDatabaseMissing('autores', ['CodAu' => $autor->CodAu]);
    }

    public function test_exclusao_redireciona_para_listagem_apos_deletar(): void
    {
        $autor = $this->criarAutor();

        $resposta = $this->delete(route('autores.excluir', $autor->CodAu));

        $resposta->assertRedirect(route('autores.index'));
    }

    public function test_exclusao_retorna_404_para_autor_inexistente(): void
    {
        $resposta = $this->delete(route('autores.excluir', 99999));

        $resposta->assertStatus(404);
    }

    public function test_exclusao_nao_deve_afetar_outros_autores(): void
    {
        $autorUm = $this->criarAutor(['Nome' => 'Autor Um']);
        $autorDois = $this->criarAutor(['Nome' => 'Autor Dois']);

        $this->delete(route('autores.excluir', $autorUm->CodAu));

        $this->assertDatabaseMissing('autores', ['CodAu' => $autorUm->CodAu]);
        $this->assertDatabaseHas('autores', ['CodAu' => $autorDois->CodAu]);
    }
}
