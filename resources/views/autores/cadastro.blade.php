@extends('app')

@section('content')
    <h1>Cadastro de Autores</h1>

    <a href="{{ route('autores.index') }}" class="btn btn-secondary">Voltar</a>

    <form action="{{ route('autores.cadastrar') }}" method="post">
        @csrf
        <div class="my-2">
            <label for="Nome" class="form-label">Nome</label>
            <input
                type="text"
                name="Nome"
                class="form-control {{ $errors->has('Nome') ? 'is-invalid' : 'is-valid' }}"
                id="Nome"
                value="{{ old('Nome') }}"
            />
            @if($errors->has('Nome'))
                <div class="invalid-feedback">
                    <p>{{ $errors->get('Nome')[0] }}</p>
                </div>
            @endif
        </div>
        <div class="my-2">
            <button type="submit" class="btn btn-primary">Cadastrar</button>
        </div>
    </form>
@endsection
