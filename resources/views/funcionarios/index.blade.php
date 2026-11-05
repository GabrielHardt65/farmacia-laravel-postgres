
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

        @if (session('error'))
            <div role="alert" class="funcionarios-alerta-erro">
                {{ session('error') }}
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
                            <th>Ações</th>
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

                                <td>
                                    <div class="funcionarios-acoes">

                                        <a
                                            href="{{ route('funcionarios.show', $funcionario->id_funcionario) }}"
                                            class="funcionario-btn-acao"
                                            title="Visualizar funcionário"
                                            aria-label="Visualizar funcionário"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="19"
                                                height="19"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            >
                                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </a>

                                        <a
                                            href="{{ route('funcionarios.edit', $funcionario->id_funcionario) }}"
                                            class="funcionario-btn-acao"
                                            title="Editar funcionário"
                                            aria-label="Editar funcionário"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="19"
                                                height="19"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            >
                                                <path d="M12 20h9" />
                                                <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L9 17l-4 1 1-4Z" />
                                            </svg>
                                        </a>

                                        @if ((int) auth()->id() !== (int) $funcionario->id_funcionario)
                                            <form
                                                action="{{ route('funcionarios.destroy', $funcionario->id_funcionario) }}"
                                                method="POST"
                                                class="funcionarios-form-excluir"
                                                onsubmit="return confirm('Tem certeza que deseja excluir este funcionário? Esta ação não poderá ser desfeita.')"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="funcionario-btn-acao funcionario-btn-excluir"
                                                    title="Excluir funcionário"
                                                    aria-label="Excluir funcionário"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        width="19"
                                                        height="19"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                    >
                                                        <path d="M3 6h18" />
                                                        <path d="M8 6V4h8v2" />
                                                        <path d="M19 6l-1 14H6L5 6" />
                                                        <path d="M10 11v6" />
                                                        <path d="M14 11v6" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="empty-table">
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

<style>
    .funcionarios-acoes {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .funcionario-btn-acao {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        padding: 0;
        background: #fff0f6;
        color: #cc3676;
        border: 1px solid #f1b8d0;
        border-radius: 8px;
        text-decoration: none;
        cursor: pointer;
        transition: 0.2s ease;
    }

    .funcionario-btn-acao:hover {
        background: #fbdde9;
        color: #c52e6d;
    }

    .funcionario-btn-acao:focus-visible {
        outline: 2px solid #cc3676;
        outline-offset: 2px;
    }

    .funcionario-btn-acao svg {
        width: 19px;
        height: 19px;
    }

    .funcionarios-form-excluir {
        display: inline-flex;
        margin: 0;
        padding: 0;
    }

    .funcionario-btn-excluir {
        background: #fff0f0;
        color: #dc3545;
        border: 1px solid #f3b7b7;
        font-family: inherit;
    }

    .funcionario-btn-excluir:hover {
        background: #ffe0e0;
        color: #b42318;
        border-color: #e89a9a;
    }

    .funcionario-btn-excluir:focus-visible {
        outline: 2px solid #dc3545;
    }

    .funcionarios-alerta-erro {
        padding: 14px;
        margin-bottom: 20px;
        border-radius: 8px;
        background: #fff0f0;
        color: #b42318;
        border: 1px solid #f3b7b7;
    }
</style>

@endsection
