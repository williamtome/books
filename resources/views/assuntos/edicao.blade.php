@extends('app')

@section('content')
    <h1>Edição de Assuntos</h1>

    <form action="{{ route('assuntos.atualizar', $assunto->CodAs) }}" method="post">
        @csrf
        @method('PUT')
        <div class="my-2">
            <label for="Descricao" class="form-label">Descrição</label>
            <input
                type="text"
                name="Descricao"
                class="form-control {{ $errors->has('Descricao') ? 'is-invalid' : 'is-valid' }}"
                id="Descricao"
                value="{{ old('Descricao', $assunto->Descricao) }}"
            />
            @if($errors->has('Descricao'))
                <div class="invalid-feedback">
                    <p>{{ $errors->get('Descricao')[0] }}</p>
                </div>
            @endif
        </div>
        <div class="my-2">
            <button type="submit" class="btn btn-primary">Atualizar</button>
            <a href="{{ route('assuntos.index') }}" class="btn btn-secondary">Voltar</a>
        </div>
    </form>
@endsection
