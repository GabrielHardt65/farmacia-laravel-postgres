@extends('layouts.app')

@section('title', 'Nova Compra - Sistema Farmácia')
@section('page-title', 'Nova Compra')

@section('content')

<div class="compra-form-container">

    <section class="compra-form-section">

        <div class="section-header">
            <div>
                <h2>Registrar Compra</h2>
                <p>Informe os dados da compra e os produtos recebidos.</p>
            </div>
        </div>

        <div class="compra-form-grid">

            <div class="compra-field">
                <label for="fornecedor">Fornecedor</label>
                <select id="fornecedor" name="fornecedor">
                    <option value="">Selecione um fornecedor</option>
                </select>
            </div>

            <div class="compra-field">
                <label for="data_compra">Data da compra</label>
                <input
                    type="date"
                    id="data_compra"
                    name="data_compra"
                >
            </div>

            <div class="compra-field">
                <label for="numero_nota">Número da nota fiscal</label>
                <input
                    type="text"
                    id="numero_nota"
                    name="numero_nota"
                    placeholder="Digite o número da nota"
                >
            </div>

        </div>

    </section>

    <section class="compra-form-section">

        <div class="section-header">
            <div>
                <h2>Adicionar Produto</h2>
                <p>Informe o produto recebido e os dados da entrada.</p>
            </div>
        </div>

        <div class="compra-produto-grid">

            <div class="compra-field compra-produto">
                <label for="produto">Produto</label>
                <input
                    type="text"
                    id="produto"
                    name="produto"
                    placeholder="Nome ou código do produto"
                >
            </div>

            <div class="compra-field">
                <label for="quantidade">Quantidade</label>
                <input
                    type="number"
                    id="quantidade"
                    name="quantidade"
                    min="1"
                    placeholder="0"
                >
            </div>

            <div class="compra-field">
                <label for="custo_unitario">Custo unitário</label>
                <input
                    type="number"
                    id="custo_unitario"
                    name="custo_unitario"
                    min="0"
                    step="0.01"
                    placeholder="R$ 0,00"
                >
            </div>

            <button type="button" class="btn-secondary">
                Adicionar
            </button>

        </div>

        <div class="table-wrapper">

            <table class="sale-table">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Custo Unitário</th>
                        <th>Subtotal</th>
                        <th>Ação</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td colspan="5" class="empty-table">
                            Nenhum produto adicionado.
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>

    </section>

    <section class="compra-form-section">

        <div class="compra-resumo">
            <span>Valor total da compra</span>
            <strong>R$ 0,00</strong>
        </div>

        <div class="compra-form-actions">

            <a href="{{ route('compras.index') }}" class="btn-outline">
                Cancelar
            </a>

            <button type="button" class="btn-finalizar">
                Registrar Compra
            </button>

        </div>

    </section>

</div>

@endsection