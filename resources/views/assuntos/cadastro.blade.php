@extends('app')

@section('content')
    <h1>Cadastro de Assuntos</h1>

    <form action="{{ route('assuntos.cadastrar') }}" method="post">
        @csrf
        <div class="my-2">
            <label for="Descricao" class="form-label">Descrição</label>
            <input
                type="text"
                name="Descricao"
                class="form-control {{ $errors->has('Descricao') ? 'is-invalid' : 'is-valid' }}"
                id="Descricao"
                value="{{ old('Descricao') }}"
            />
            @if($errors->has('Descricao'))
                <div class="invalid-feedback">
                    <p>{{ $errors->get('Descricao')[0] }}</p>
                </div>
            @endif
        </div>
        <div class="my-2">
            <button type="submit" class="btn btn-primary">Cadastrar</button>
            <a href="{{ route('assuntos.index') }}" class="btn btn-secondary">Voltar</a>
        </div>
    </form>
@endsection
