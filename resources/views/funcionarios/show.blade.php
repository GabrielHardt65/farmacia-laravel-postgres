
@extends('layouts.app')

@section('title', 'Visualizar Funcionário - Sistema Farmácia')
@section('page-title', 'Visualizar Funcionário')

@section('content')

<div class="clientes-container">
    <section class="clientes-section">

        <div class="section-header">
            <div>
                <h2>Dados do Funcionário</h2>
                <p>Informações do cadastro.</p>
            </div>

            <a href="{{ route('funcionarios.index') }}" class="btn-secondary">
                Voltar
            </a>
        </div>

        <div class="funcionario-detalhes">

            <div>
                <span>Código</span>
                <strong>{{ $funcionario->id_funcionario }}</strong>
            </div>

            <div>
                <span>Nome</span>
                <strong>{{ $funcionario->nome }}</strong>
            </div>

            <div>
                <span>CPF</span>
                <strong>{{ $funcionario->cpf }}</strong>
            </div>

            <div>
                <span>Cargo</span>
                <strong>{{ $funcionario->cargo }}</strong>
            </div>

            <div>
                <span>Data de admissão</span>
                <strong>{{ \Carbon\Carbon::parse($funcionario->data_admissao)->format('d/m/Y') }}</strong>
            </div>

            <div>
                <span>Salário</span>
                <strong>R$ {{ number_format($funcionario->salario, 2, ',', '.') }}</strong>
            </div>

            <div>
                <span>Telefone</span>
                <strong>{{ $funcionario->telefone ?: 'Não informado' }}</strong>
            </div>

            <div>
                <span>E-mail</span>
                <strong>{{ $funcionario->email ?: 'Não informado' }}</strong>
            </div>

            <div>
                <span>Login</span>
                <strong>{{ $funcionario->login }}</strong>
            </div>

            <div>
                <span>Responsável técnico</span>
                <strong>{{ $funcionario->e_responsavel_tecnico ? 'Sim' : 'Não' }}</strong>
            </div>

            <div>
                <span>Código do supervisor</span>
                <strong>{{ $funcionario->id_supervisor ?: 'Não informado' }}</strong>
            </div>

        </div>

        <div class="funcionario-acoes">
            <a href="{{ route('funcionarios.edit', $funcionario->id_funcionario) }}"
               class="btn-finalizar">
                Editar Funcionário
            </a>
        </div>

    </section>
</div>

<style>
    .funcionario-detalhes {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
        margin-top: 24px;
    }

    .funcionario-detalhes > div {
        display: flex;
        flex-direction: column;
        gap: 8px;
        padding: 18px;
        background: #fff0f6;
        border: 1px solid #f1b8d0;
        border-radius: 10px;
    }

    .funcionario-detalhes span {
        color: #858999;
        font-size: 13px;
    }

    .funcionario-detalhes strong {
        color: #35394b;
        font-size: 15px;
        overflow-wrap: anywhere;
    }

    .funcionario-acoes {
        margin-top: 24px;
    }

    @media (max-width: 650px) {
        .funcionario-detalhes {
            grid-template-columns: 1fr;
        }
    }
</style>

@endsection
