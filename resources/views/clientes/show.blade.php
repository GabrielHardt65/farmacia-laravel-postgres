
@extends('layouts.app')

@section('title', 'Visualizar Cliente - Sistema Farmácia')
@section('page-title', 'Visualizar Cliente')

@section('content')

<div class="clientes-container">
    <section class="clientes-section">
        <div class="section-header">
            <div>
                <h2>Dados do Cliente</h2>
                <p>Informações completas do cadastro.</p>
            </div>

            <a href="{{ route('clientes.index') }}" class="btn-secondary">
                Voltar
            </a>
        </div>

        <div class="cliente-detalhes">
            <div>
                <span>Código</span>
                <strong>#{{ $cliente->id_cliente }}</strong>
            </div>

            <div>
                <span>Nome completo</span>
                <strong>{{ $cliente->nome }}</strong>
            </div>

            <div>
                <span>CPF</span>
                <strong>{{ $cliente->cpf }}</strong>
            </div>

            <div>
                <span>Data de nascimento</span>
                <strong>{{ $cliente->data_nascimento?->format('d/m/Y') ?? 'Não informada' }}</strong>
            </div>

            <div>
                <span>Telefone</span>
                <strong>{{ $cliente->telefone ?: 'Não informado' }}</strong>
            </div>

            <div>
                <span>E-mail</span>
                <strong>{{ $cliente->email ?: 'Não informado' }}</strong>
            </div>

            <div>
                <span>Programa de fidelidade</span>
                <strong>{{ $cliente->programa_fidelidade ? 'Sim' : 'Não' }}</strong>
            </div>

            <div>
                <span>Pontos de fidelidade</span>
                <strong>{{ $cliente->pontos_fidelidade }}</strong>
            </div>

            <div>
                <span>Data de cadastro</span>
                <strong>{{ $cliente->data_cadastro?->format('d/m/Y') ?? 'Não informada' }}</strong>
            </div>
        </div>

        <div class="cliente-detalhes-acoes">
            <a href="{{ route('clientes.edit', $cliente->id_cliente) }}"
               class="btn-finalizar">
                Editar Cliente
            </a>
        </div>
    </section>
</div>

<style>
    .cliente-detalhes {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
        margin-top: 24px;
    }

    .cliente-detalhes > div {
        display: flex;
        flex-direction: column;
        gap: 8px;
        padding: 18px;
        background: #fff0f6;
        border: 1px solid #f1b8d0;
        border-radius: 10px;
    }

    .cliente-detalhes span {
        font-size: 13px;
        color: #858999;
    }

    .cliente-detalhes strong {
        color: #35394b;
        font-size: 15px;
        overflow-wrap: anywhere;
    }

    .cliente-detalhes-acoes {
        margin-top: 24px;
    }

    @media (max-width: 650px) {
        .cliente-detalhes {
            grid-template-columns: 1fr;
        }
    }
</style>

@endsection
