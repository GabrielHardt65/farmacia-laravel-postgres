
@extends('layouts.app')

@section('title', 'Editar Cliente - Sistema Farmácia')
@section('page-title', 'Editar Cliente')

@section('content')

<div class="clientes-container">
    <section class="clientes-section">
        <div class="section-header">
            <div>
                <h2>Editar Cliente</h2>
                <p>Atualize as informações do cliente.</p>
            </div>
        </div>

        @if ($errors->any())
            <div style="color:#b42318; margin-bottom:20px;">
                @foreach ($errors->all() as $erro)
                    <p>{{ $erro }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('clientes.update', $cliente->id_cliente) }}" method="POST" class="clientes-edicao">
            @csrf
            @method('PUT')

            <label>Nome completo</label>
            <input type="text" name="nome" value="{{ old('nome', $cliente->nome) }}" required>

            <label>CPF</label>
            <input type="text" name="cpf" value="{{ old('cpf', $cliente->cpf) }}" required maxlength="14">

            <label>Data de nascimento</label>
            <input type="date" name="data_nascimento" value="{{ old('data_nascimento', $cliente->data_nascimento?->format('Y-m-d')) }}" required>

            <label>Telefone</label>
            <input type="text" name="telefone" value="{{ old('telefone', $cliente->telefone) }}" required>

            <label>E-mail</label>
            <input type="email" name="email" value="{{ old('email', $cliente->email) }}" required>

            <label>Programa de fidelidade</label>
            <select name="programa_fidelidade">
                <option value="1" @selected(old('programa_fidelidade', (int) $cliente->programa_fidelidade) == 1)>Sim</option>
                <option value="0" @selected(old('programa_fidelidade', (int) $cliente->programa_fidelidade) == 0)>Não</option>
            </select>

            <label>Pontos de fidelidade</label>
            <input type="number" name="pontos_fidelidade" min="0" value="{{ old('pontos_fidelidade', $cliente->pontos_fidelidade) }}" required>

            <div class="clientes-edicao-acoes">
                <button type="submit" class="btn-finalizar">Salvar alterações</button>
                <a href="{{ route('clientes.index') }}" class="btn-secondary">Cancelar</a>
            </div>
        </form>
    </section>
</div>

<style>
    .clientes-edicao {
        display: flex;
        flex-direction: column;
        gap: 10px;
        max-width: 650px;
    }

    .clientes-edicao label {
        font-weight: 600;
        color: #35394b;
        margin-top: 8px;
    }

    .clientes-edicao input,
    .clientes-edicao select {
        width: 100%;
        padding: 12px;
        border: 1px solid #dfe2ed;
        border-radius: 8px;
        background: #fffafc;
        font: inherit;
    }

    .clientes-edicao input:focus,
    .clientes-edicao select:focus {
        outline: none;
        border-color: #d83e80;
    }

    .clientes-edicao-acoes {
        display: flex;
        gap: 12px;
        margin-top: 20px;
        align-items: center;
    }
</style>

@endsection
