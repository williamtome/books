<?php

namespace App\Http\Controllers;

use App\Http\Requests\AutorRequest;
use App\Models\Autor;

class AutorController extends Controller
{
    public function index()
    {
        return view('autores.index', [
            'autores' => Autor::all(),
        ]);
    }

    public function create()
    {
        return view('autores.cadastro');
    }

    public function store(AutorRequest $request)
    {
        Autor::create([
            'Nome' => $request->Nome,
        ]);

        return redirect()->route('autores.index')
            ->with('sucesso', 'Autor cadastrado com sucesso!');
    }

    public function edit(string $id)
    {
        $autor = Autor::findOrFail($id);

        return view('autores.edicao', ['autor' => $autor]);
    }

    public function update(AutorRequest $request, string $id)
    {
        $autor = Autor::findOrFail($id);
        $autor->update($request->validated());

        return redirect()->route('autores.index')
            ->with('sucesso', 'Autor atualizado com sucesso!');
    }

    public function destroy(string $id)
    {
        $autor = Autor::findOrFail($id);
        $autor->delete();

        return redirect()->route('autores.index');
    }
}
