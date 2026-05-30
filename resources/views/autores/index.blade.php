@extends('app')

@section('content')
    @if (session('sucesso'))
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
            {{ session('sucesso') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    @endif

    @if (session('erro'))
        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
            {{ session('erro') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    @endif

    <h1>Autores</h1>

    <div>
        <a href="/" class="btn btn-secondary">Voltar</a>
        <a href="{{ route('autores.cadastro') }}" class="btn btn-primary">Cadastrar</a>
    </div>

    <ul>
        @foreach($autores as $autor)
            <li>{{ $autor->Nome }}</li>
            <a href="{{ route('autores.editar', $autor->CodAu) }}" class="btn btn-primary">Alterar</a>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalDialogCentered">
                Excluir
            </button>

            <div class="modal fade" id="modalDialogCentered" aria-labelledby="modalDialogCentered" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Excluir Autor</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Tem certeza que deseja excluir este autor?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <form action="{{ route('autores.excluir', $autor->CodAu) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Sim</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        @endforeach
    </ul>


@endsection
