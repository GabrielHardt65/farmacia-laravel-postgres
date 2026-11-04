
@extends('layouts.app')

@section('title', 'Funcionários - Sistema Farmácia')
@section('page-title', 'Funcionários')

@section('content')

<div class="funcionarios-container">
    <section class="funcionarios-section">

        <div class="section-header">
            <div>
                <h2>Funcionários</h2>
                <p>Consulte e gerencie os funcionários cadastrados na farmácia.</p>
            </div>

            <a href="{{ route('funcionarios.create') }}" class="btn-finalizar">
                Novo Funcionário
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('funcionarios.index') }}" method="GET"
              class="funcionarios-filtros">

            <div class="funcionarios-field">
                <label for="busca">Buscar funcionário</label>
                <input type="text" id="busca" name="busca"
                       value="{{ $busca }}"
                       placeholder="Nome, CPF ou cargo">
            </div>

            <button type="submit" class="btn-secondary">Buscar</button>
        </form>

        <table class="sale-table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Cargo</th>
                    <th>Telefone</th>
                    <th>Login</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($funcionarios as $funcionario)
                    <tr>
                        <td>{{ $funcionario->id_funcionario }}</td>
                        <td>{{ $funcionario->nome }}</td>
                        <td>{{ $funcionario->cpf }}</td>
                        <td>{{ $funcionario->cargo }}</td>
                        <td>{{ $funcionario->telefone ?? '-' }}</td>
                        <td>{{ $funcionario->login }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-table">
                            Nenhum funcionário encontrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $funcionarios->links() }}

    </section>
</div>

@endsection
