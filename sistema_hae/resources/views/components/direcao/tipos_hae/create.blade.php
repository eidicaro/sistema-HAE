@extends('layouts.app')

@section('content')
<style>
    .th-container { max-width: 500px; margin: 2rem auto; padding: 0 1rem; }
    .th-header h1 { color: #b20000; font-size: 1.5rem; margin-bottom: 1.5rem; }
    .th-card { background: #fff; border: 1px solid #e5e5e5; border-radius: 10px; padding: 1.5rem; }
    .th-field { margin-bottom: 1rem; }
    .th-field label { display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.3rem; color: #333; }
    .th-field input[type=text], .th-field input[type=number], .th-field textarea {
        width: 100%; padding: 0.5rem 0.7rem; border: 1px solid #ccc; border-radius: 6px; font-size: 0.9rem; box-sizing: border-box;
    }
    .th-checkbox label { display: flex; align-items: center; gap: 0.5rem; font-weight: normal; }
    .th-error { color: #b20000; font-size: 0.8rem; }
    .th-btn { background: #b20000; color: #fff; border: none; padding: 0.55rem 1.1rem; border-radius: 6px; text-decoration: none; font-size: 0.9rem; cursor: pointer; }
    .th-btn:hover { background: #8f0000; }
    .th-btn-outline { background: #fff; color: #b20000; border: 1px solid #b20000; margin-left: 0.5rem; }
    .th-actions-bottom { display: flex; margin-top: 1.2rem; }
</style>

<div class="th-container">
    <div class="th-header"><h1>Novo Tipo de HAE</h1></div>

    <div class="th-card">
        <form action="{{ route('direcao.tipos-hae.store') }}" method="POST">
            @csrf
            @include('direcao.tipos_hae._form')

            <div class="th-actions-bottom">
                <button type="submit" class="th-btn">Salvar</button>
                <a href="{{ route('direcao.tipos-hae.index') }}" class="th-btn th-btn-outline">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection