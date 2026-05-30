@extends('app')

@section('content')
    <h1>Edição de Livros</h1>

    <form action="{{ route('livros.atualizar', $livro->CodL) }}" method="post">
        @csrf
        @method('PUT')
        <div class="my-2">
            <label for="Titulo" class="form-label">Título</label>
            <input type="text" name="Titulo" class="form-control {{ $errors->has('Titulo') ? 'is-invalid' : 'is-valid' }}" id="Titulo" value="{{ old('Titulo', $livro->Titulo) }}"/>
            @if($errors->has('Titulo'))
                <div class="invalid-feedback">
                    <p>{{ $errors->get('Titulo')[0] }}</p>
                </div>
            @endif
        </div>
        <div class="my-2">
            <label for="Editora" class="form-label">Editora</label>
            <input type="text" name="Editora" class="form-control {{ $errors->has('Editora') ? 'is-invalid' : 'is-valid' }}" id="Editora" value="{{ old('Editora', $livro->Editora) }}"/>
            @if($errors->has('Editora'))
                <div class="invalid-feedback">
                    <p>{{ $errors->get('Editora')[0] }}</p>
                </div>
            @endif
        </div>
        <div class="my-2">
            <label for="Edicao" class="form-label">Edição</label>
            <input type="number" name="Edicao" placeholder="ex.: (1, 2, 3, 4...)" class="form-control {{ $errors->has('Edicao') ? 'is-invalid' : 'is-valid' }}" min="1" id="Edicao" value="{{ old('Edicao', $livro->Edicao) }}"/>
            @if($errors->has('Edicao'))
                <div class="invalid-feedback">
                    <p>{{ $errors->get('Edicao')[0] }}</p>
                </div>
            @endif
        </div>
        <div class="my-2">
            <label for="AnoPublicacao" class="form-label">Ano de Publicação</label>
            <input type="text" name="AnoPublicacao" class="form-control {{ $errors->has('AnoPublicacao') ? 'is-invalid' : 'is-valid' }}" maxlength="4" id="AnoPublicacao" value="{{ old('AnoPublicacao', $livro->AnoPublicacao) }}"/>
            @if($errors->has('AnoPublicacao'))
                <div class="invalid-feedback">
                    <p>{{ $errors->get('AnoPublicacao')[0] }}</p>
                </div>
            @endif
        </div>
        <div class="my-2">
            <button type="submit" class="btn btn-primary">Atualizar</button>
            <a href="{{ route('livros.index') }}" class="btn btn-secondary">Voltar</a>
        </div>
    </form>
@endsection
