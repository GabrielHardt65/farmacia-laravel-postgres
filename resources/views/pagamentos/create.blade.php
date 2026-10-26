@extends('layouts.app')

@section('title', 'Pagamento - Sistema Farmácia')
@section('page-title', 'Pagamento')

@section('content')

    <div class="pagamento-container">

        {{-- Resumo da venda --}}
        <section class="pagamento-section">

            <div class="section-header">
                <div>
                    <h2>Resumo da Venda</h2>
                    <p>Confira os dados antes de finalizar o pagamento.</p>
                </div>
            </div>

            <div class="pagamento-info">

                <div>
                    <span>Venda</span>
                    <strong>#0001</strong>
                </div>

                <div>
                    <span>Cliente</span>
                    <strong>Cliente não informado</strong>
                </div>

                <div>
                    <span>Funcionário</span>
                    <strong>Funcionário logado</strong>
                </div>

            </div>

        </section>


        {{-- Valores --}}
        <section class="pagamento-section">

            <div class="section-header">
                <div>
                    <h2>Valores</h2>
                    <p>Resumo dos valores da venda.</p>
                </div>
            </div>

            <div class="pagamento-valores">

                <div>
                    <span>Subtotal</span>
                    <strong>R$ 0,00</strong>
                </div>

                <div>
                    <span>Descontos</span>
                    <strong>R$ 0,00</strong>
                </div>

                <div class="pagamento-total">
                    <span>Total a pagar</span>
                    <strong>R$ 0,00</strong>
                </div>

            </div>

        </section>


        {{-- Forma de pagamento --}}
        <section class="pagamento-section">

            <div class="section-header">
                <div>
                    <h2>Forma de Pagamento</h2>
                    <p>Selecione como o cliente realizará o pagamento.</p>
                </div>
            </div>

            <div class="pagamento-opcoes">

                <label class="pagamento-option">
                    <input
                        type="radio"
                        name="forma_pagamento"
                        value="dinheiro"
                    >
                    <span>Dinheiro</span>
                </label>

                <label class="pagamento-option">
                    <input
                        type="radio"
                        name="forma_pagamento"
                        value="pix"
                    >
                    <span>PIX</span>
                </label>

                <label class="pagamento-option">
                    <input
                        type="radio"
                        name="forma_pagamento"
                        value="debito"
                    >
                    <span>Cartão de Débito</span>
                </label>

                <label class="pagamento-option">
                    <input
                        type="radio"
                        name="forma_pagamento"
                        value="credito"
                    >
                    <span>Cartão de Crédito</span>
                </label>

            </div>

        </section>


        {{-- Valor recebido --}}
        <section class="pagamento-section">

            <div class="section-header">
                <div>
                    <h2>Pagamento</h2>
                    <p>Informe o valor recebido quando necessário.</p>
                </div>
            </div>

            <div class="pagamento-grid">

                <div class="pagamento-field">
                    <label for="valor_recebido">Valor recebido</label>

                    <input
                        type="number"
                        id="valor_recebido"
                        name="valor_recebido"
                        step="0.01"
                        min="0"
                        placeholder="R$ 0,00"
                    >
                </div>

                <div class="pagamento-field">
                    <label for="troco">Troco</label>

                    <input
                        type="text"
                        id="troco"
                        value="R$ 0,00"
                        readonly
                    >
                </div>

            </div>

        </section>


        {{-- Botões --}}
        <div class="pagamento-actions">

            <a href="{{ route('vendas.create') }}" class="btn-outline">
                Voltar
            </a>

            <a href="{{ route('comprovantes.show') }}" class="btn-finalizar">
                Finalizar Pagamento
            </a>

        </div>

    </div>

@endsection