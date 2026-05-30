<?php

namespace Tests\Feature\Controllers;

use App\Models\Livro;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LivroControllerTest extends TestCase
{
    use RefreshDatabase;

    // Métodos auxiliares
    private function dadosValidos(array $sobrescrever = []): array
    {
        return array_merge([
            'Titulo' => 'Código Limpo',
            'Editora' => 'Alta Books',
            'Edicao' => 1,
            'AnoPublicacao' => '2009',
        ], $sobrescrever);
    }

    private function criarLivro(array $atributos = []): Livro
    {
        return Livro::create($this->dadosValidos($atributos));
    }

    // Testes do método index (listagem)
    public function test_pagina_de_listagem_retorna_status_200(): void
    {
        $resposta = $this->get(route('livros.index'));

        $resposta->assertStatus(200);
    }

    public function test_pagina_de_listagem_exibe_a_view_correta(): void
    {
        $resposta = $this->get(route('livros.index'));

        $resposta->assertViewIs('livros.index');
    }

    public function test_pagina_de_listagem_passa_variavel_livros_para_a_view(): void
    {
        $resposta = $this->get(route('livros.index'));

        $resposta->assertViewHas('livros');
    }

    public function test_pagina_de_listagem_exibe_livros_cadastrados(): void
    {
        $this->criarLivro(['Titulo' => 'O Programador Pragmático']);
        $this->criarLivro(['Titulo' => 'Refatoração']);

        $resposta = $this->get(route('livros.index'));

        $resposta->assertSee('O Programador Pragmático');
        $resposta->assertSee('Refatoração');
    }

    public function test_pagina_de_listagem_sem_livros_cadastrados(): void
    {
        $resposta = $this->get(route('livros.index'));

        $resposta->assertViewHas('livros', function ($livros) {
            return $livros->isEmpty();
        });
    }

    // Testes do método create (formulário de cadastro)
    public function test_pagina_de_cadastro_retorna_status_200(): void
    {
        $resposta = $this->get(route('livros.cadastro'));

        $resposta->assertStatus(200);
    }

    public function test_pagina_de_cadastro_exibe_a_view_correta(): void
    {
        $resposta = $this->get(route('livros.cadastro'));

        $resposta->assertViewIs('livros.cadastro');
    }

    // Testes do método store (salvar cadastro)
    public function test_cadastro_salva_livro_no_banco_de_dados(): void
    {
        $this->post(route('livros.cadastrar'), $this->dadosValidos());

        $this->assertDatabaseHas('livros', [
            'Titulo' => 'Código Limpo',
            'Editora' => 'Alta Books',
            'Edicao' => 1,
            'AnoPublicacao' => '2009',
        ]);
    }

    public function test_cadastro_redireciona_para_listagem_apos_salvar(): void
    {
        $resposta = $this->post(route('livros.cadastrar'), $this->dadosValidos());

        $resposta->assertRedirect(route('livros.index'));
    }

    public function test_cadastro_exibe_mensagem_de_sucesso_apos_salvar(): void
    {
        $resposta = $this->post(route('livros.cadastrar'), $this->dadosValidos());

        $resposta->assertSessionHas('sucesso', 'Livro cadastrado com sucesso!');
    }

    public function test_cadastro_falha_quando_titulo_nao_informado(): void
    {
        $resposta = $this->post(route('livros.cadastrar'), $this->dadosValidos([
            'Titulo' => '',
        ]));

        $resposta->assertSessionHasErrors('Titulo');
        $this->assertDatabaseMissing('livros', ['Editora' => 'Alta Books']);
    }

    public function test_cadastro_falha_quando_editora_nao_informada(): void
    {
        $resposta = $this->post(route('livros.cadastrar'), $this->dadosValidos([
            'Editora' => '',
        ]));

        $resposta->assertSessionHasErrors('Editora');
        $this->assertDatabaseMissing('livros', ['Titulo' => 'Código Limpo']);
    }

    public function test_cadastro_falha_quando_edicao_nao_informada(): void
    {
        $resposta = $this->post(route('livros.cadastrar'), $this->dadosValidos([
            'Edicao' => '',
        ]));

        $resposta->assertSessionHasErrors('Edicao');
        $this->assertDatabaseMissing('livros', ['Edicao' => 1]);
    }

    public function test_cadastro_falha_quando_ano_publicacao_invalido(): void
    {
        $resposta = $this->post(route('livros.cadastrar'), $this->dadosValidos([
            'AnoPublicacao' => '99',
        ]));

        $resposta->assertSessionHasErrors('AnoPublicacao');
        $this->assertDatabaseMissing('livros', ['AnoPublicacao' => '2009']);
    }

    public function test_cadastro_falha_quando_ano_publicacao_negativo(): void
    {
        $resposta = $this->post(route('livros.cadastrar'), $this->dadosValidos([
            'AnoPublicacao' => '-999',
        ]));

        $resposta->assertSessionHasErrors('AnoPublicacao');
        $this->assertDatabaseMissing('livros', ['AnoPublicacao' => '2009']);
    }

    // Testes do método edit (formulário de edição)
    public function test_pagina_de_edicao_retorna_status_200(): void
    {
        $livro = $this->criarLivro();

        $resposta = $this->get(route('livros.editar', $livro->CodL));

        $resposta->assertStatus(200);
    }

    public function test_pagina_de_edicao_exibe_a_view_correta(): void
    {
        $livro = $this->criarLivro();

        $resposta = $this->get(route('livros.editar', $livro->CodL));

        $resposta->assertViewIs('livros.edicao');
    }

    public function test_pagina_de_edicao_envia_o_livro_para_a_view(): void
    {
        $livro = $this->criarLivro();

        $resposta = $this->get(route('livros.editar', $livro->CodL));

        $resposta->assertViewHas('livro', function ($livroNaView) use ($livro) {
            return $livroNaView->CodL === $livro->CodL;
        });
    }

    public function test_pagina_de_edicao_retorna_404_para_livro_inexistente(): void
    {
        $resposta = $this->get(route('livros.editar', 99999));

        $resposta->assertStatus(404);
    }

    // Testes do método update (salvar edição)
    public function test_atualizacao_salva_dados_no_banco_de_dados(): void
    {
        $livro = $this->criarLivro();

        $this->put(route('livros.atualizar', $livro->CodL), $this->dadosValidos([
            'Titulo' => 'Título Atualizado',
            'Edicao' => 2,
        ]));

        $this->assertDatabaseHas('livros', [
            'CodL' => $livro->CodL,
            'Titulo' => 'Título Atualizado',
            'Edicao' => 2,
        ]);
    }

    public function test_atualizacao_redireciona_para_listagem_apos_salvar(): void
    {
        $livro = $this->criarLivro();

        $resposta = $this->put(route('livros.atualizar', $livro->CodL), $this->dadosValidos());

        $resposta->assertRedirect(route('livros.index'));
    }

    public function test_atualizacao_exibe_mensagem_de_sucesso_apos_salvar(): void
    {
        $livro = $this->criarLivro();

        $resposta = $this->put(route('livros.atualizar', $livro->CodL), $this->dadosValidos());

        $resposta->assertSessionHas('sucesso', 'Livro atualizado com sucesso!');
    }

    public function test_atualizacao_retorna_404_para_livro_inexistente(): void
    {
        $resposta = $this->put(route('livros.atualizar', 99999), $this->dadosValidos());

        $resposta->assertStatus(404);
    }

    public function test_atualizacao_falha_quando_titulo_nao_informado(): void
    {
        $livro = $this->criarLivro();

        $resposta = $this->put(route('livros.atualizar', $livro->CodL), $this->dadosValidos([
            'Titulo' => '',
        ]));

        $resposta->assertSessionHasErrors('Titulo');
        $this->assertDatabaseHas('livros', ['Titulo' => 'Código Limpo']);
    }

    public function test_atualizacao_falha_quando_ano_publicacao_invalido(): void
    {
        $livro = $this->criarLivro();

        $resposta = $this->put(route('livros.atualizar', $livro->CodL), $this->dadosValidos([
            'AnoPublicacao' => '99',
        ]));

        $resposta->assertSessionHasErrors('AnoPublicacao');
        $this->assertDatabaseHas('livros', ['AnoPublicacao' => '2009']);
    }

    // Testes do método destroy (exclusão)
    public function test_exclusao_remove_livro_do_banco_de_dados(): void
    {
        $livro = $this->criarLivro();

        $this->delete(route('livros.excluir', $livro->CodL));

        $this->assertDatabaseMissing('livros', ['CodL' => $livro->CodL]);
    }

    public function test_exclusao_redireciona_para_listagem_apos_deletar(): void
    {
        $livro = $this->criarLivro();

        $resposta = $this->delete(route('livros.excluir', $livro->CodL));

        $resposta->assertRedirect(route('livros.index'));
    }

    public function test_exclusao_retorna_404_para_livro_inexistente(): void
    {
        $resposta = $this->delete(route('livros.excluir', 99999));

        $resposta->assertStatus(404);
    }

    public function test_exclusao_nao_deve_afetar_outros_livros(): void
    {
        $livroUm = $this->criarLivro(['Titulo' => 'Livro Um']);
        $livroDois = $this->criarLivro(['Titulo' => 'Livro Dois']);

        $this->delete(route('livros.excluir', $livroUm->CodL));

        $this->assertDatabaseMissing('livros', ['CodL' => $livroUm->CodL]);
        $this->assertDatabaseHas('livros', ['CodL' => $livroDois->CodL]);
    }
}
