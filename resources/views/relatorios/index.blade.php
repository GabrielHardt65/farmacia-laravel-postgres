
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

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $erro)
                    <p>{{ $erro }}</p>
                @endforeach
            </div>
        @endif

        <div class="relatorios-grid">

            {{-- RELATÓRIO DE VENDAS --}}
            <div class="relatorio-card">
                <div class="relatorio-card-header">
                    <h3>Relatório de Vendas</h3>
                </div>

                <p>Consulte as vendas realizadas em um determinado período.</p>

                <form method="GET" action="{{ route('relatorios.index') }}">
                    <input type="hidden" name="tipo" value="vendas">

                    <div class="relatorio-filtros">
                        <div class="relatorio-field">
                            <label for="vendas_inicio">Data inicial</label>
                            <input type="date" id="vendas_inicio"
                                   name="inicio" value="{{ request('inicio') }}">
                        </div>

                        <div class="relatorio-field">
                            <label for="vendas_fim">Data final</label>
                            <input type="date" id="vendas_fim"
                                   name="fim" value="{{ request('fim') }}">
                        </div>
                    </div>

                    <button type="submit" class="btn-secondary">
                        Gerar Relatório
                    </button>
                </form>
            </div>

            {{-- RELATÓRIO DE ESTOQUE --}}
            <div class="relatorio-card">
                <div class="relatorio-card-header">
                    <h3>Relatório de Estoque</h3>
                </div>

                <p>Visualize a situação atual dos produtos em estoque.</p>

                <form method="GET" action="{{ route('relatorios.index') }}">
                    <input type="hidden" name="tipo" value="estoque">

                    <div class="relatorio-field">
                        <label for="estoque_tipo">Situação do estoque</label>

                        <select id="estoque_tipo" name="situacao">
                            <option value="todos" @selected(request('situacao', 'todos') === 'todos')>
                                Todos os produtos
                            </option>
                            <option value="baixo" @selected(request('situacao') === 'baixo')>
                                Estoque baixo (1 a 10)
                            </option>
                            <option value="sem_estoque" @selected(request('situacao') === 'sem_estoque')>
                                Sem estoque
                            </option>
                        </select>
                    </div>

                    <button type="submit" class="btn-secondary">
                        Gerar Relatório
                    </button>
                </form>
            </div>

            {{-- RELATÓRIO DE COMPRAS --}}
            <div class="relatorio-card">
                <div class="relatorio-card-header">
                    <h3>Relatório de Compras</h3>
                </div>

                <p>
                    Consulte as entradas de mercadorias registradas nos lotes.
                    O banco não armazena a data da compra.
                </p>

                <form method="GET" action="{{ route('relatorios.index') }}">
                    <input type="hidden" name="tipo" value="compras">

                    <button type="submit" class="btn-secondary">
                        Gerar Relatório
                    </button>
                </form>
            </div>

            {{-- RELATÓRIO DE VALIDADE --}}
            <div class="relatorio-card">
                <div class="relatorio-card-header">
                    <h3>Produtos com Validade Próxima</h3>
                </div>

                <p>Consulte produtos próximos da data de vencimento.</p>

                <form method="GET" action="{{ route('relatorios.index') }}">
                    <input type="hidden" name="tipo" value="validade">

                    <div class="relatorio-field">
                        <label for="validade_dias">Período</label>

                        <select id="validade_dias" name="dias">
                            <option value="30" @selected(request('dias', '30') === '30')>
                                Próximos 30 dias
                            </option>
                            <option value="60" @selected(request('dias') === '60')>
                                Próximos 60 dias
                            </option>
                            <option value="90" @selected(request('dias') === '90')>
                                Próximos 90 dias
                            </option>
                        </select>
                    </div>

                    <button type="submit" class="btn-secondary">
                        Gerar Relatório
                    </button>
                </form>
            </div>

        </div>
    </section>

    {{-- RESULTADOS --}}
    @if (request()->filled('tipo'))

        <section class="relatorios-section" style="margin-top: 30px;">

            <div class="section-header">
                <div>
                    <h2>Resultado do relatório</h2>
                    <p>Dados consultados diretamente no PostgreSQL.</p>
                </div>
            </div>

            @if ($dados->isEmpty())
                <p>Nenhum registro encontrado para os filtros selecionados.</p>
            @else

                <div style="overflow-x: auto;">

                    {{-- VENDAS --}}
                    @if ($tipo === 'vendas')
                        <table>
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Data</th>
                                    <th>Valor total</th>
                                    <th>Desconto</th>
                                    <th>Parcelas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dados as $venda)
                                    <tr>
                                        <td>{{ $venda->id_venda }}</td>
                                        <td>{{ \Carbon\Carbon::parse($venda->data_venda)->format('d/m/Y H:i') }}</td>
                                        <td>R$ {{ number_format($venda->valor_total, 2, ',', '.') }}</td>
                                        <td>R$ {{ number_format($venda->desconto_aplicado, 2, ',', '.') }}</td>
                                        <td>{{ $venda->parcelas }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                    {{-- ESTOQUE --}}
                    @if ($tipo === 'estoque')
                        <table>
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Produto</th>
                                    <th>Quantidade em estoque</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dados as $produto)
                                    <tr>
                                        <td>{{ $produto->id_produto }}</td>
                                        <td>{{ $produto->nome }}</td>
                                        <td>{{ $produto->estoque_atual }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                    {{-- COMPRAS --}}
                    @if ($tipo === 'compras')
                        <table>
                            <thead>
                                <tr>
                                    <th>Lote</th>
                                    <th>Produto</th>
                                    <th>Fornecedor</th>
                                    <th>Número do lote</th>
                                    <th>Fabricação</th>
                                    <th>Validade</th>
                                    <th>Quantidade recebida</th>
                                    <th>Quantidade atual</th>
                                    <th>Custo unitário</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dados as $lote)
                                    <tr>
                                        <td>{{ $lote->id_lote }}</td>
                                        <td>{{ $lote->id_produto }}</td>
                                        <td>{{ $lote->id_fornecedor }}</td>
                                        <td>{{ $lote->numero_lote }}</td>
                                        <td>{{ \Carbon\Carbon::parse($lote->data_fabricacao)->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($lote->data_validade)->format('d/m/Y') }}</td>
                                        <td>{{ $lote->quantidade_recebida }}</td>
                                        <td>{{ $lote->quantidade_atual }}</td>
                                        <td>R$ {{ number_format($lote->preco_custo, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                    {{-- VALIDADE --}}
                    @if ($tipo === 'validade')
                        <table>
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Lote</th>
                                    <th>Validade</th>
                                    <th>Quantidade atual</th>
                                    <th>Fornecedor</th>
                                    <th>Dias para vencer</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dados as $lote)
                                    <tr>
                                        <td>{{ $lote->produto }}</td>
                                        <td>{{ $lote->numero_lote }}</td>
                                        <td>{{ \Carbon\Carbon::parse($lote->data_validade)->format('d/m/Y') }}</td>
                                        <td>{{ $lote->quantidade_atual }}</td>
                                        <td>{{ $lote->fornecedor }}</td>
                                        <td>{{ $lote->dias_para_vencer }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                </div>

                <p style="margin-top: 15px;">
                    Total de registros encontrados: <strong>{{ $dados->count() }}</strong>
                </p>

            @endif

        </section>

    @endif

</div>

@endsection
