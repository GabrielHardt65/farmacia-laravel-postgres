
@extends('layouts.app')

@section('title', 'Funcionários - Sistema Farmácia')
@section('page-title', 'Funcionários')

@section('content')

<div class="funcionarios-container">
    <section class="funcionarios-section">

        <div class="section-header funcionarios-cabecalho">
            <div>
                <h2>Funcionários</h2>
                <p>
                    Consulte e gerencie os funcionários
                    cadastrados na farmácia.
                </p>
            </div>

            <a href="{{ route('funcionarios.create') }}"
               class="btn-finalizar funcionarios-btn-novo">
                + Novo Funcionário
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="funcionarios-consulta">

            <div class="funcionarios-consulta-titulo">
                <h3>Consultar funcionários</h3>
                <p>
                    Pesquise pelo nome, CPF ou cargo do funcionário.
                </p>
            </div>

            <form
                action="{{ route('funcionarios.index') }}"
                method="GET"
                class="funcionarios-filtros"
            >
                <div class="funcionarios-field">
                    <label for="busca">Buscar funcionário</label>

                    <input
                        type="text"
                        id="busca"
                        name="busca"
                        value="{{ $busca ?? '' }}"
                        placeholder="Digite o nome, CPF ou cargo"
                    >
                </div>

                <button type="submit"
                        class="btn-finalizar funcionarios-btn-buscar">
                    Buscar
                </button>

                <a href="{{ route('funcionarios.index') }}"
                   class="funcionarios-btn-limpar">
                    Limpar
                </a>
            </form>

            <div class="funcionarios-tabela-topo">
                <h3>Funcionários cadastrados</h3>

                <span>
                    {{ $funcionarios->total() }}
                    {{ $funcionarios->total() === 1
                        ? 'funcionário'
                        : 'funcionários' }}
                </span>
            </div>

            <div class="funcionarios-table-wrapper">
                <table class="sale-table funcionarios-table">

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
                                <td class="funcionarios-codigo">
                                    #{{ $funcionario->id_funcionario }}
                                </td>

                                <td class="funcionarios-nome">
                                    {{ $funcionario->nome }}
                                </td>

                                <td>
                                    {{ $funcionario->cpf }}
                                </td>

                                <td>
                                    <span class="funcionarios-cargo">
                                        {{ $funcionario->cargo }}
                                    </span>
                                </td>

                                <td>
                                    {{ $funcionario->telefone ?: '—' }}
                                </td>

                                <td>
                                    {{ $funcionario->login }}
                                </td>
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
            </div>

            <div class="funcionarios-paginacao">
                {{ $funcionarios->withQueryString()->links() }}
            </div>

        </div>

    </section>
</div>

@endsection
