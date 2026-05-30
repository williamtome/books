<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssuntoRequest;
use App\Models\Assunto;

class AssuntoController extends Controller
{
    public function index()
    {
        return view('assuntos.index', [
            'assuntos' => Assunto::all(),
        ]);
    }

    public function create()
    {
        return view('assuntos.cadastro');
    }

    public function store(AssuntoRequest $request)
    {
        Assunto::create([
            'Descricao' => $request->Descricao,
        ]);

        return redirect()->route('assuntos.index')
            ->with('sucesso', 'Assunto cadastrado com sucesso!');
    }

    public function edit(string $id)
    {
        $assunto = Assunto::findOrFail($id);

        return view('assuntos.edicao', ['assunto' => $assunto]);
    }

    public function update(AssuntoRequest $request, string $id)
    {
        $assunto = Assunto::findOrFail($id);
        $assunto->update($request->validated());

        return redirect()->route('assuntos.index')
            ->with('sucesso', 'Assunto atualizado com sucesso!');
    }

    public function destroy(string $id)
    {
        $assunto = Assunto::findOrFail($id);
        $assunto->delete();

        return redirect()->route('assuntos.index');
    }
}
