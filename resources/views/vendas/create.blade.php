@extends('layouts.app')

@section('title', 'Nova Venda - Sistema Farmácia')
@section('page-title', 'Nova Venda')

@section('content')
<div class="venda-container" id="pagina-venda"
    data-api-clientes="{{ url('/api/clientes/buscar') }}"
    data-api-produtos="{{ url('/api/produtos/buscar') }}"
    data-api-vendas="{{ url('/api/vendas') }}"
    data-url-pagamento="{{ route('pagamentos.create') }}"
    data-url-novo-cliente="{{ route('clientes.create') }}">

    <section class="venda-info">
        <div>
            <span class="venda-label">Funcionário</span>
            <strong>Funcionário 1</strong>
        </div>
        <div>
            <span class="venda-label">Venda</span>
            <strong id="numero-venda">Ainda não iniciada</strong>
        </div>
    </section>

    <section class="venda-section">
        <div class="section-header">
            <div>
                <h2>Cliente</h2>
                <p>Localize o cliente pelo CPF. A venda também pode ser feita sem cliente.</p>
            </div>
        </div>

        <div class="venda-search">
            <div class="venda-field">
                <label for="cpf">CPF</label>
                <input type="text" id="cpf" placeholder="Digite o CPF do cliente">
            </div>
            <button type="button" class="btn-secondary" id="buscar-cliente">Buscar cliente</button>
            <a href="{{ route('clientes.create') }}" class="btn-outline">Novo cliente</a>
        </div>

        <p id="resultado-cliente" aria-live="polite">Nenhum cliente selecionado.</p>
    </section>

    <section class="venda-section">
        <div class="section-header">
            <div>
                <h2>Produtos</h2>
                <p>Busque pelo código de barras ou nome do produto.</p>
            </div>
        </div>

        <div class="venda-search">
            <div class="venda-field venda-field-large">
                <label for="produto">Produto</label>
                <input type="text" id="produto" placeholder="Código de barras ou nome do produto">
            </div>
            <button type="button" class="btn-secondary" id="buscar-produto">Buscar produto</button>
        </div>

        <div class="venda-field" style="margin-top: 15px;">
            <label for="resultado-produtos">Produto e lote disponíveis</label>
            <select id="resultado-produtos">
                <option value="">Pesquise um produto primeiro</option>
            </select>
        </div>

        <div class="venda-search" style="margin-top: 15px;">
            <div class="venda-field">
                <label for="quantidade-produto">Quantidade</label>
                <input type="number" id="quantidade-produto" min="1" value="1">
            </div>
            <button type="button" class="btn-secondary" id="adicionar-produto">
                Adicionar à venda
            </button>
        </div>
    </section>

    <p id="mensagem-venda" role="status" aria-live="polite"></p>

    <section class="venda-section">
        <div class="section-header">
            <div>
                <h2>Itens da venda</h2>
                <p>Produtos já registrados na venda.</p>
            </div>
        </div>

        <div class="venda-table-wrapper">
            <table class="venda-table">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Lote</th>
                        <th>Quantidade</th>
                        <th>Valor unitário</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody id="itens-venda">
                    <tr class="empty-row">
                        <td colspan="5">Nenhum produto adicionado.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <section class="venda-resumo">
        <div class="venda-valores">
            <div>
                <span>Subtotal</span>
                <strong id="subtotal-venda">R$ 0,00</strong>
            </div>
            <div>
                <span>Descontos</span>
                <strong id="desconto-venda">Calculados ao finalizar</strong>
            </div>
            <div class="venda-total">
                <span>Total parcial</span>
                <strong id="total-venda">R$ 0,00</strong>
            </div>
        </div>

        <a href="{{ route('pagamentos.create') }}"
           class="btn-finalizar"
           id="ir-pagamento"
           style="pointer-events:none; opacity:.5;">
            Ir para pagamento
        </a>
    </section>
</div>
@endsection