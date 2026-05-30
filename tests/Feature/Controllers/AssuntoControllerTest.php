<?php

namespace Tests\Feature\Controllers;

use App\Models\Assunto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssuntoControllerTest extends TestCase
{
    use RefreshDatabase;

    // Métodos auxiliares
    private function dadosValidos(array $sobrescrever = []): array
    {
        return array_merge([
            'Descricao' => 'Tecnologia',
        ], $sobrescrever);
    }

    private function criarAssunto(array $atributos = []): Assunto
    {
        return Assunto::create($this->dadosValidos($atributos));
    }

    // Testes do método index (listagem)
    public function test_pagina_de_listagem_retorna_status_200(): void
    {
        $resposta = $this->get(route('assuntos.index'));

        $resposta->assertStatus(200);
    }

    public function test_pagina_de_listagem_exibe_a_view_correta(): void
    {
        $resposta = $this->get(route('assuntos.index'));

        $resposta->assertViewIs('assuntos.index');
    }

    public function test_pagina_de_listagem_passa_variavel_assuntos_para_a_view(): void
    {
        $resposta = $this->get(route('assuntos.index'));

        $resposta->assertViewHas('assuntos');
    }

    public function test_pagina_de_listagem_exibe_assuntos_cadastrados(): void
    {
        $this->criarAssunto(['Descricao' => 'Martin Fowler']);
        $this->criarAssunto(['Descricao' => 'Eric Evans']);

        $resposta = $this->get(route('assuntos.index'));

        $resposta->assertSee('Martin Fowler');
        $resposta->assertSee('Eric Evans');
    }

    public function test_pagina_de_listagem_sem_assuntos_cadastrados(): void
    {
        $resposta = $this->get(route('assuntos.index'));

        $resposta->assertViewHas('assuntos', function ($assuntos) {
            return $assuntos->isEmpty();
        });
    }

    // Testes do método create (formulário de cadastro)
    public function test_pagina_de_cadastro_retorna_status_200(): void
    {
        $resposta = $this->get(route('assuntos.cadastro'));

        $resposta->assertStatus(200);
    }

    public function test_pagina_de_cadastro_exibe_a_view_correta(): void
    {
        $resposta = $this->get(route('assuntos.cadastro'));

        $resposta->assertViewIs('assuntos.cadastro');
    }

    // Testes do método store (salvar cadastro)
    public function test_cadastro_salva_assunto_no_banco_de_dados(): void
    {
        $this->post(route('assuntos.cadastrar'), $this->dadosValidos());

        $this->assertDatabaseHas('assuntos', [
            'Descricao' => 'Tecnologia',
        ]);
    }

    public function test_cadastro_redireciona_para_listagem_apos_salvar(): void
    {
        $resposta = $this->post(route('assuntos.cadastrar'), $this->dadosValidos());

        $resposta->assertRedirect(route('assuntos.index'));
    }

    public function test_cadastro_exibe_mensagem_de_sucesso_apos_salvar(): void
    {
        $resposta = $this->post(route('assuntos.cadastrar'), $this->dadosValidos());

        $resposta->assertSessionHas('sucesso', 'Assunto cadastrado com sucesso!');
    }

    public function test_cadastro_falha_quando_descricao_nao_informado(): void
    {
        $resposta = $this->post(route('assuntos.cadastrar'), $this->dadosValidos([
            'Descricao' => '',
        ]));

        $resposta->assertSessionHasErrors('Descricao');
        $this->assertDatabaseMissing('assuntos', ['Descricao' => 'Tecnologia']);
    }

    public function test_cadastro_falha_quando_descricao_possui_mais_de_20_caracteres(): void
    {
        $resposta = $this->post(route('assuntos.cadastrar'), $this->dadosValidos([
            'Descricao' => str_repeat('A', 21),
        ]));

        $resposta->assertSessionHasErrors('Descricao');
        $this->assertDatabaseMissing('assuntos', ['Descricao' => 'Tecnologia']);
    }

    // Testes do método edit (formulário de edição)
    public function test_pagina_de_edicao_retorna_status_200(): void
    {
        $assunto = $this->criarAssunto();

        $resposta = $this->get(route('assuntos.editar', $assunto->CodAs));

        $resposta->assertStatus(200);
    }

    public function test_pagina_de_edicao_exibe_a_view_correta(): void
    {
        $assunto = $this->criarAssunto();

        $resposta = $this->get(route('assuntos.editar', $assunto->CodAs));

        $resposta->assertViewIs('assuntos.edicao');
    }

    public function test_pagina_de_edicao_retorna_404_para_assunto_inexistente(): void
    {
        $resposta = $this->get(route('assuntos.editar', 99999));

        $resposta->assertStatus(404);
    }

    // Testes do método update (salvar edição)
    public function test_atualizacao_salva_dados_no_banco_de_dados(): void
    {
        $assunto = $this->criarAssunto();

        $this->put(route('assuntos.atualizar', $assunto->CodAs), $this->dadosValidos([
            'Descricao' => 'Descricao Atualizado',
        ]));

        $this->assertDatabaseHas('assuntos', [
            'CodAs' => $assunto->CodAs,
            'Descricao' => 'Descricao Atualizado',
        ]);
    }

    public function test_atualizacao_redireciona_para_listagem_apos_salvar(): void
    {
        $assunto = $this->criarAssunto();

        $resposta = $this->put(route('assuntos.atualizar', $assunto->CodAs), $this->dadosValidos());

        $resposta->assertRedirect(route('assuntos.index'));
    }

    public function test_atualizacao_exibe_mensagem_de_sucesso_apos_salvar(): void
    {
        $assunto = $this->criarAssunto();

        $resposta = $this->put(route('assuntos.atualizar', $assunto->CodAs), $this->dadosValidos());

        $resposta->assertSessionHas('sucesso', 'Assunto atualizado com sucesso!');
    }

    public function test_atualizacao_retorna_404_para_assunto_inexistente(): void
    {
        $resposta = $this->put(route('assuntos.atualizar', 99999), $this->dadosValidos());

        $resposta->assertStatus(404);
    }

    public function test_atualizacao_falha_quando_descricao_nao_informado(): void
    {
        $assunto = $this->criarAssunto();

        $resposta = $this->put(route('assuntos.atualizar', $assunto->CodAs), $this->dadosValidos([
            'Descricao' => '',
        ]));

        $resposta->assertSessionHasErrors('Descricao');
        $this->assertDatabaseHas('assuntos', ['Descricao' => 'Tecnologia']);
    }

    public function test_atualizacao_falha_quando_descricao_possui_mais_de_20_caracteres(): void
    {
        $assunto = $this->criarAssunto();

        $resposta = $this->put(route('assuntos.atualizar', $assunto->CodAs), $this->dadosValidos([
            'Descricao' => str_repeat('B', 21),
        ]));

        $resposta->assertSessionHasErrors('Descricao');
        $this->assertDatabaseHas('assuntos', ['Descricao' => 'Tecnologia']);
    }

    // Testes do método destroy (exclusão)
    public function test_exclusao_remove_assunto_do_banco_de_dados(): void
    {
        $assunto = $this->criarAssunto();

        $this->delete(route('assuntos.excluir', $assunto->CodAs));

        $this->assertDatabaseMissing('assuntos', ['CodAs' => $assunto->CodAs]);
    }

    public function test_exclusao_redireciona_para_listagem_apos_deletar(): void
    {
        $assunto = $this->criarAssunto();

        $resposta = $this->delete(route('assuntos.excluir', $assunto->CodAs));

        $resposta->assertRedirect(route('assuntos.index'));
    }

    public function test_exclusao_retorna_404_para_assunto_inexistente(): void
    {
        $resposta = $this->delete(route('assuntos.excluir', 99999));

        $resposta->assertStatus(404);
    }

    public function test_exclusao_nao_deve_afetar_outros_assuntos(): void
    {
        $assuntoUm = $this->criarAssunto(['Descricao' => 'Descrição Um']);
        $assuntoDois = $this->criarAssunto(['Descricao' => 'Descrição Dois']);

        $this->delete(route('assuntos.excluir', $assuntoUm->CodAs));

        $this->assertDatabaseMissing('assuntos', ['CodAs' => $assuntoUm->CodAs]);
        $this->assertDatabaseHas('assuntos', ['CodAs' => $assuntoDois->CodAs]);
    }
}
