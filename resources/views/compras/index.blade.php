@extends('layouts.app')

@section('title', 'Controle de Estoque - Sistema Farmácia')
@section('page-title', 'Controle de Estoque')

@section('content')

<div class="compras-container">

    <section class="compras-section">

        <div class="section-header">
            <div>
                <h2>Controle de Estoque</h2>
                <p>
                    Acompanhe os medicamentos disponíveis,
                    identifique alertas e consulte os lotes.
                </p>
            </div>

            <a href="{{ route('compras.create') }}" class="btn-finalizar">
                + Registrar Entrada
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Indicadores do estoque --}}
        <div class="estoque-indicadores">

            <a href="{{ route('compras.index') }}" class="estoque-card">
                <span class="estoque-card-titulo">Unidades em estoque</span>
                <strong>{{ number_format($totalUnidades, 0, ',', '.') }}</strong>
                <small>Quantidade total registrada</small>
            </a>

            <a href="{{ route('compras.index', ['filtro' => 'baixo']) }}"
               class="estoque-card">
                <span class="estoque-card-titulo">Estoque baixo</span>
                <strong>{{ $totalEstoqueBaixo }}</strong>
                <small>Medicamentos com até 5 unidades</small>
            </a>

            <a href="{{ route('compras.index', ['filtro' => 'vencendo']) }}"
               class="estoque-card">
                <span class="estoque-card-titulo">Próximos do vencimento</span>
                <strong>{{ $lotesProximosVencimento }}</strong>
                <small>Lotes que vencem em até 30 dias</small>
            </a>

            <a href="{{ route('compras.index', ['filtro' => 'vencidos']) }}"
               class="estoque-card">
                <span class="estoque-card-titulo">Lotes vencidos</span>
                <strong>{{ $lotesVencidos }}</strong>
                <small>Lotes vencidos com estoque disponível</small>
            </a>

        </div>

        {{-- Pesquisa e filtros --}}
        <div class="estoque-consulta">

            <div class="section-header">
                <div>
                    <h2>Consulta de Medicamentos</h2>
                    <p>
                        Pesquise produtos e acompanhe a situação dos lotes.
                    </p>
                </div>
            </div>

            <form method="GET"
                  action="{{ route('compras.index') }}"
                  class="estoque-filtros">

                <input
                    type="text"
                    name="busca"
                    placeholder="Pesquisar medicamento ou lote..."
                    value="{{ request('busca') }}"
                    class="estoque-input"
                >

                <select name="filtro" class="estoque-select">
                    <option value="">Todos os lotes</option>

                    <option value="baixo"
                        @selected(request('filtro') === 'baixo')>
                        Estoque baixo
                    </option>

                    <option value="vencendo"
                        @selected(request('filtro') === 'vencendo')>
                        Próximos do vencimento
                    </option>

                    <option value="vencidos"
                        @selected(request('filtro') === 'vencidos')>
                        Vencidos
                    </option>
                </select>

                <button type="submit" class="btn-finalizar">
                    Pesquisar
                </button>

                <a href="{{ route('compras.index') }}"
                   class="estoque-limpar">
                    Limpar
                </a>

            </form>

            <div class="table-wrapper">
                <table class="sale-table">

                    <thead>
                        <tr>
                            <th>Lote</th>
                            <th>Medicamento</th>
                            <th>Fornecedor</th>
                            <th>Validade</th>
                            <th>Recebido</th>
                            <th>Disponível</th>
                            <th>Custo unitário</th>
                            <th>Situação</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($lotes as $lote)

                            @php
                                $validade = \Carbon\Carbon::parse(
                                    $lote->data_validade
                                )->startOfDay();

                                $hoje = now()->startOfDay();

                                $quantidade = (int) $lote->quantidade_atual;

                                if ($validade->lt($hoje)) {
                                    $situacao = 'Vencido';
                                    $classe = 'estoque-status-vencido';
                                } elseif (
                                    $validade->lte(
                                        $hoje->copy()->addDays(30)
                                    )
                                ) {
                                    $situacao = 'Próximo do vencimento';
                                    $classe = 'estoque-status-atencao';
                                } elseif ($quantidade === 0) {
                                    $situacao = 'Sem estoque';
                                    $classe = 'estoque-status-vencido';
                                } elseif ($quantidade <= 5) {
                                    $situacao = 'Quantidade baixa no lote';
                                    $classe = 'estoque-status-atencao';
                                } else {
                                    $situacao = 'Regular';
                                    $classe = 'estoque-status-regular';
                                }
                            @endphp

                            <tr>
                                <td>{{ $lote->numero_lote }}</td>

                                <td>{{ $lote->produto_nome }}</td>

                                <td>{{ $lote->fornecedor_nome }}</td>

                                <td>
                                    {{ $validade->format('d/m/Y') }}
                                </td>

                                <td>{{ $lote->quantidade_recebida }}</td>

                                <td>{{ $lote->quantidade_atual }}</td>

                                <td>
                                    R$
                                    {{ number_format(
                                        (float) $lote->preco_custo,
                                        2,
                                        ',',
                                        '.'
                                    ) }}
                                </td>

                                <td>
                                    <span class="estoque-status {{ $classe }}">
                                        {{ $situacao }}
                                    </span>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="8" class="empty-table">
                                    Nenhum lote encontrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            {{ $lotes->links() }}

        </div>

    </section>

</div>

<style>
    .estoque-indicadores {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin: 24px 0;
    }

    .estoque-card {
        display: flex;
        flex-direction: column;
        gap: 10px;
        padding: 22px;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        background: #fff;
        color: inherit;
        text-decoration: none;
        transition: 0.2s ease;
    }

    .estoque-card:hover {
        border-color: #7a1e3a;
        transform: translateY(-2px);
    }

    .estoque-card-titulo {
        font-size: 14px;
        font-weight: 600;
        color: #555;
    }

    .estoque-card strong {
        font-size: 30px;
        color: #7a1e3a;
    }

    .estoque-card small {
        font-size: 12px;
        color: #777;
    }

    .estoque-consulta {
        margin-top: 28px;
    }

    .estoque-filtros {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
        margin: 20px 0;
    }

    .estoque-input,
    .estoque-select {
        padding: 12px 14px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: #fff;
        font-size: 14px;
    }

    .estoque-input {
        flex: 1;
        min-width: 200px;
    }

    .estoque-select {
        min-width: 180px;
    }

    .estoque-limpar {
        color: #7a1e3a;
        text-decoration: none;
        font-weight: 600;
        padding: 10px;
    }

    .estoque-status {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }

    .estoque-status-regular {
        background: #dcfce7;
        color: #166534;
    }

    .estoque-status-atencao {
        background: #fef3c7;
        color: #92400e;
    }

    .estoque-status-vencido {
        background: #fee2e2;
        color: #991b1b;
    }

    @media (max-width: 1100px) {
        .estoque-indicadores {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 600px) {
        .estoque-indicadores {
            grid-template-columns: 1fr;
        }

        .estoque-filtros {
            flex-direction: column;
            align-items: stretch;
        }
    }
</style>

@endsection