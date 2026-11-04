@extends('layouts.app')

@section('title', 'Relatórios - Sistema Farmácia')
@section('page-title', 'Relatórios')

@section('content')
<div class="relatorios-container relatorios-painel">
    <section class="relatorios-section">
        <div class="section-header relatorios-cabecalho">
            <div>
                <h2>Relatórios da Farmácia</h2>
                <p>Consulte indicadores e resultados registrados no sistema.</p>
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
            <article class="relatorio-card">
                <div class="relatorio-card-header"><span class="relatorio-icone">↗</span><h3>Vendas</h3></div>
                <p>Consulte vendas realizadas em um período.</p>
                <form method="GET" action="{{ route('relatorios.index') }}">
                    <input type="hidden" name="tipo" value="vendas">
                    <div class="relatorio-filtros">
                        <div class="relatorio-field">
                            <label for="vendas_inicio">Data inicial</label>
                            <input type="date" id="vendas_inicio" name="inicio" value="{{ request('tipo') === 'vendas' ? request('inicio') : '' }}">
                        </div>
                        <div class="relatorio-field">
                            <label for="vendas_fim">Data final</label>
                            <input type="date" id="vendas_fim" name="fim" value="{{ request('tipo') === 'vendas' ? request('fim') : '' }}">
                        </div>
                    </div>
                    <button type="submit" class="relatorio-botao">Gerar relatório</button>
                </form>
            </article>

            <article class="relatorio-card">
                <div class="relatorio-card-header"><span class="relatorio-icone">▤</span><h3>Estoque</h3></div>
                <p>Analise a quantidade de produtos em estoque.</p>
                <form method="GET" action="{{ route('relatorios.index') }}">
                    <input type="hidden" name="tipo" value="estoque">
                    <div class="relatorio-field">
                        <label for="estoque_tipo">Situação do estoque</label>
                        <select id="estoque_tipo" name="situacao">
                            <option value="todos" @selected(request('situacao', 'todos') === 'todos')>Todos os produtos</option>
                            <option value="baixo" @selected(request('situacao') === 'baixo')>Estoque baixo</option>
                            <option value="sem_estoque" @selected(request('situacao') === 'sem_estoque')>Sem estoque</option>
                        </select>
                    </div>
                    <button type="submit" class="relatorio-botao">Gerar relatório</button>
                </form>
            </article>

            <article class="relatorio-card">
                <div class="relatorio-card-header"><span class="relatorio-icone">＋</span><h3>Entradas de mercadorias</h3></div>
                <p>Consulte os lotes recebidos e os respectivos custos. Não há data de compra registrada no banco.</p>
                <form method="GET" action="{{ route('relatorios.index') }}">
                    <input type="hidden" name="tipo" value="compras">
                    <button type="submit" class="relatorio-botao">Gerar relatório</button>
                </form>
            </article>

            <article class="relatorio-card">
                <div class="relatorio-card-header"><span class="relatorio-icone">◷</span><h3>Validade de lotes</h3></div>
                <p>Identifique lotes com vencimento próximo.</p>
                <form method="GET" action="{{ route('relatorios.index') }}">
                    <input type="hidden" name="tipo" value="validade">
                    <div class="relatorio-field">
                        <label for="validade_dias">Período</label>
                        <select id="validade_dias" name="dias">
                            <option value="30" @selected(request('dias', '30') === '30')>Próximos 30 dias</option>
                            <option value="60" @selected(request('dias') === '60')>Próximos 60 dias</option>
                            <option value="90" @selected(request('dias') === '90')>Próximos 90 dias</option>
                        </select>
                    </div>
                    <button type="submit" class="relatorio-botao">Gerar relatório</button>
                </form>
            </article>
        </div>
    </section>

    @if (request()->filled('tipo'))
        <section class="relatorios-section relatorios-resultado" id="resultado-relatorio">
            <div class="relatorios-resultado-topo">
                <div>
                    <h2>Resultado do relatório</h2>
                    <p>Dados consultados diretamente no PostgreSQL.</p>
                </div>
                <span class="relatorios-total">{{ $dados->count() }} {{ $dados->count() === 1 ? 'registro' : 'registros' }}</span>
            </div>

            @if ($dados->isEmpty())
                <div class="relatorios-vazio">Nenhum registro encontrado para os filtros selecionados.</div>
            @else
                <div class="relatorios-tabela-wrapper">
                    @if ($tipo === 'vendas')
                        <table class="relatorios-tabela">
                            <thead><tr><th>Código</th><th>Data</th><th>Valor total</th><th>Desconto</th><th>Parcelas</th></tr></thead>
                            <tbody>
                                @foreach ($dados as $venda)
                                    <tr>
                                        <td class="relatorios-codigo">#{{ $venda->id_venda }}</td>
                                        <td>{{ \Carbon\Carbon::parse($venda->data_venda)->format('d/m/Y H:i') }}</td>
                                        <td>R$ {{ number_format((float) $venda->valor_total, 2, ',', '.') }}</td>
                                        <td>R$ {{ number_format((float) $venda->desconto_aplicado, 2, ',', '.') }}</td>
                                        <td>{{ $venda->parcelas }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @elseif ($tipo === 'estoque')
                        <table class="relatorios-tabela">
                            <thead><tr><th>Código</th><th>Produto</th><th>Quantidade em estoque</th></tr></thead>
                            <tbody>
                                @foreach ($dados as $produto)
                                    <tr>
                                        <td class="relatorios-codigo">#{{ $produto->id_produto }}</td>
                                        <td>{{ $produto->nome }}</td>
                                        <td><span class="relatorios-quantidade">{{ number_format((float) $produto->estoque_atual, 0, ',', '.') }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @elseif ($tipo === 'compras')
                        <table class="relatorios-tabela">
                            <thead><tr><th>Código do lote</th><th>Código do produto</th><th>Código do fornecedor</th><th>Número do lote</th><th>Fabricação</th><th>Validade</th><th>Recebido</th><th>Atual</th><th>Custo unitário</th></tr></thead>
                            <tbody>
                                @foreach ($dados as $lote)
                                    <tr>
                                        <td class="relatorios-codigo">#{{ $lote->id_lote }}</td>
                                        <td>{{ $lote->id_produto }}</td>
                                        <td>{{ $lote->id_fornecedor }}</td>
                                        <td>{{ $lote->numero_lote }}</td>
                                        <td>{{ \Carbon\Carbon::parse($lote->data_fabricacao)->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($lote->data_validade)->format('d/m/Y') }}</td>
                                        <td>{{ $lote->quantidade_recebida }}</td>
                                        <td>{{ $lote->quantidade_atual }}</td>
                                        <td>R$ {{ number_format((float) $lote->preco_custo, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @elseif ($tipo === 'validade')
                        <table class="relatorios-tabela">
                            <thead><tr><th>Produto</th><th>Lote</th><th>Validade</th><th>Quantidade atual</th><th>Fornecedor</th><th>Dias para vencer</th></tr></thead>
                            <tbody>
                                @foreach ($dados as $lote)
                                    <tr>
                                        <td>{{ $lote->produto }}</td>
                                        <td class="relatorios-codigo">{{ $lote->numero_lote }}</td>
                                        <td>{{ \Carbon\Carbon::parse($lote->data_validade)->format('d/m/Y') }}</td>
                                        <td>{{ $lote->quantidade_atual }}</td>
                                        <td>{{ $lote->fornecedor }}</td>
                                        <td><span class="relatorios-quantidade">{{ $lote->dias_para_vencer }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            @endif
        </section>
    @endif
</div>
@endsection
