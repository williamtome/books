<?php

namespace App\Http\Controllers;

use App\Http\Requests\LivroRequest;
use App\Models\Livro;
use Illuminate\Http\Request;

class LivroController extends Controller
{
    public function index()
    {
        return view('livros.index', [
            'livros' => Livro::all(),
        ]);
    }

    public function create()
    {
        return view('livros.cadastro');
    }

    public function store(LivroRequest $request)
    {
        Livro::create([
            'Titulo' => $request->Titulo,
            'Editora' => $request->Editora,
            'Edicao' => $request->Edicao,
            'AnoPublicacao' => $request->AnoPublicacao,
        ]);

        return redirect()->route('livros.index')
            ->with('sucesso', 'Livro cadastrado com sucesso!');
    }

    public function edit(string $id)
    {
        $livro = Livro::findOrFail($id);
        return view('livros.edicao', ['livro' => $livro]);
    }

    public function update(LivroRequest $request, string $id)
    {
        $livro = Livro::findOrFail($id);
        $livro->update($request->validated());
        return redirect()->route('livros.index')
            ->with('sucesso', 'Livro atualizado com sucesso!');
    }

    public function destroy(string $id)
    {
        $livro = Livro::findOrFail($id);
        $livro->delete();
        return redirect()->route('livros.index');
    }
}
