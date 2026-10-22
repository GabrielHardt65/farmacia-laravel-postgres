@extends('layouts.app')

@section('title', 'Nova Venda - Sistema Farmácia')
@section('page-title', 'Nova Venda')

@section('content')

    <div class="venda-container">

        {{-- Informações da venda --}}
        <section class="venda-info">
            <div>
                <span class="venda-label">Funcionário</span>
                <strong>Funcionário logado</strong>
            </div>

            <div>
                <span class="venda-label">Venda</span>
                <strong>#0001</strong>
            </div>
        </section>

        {{-- Cliente --}}
        <section class="venda-section">
            <div class="section-header">
                <div>
                    <h2>Cliente</h2>
                    <p>Localize o cliente pelo CPF.</p>
                </div>
            </div>

            <div class="venda-search">
                <div class="venda-field">
                    <label for="cpf">CPF</label>
                    <input
                        type="text"
                        id="cpf"
                        placeholder="Digite o CPF do cliente"
                    >
                </div>

                <button type="button" class="btn-secondary">
                    Buscar cliente
                </button>

                <button type="button" class="btn-outline">
                    Novo cliente
                </button>
            </div>
        </section>

        {{-- Produtos --}}
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
                    <input
                        type="text"
                        id="produto"
                        placeholder="Código de barras ou nome do produto"
                    >
                </div>

                <button type="button" class="btn-secondary">
                    Buscar produto
                </button>

            </div>
        </section>

        {{-- Itens adicionados --}}
        <section class="venda-section">

            <div class="section-header">
                <div>
                    <h2>Itens da venda</h2>
                    <p>Produtos adicionados à venda atual.</p>
                </div>
            </div>

            <div class="venda-table-wrapper">
                <table class="venda-table">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Quantidade</th>
                            <th>Valor unitário</th>
                            <th>Subtotal</th>
                            <th>Ação</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr class="empty-row">
                            <td colspan="5">
                                Nenhum produto adicionado.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </section>

        {{-- Resumo --}}
        <section class="venda-resumo">

            <div class="venda-valores">
                <div>
                    <span>Subtotal</span>
                    <strong>R$ 0,00</strong>
                </div>

                <div>
                    <span>Desconto / Promoções</span>
                    <strong>R$ 0,00</strong>
                </div>

                <div class="venda-total">
                    <span>Total</span>
                    <strong>R$ 0,00</strong>
                </div>
            </div>

            <button type="button" class="btn-finalizar">
                Ir para pagamento
            </button>

        </section>

    </div>

@endsection