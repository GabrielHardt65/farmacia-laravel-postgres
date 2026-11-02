@extends('layouts.app')

@section('title', 'Relatórios - Sistema Farmácia')
@section('page-title', 'Relatórios')

@section('content')

<div class="relatorios-container">

    <section class="relatorios-section">

        <div class="section-header">
            <div>
                <h2>Relatórios</h2>
                <p>Consulte informações e resultados da farmácia.</p>
            </div>
        </div>

        <div class="relatorios-grid">

            <div class="relatorio-card">
                <div class="relatorio-card-header">
                    <h3>Relatório de Vendas</h3>
                </div>

                <p>
                    Consulte as vendas realizadas em um determinado período.
                </p>

                <div class="relatorio-filtros">
                    <div class="relatorio-field">
                        <label for="vendas_inicio">Data inicial</label>
                        <input type="date" id="vendas_inicio">
                    </div>

                    <div class="relatorio-field">
                        <label for="vendas_fim">Data final</label>
                        <input type="date" id="vendas_fim">
                    </div>
                </div>

                <button type="button" class="btn-secondary">
                    Gerar Relatório
                </button>
            </div>

            <div class="relatorio-card">
                <div class="relatorio-card-header">
                    <h3>Relatório de Estoque</h3>
                </div>

                <p>
                    Visualize a situação atual dos produtos em estoque.
                </p>

                <div class="relatorio-field">
                    <label for="estoque_tipo">Situação do estoque</label>

                    <select id="estoque_tipo">
                        <option value="todos">Todos os produtos</option>
                        <option value="baixo">Estoque baixo</option>
                        <option value="sem_estoque">Sem estoque</option>
                    </select>
                </div>

                <button type="button" class="btn-secondary">
                    Gerar Relatório
                </button>
            </div>

            <div class="relatorio-card">
                <div class="relatorio-card-header">
                    <h3>Relatório de Compras</h3>
                </div>

                <p>
                    Consulte as compras realizadas com fornecedores.
                </p>

                <div class="relatorio-filtros">
                    <div class="relatorio-field">
                        <label for="compras_inicio">Data inicial</label>
                        <input type="date" id="compras_inicio">
                    </div>

                    <div class="relatorio-field">
                        <label for="compras_fim">Data final</label>
                        <input type="date" id="compras_fim">
                    </div>
                </div>

                <button type="button" class="btn-secondary">
                    Gerar Relatório
                </button>
            </div>

            <div class="relatorio-card">
                <div class="relatorio-card-header">
                    <h3>Produtos com Validade Próxima</h3>
                </div>

                <p>
                    Consulte produtos que estão próximos da data de vencimento.
                </p>

                <div class="relatorio-field">
                    <label for="validade_dias">Período</label>

                    <select id="validade_dias">
                        <option value="30">Próximos 30 dias</option>
                        <option value="60">Próximos 60 dias</option>
                        <option value="90">Próximos 90 dias</option>
                    </select>
                </div>

                <button type="button" class="btn-secondary">
                    Gerar Relatório
                </button>
            </div>

        </div>

    </section>

</div>

@endsection