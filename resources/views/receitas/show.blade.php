@extends('layouts.app')

@section('title', 'Detalhes da Receita - Sistema Farmácia')
@section('page-title', 'Detalhes da Receita')

@section('content')

<div class="receita-detalhes-container">
    <section class="receita-detalhes-card">
        <div class="receita-detalhes-cabecalho">
            <div>
                <h2>Receita #{{ $receita->id_receita }}</h2>
                <p>Informações completas da receita médica cadastrada.</p>
            </div>

            <a href="{{ route('receitas.index') }}" class="receita-btn-voltar">
                Voltar às receitas
            </a>
        </div>

        <div class="receita-detalhes-grupo">
            <h3>Informações da receita</h3>

            <div class="receita-detalhes-grid">
                <div class="receita-detalhes-campo">
                    <span>Número da receita</span>
                    <strong>{{ $receita->numero_receita ?: 'Não informado' }}</strong>
                </div>

                <div class="receita-detalhes-campo">
                    <span>Tipo de receita</span>
                    <strong>
                        {{ [
                            'branca' => 'Branca',
                            'controle_especial' => 'Controle especial',
                            'azul' => 'Azul',
                            'amarela' => 'Amarela'
                        ][$receita->tipo_receita] ?? 'Não informado' }}
                    </strong>
                </div>

                <div class="receita-detalhes-campo">
                    <span>Data de emissão</span>
                    <strong>{{ \Carbon\Carbon::parse($receita->data_emissao)->format('d/m/Y') }}</strong>
                </div>

                <div class="receita-detalhes-campo">
                    <span>Validade</span>
                    <strong>
                        {{ $receita->validade_receita
                            ? \Carbon\Carbon::parse($receita->validade_receita)->format('d/m/Y')
                            : 'Não informada' }}
                    </strong>
                </div>
            </div>
        </div>

        <div class="receita-detalhes-grupo">
            <h3>Paciente</h3>

            <div class="receita-detalhes-grid">
                <div class="receita-detalhes-campo">
                    <span>Nome</span>
                    <strong>{{ $receita->cliente }}</strong>
                </div>

                <div class="receita-detalhes-campo">
                    <span>CPF</span>
                    <strong>{{ $receita->cpf_paciente ?: 'Não informado' }}</strong>
                </div>
            </div>
        </div>

        <div class="receita-detalhes-grupo">
            <h3>Médico responsável</h3>

            <div class="receita-detalhes-grid">
                <div class="receita-detalhes-campo">
                    <span>Nome do médico</span>
                    <strong>{{ $receita->medico }}</strong>
                </div>

                <div class="receita-detalhes-campo">
                    <span>CRM</span>
                    <strong>{{ $receita->crm }}</strong>
                </div>
            </div>
        </div>

        <div class="receita-detalhes-grupo">
            <h3>Medicamento e venda</h3>

            <div class="receita-detalhes-grid">
                <div class="receita-detalhes-campo">
                    <span>Medicamento</span>
                    <strong>{{ $receita->medicamento }}</strong>
                </div>

                <div class="receita-detalhes-campo">
                    <span>Venda vinculada</span>
                    <strong>#{{ $receita->id_venda }}</strong>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    .receita-detalhes-container {
        max-width: 1100px;
        margin: 0 auto;
    }

    .receita-detalhes-card {
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
    }

    .receita-detalhes-cabecalho {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 30px;
    }

    .receita-detalhes-cabecalho h2 {
        color: #7a1e3a;
        margin-bottom: 6px;
    }

    .receita-detalhes-cabecalho p {
        color: #777;
    }

    .receita-btn-voltar {
        background: #7a1e3a;
        color: #fff;
        padding: 11px 18px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        white-space: nowrap;
    }

    .receita-btn-voltar:hover {
        background: #5d162c;
        color: #fff;
    }

    .receita-detalhes-grupo {
        padding: 24px 0;
        border-top: 1px solid #e5e7eb;
    }

    .receita-detalhes-grupo h3 {
        color: #7a1e3a;
        font-size: 17px;
        margin-bottom: 20px;
    }

    .receita-detalhes-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }

    .receita-detalhes-campo {
        display: flex;
        flex-direction: column;
        gap: 7px;
        padding: 16px;
        background: #f7f3f0;
        border-radius: 8px;
    }

    .receita-detalhes-campo span {
        font-size: 13px;
        color: #666;
    }

    .receita-detalhes-campo strong {
        font-size: 15px;
        color: #333;
        overflow-wrap: anywhere;
    }

    @media (max-width: 700px) {
        .receita-detalhes-card {
            padding: 18px;
        }

        .receita-detalhes-cabecalho {
            flex-direction: column;
            align-items: flex-start;
        }

        .receita-detalhes-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

@endsection
