@extends('layouts.app')

@section('title', 'Compras - Sistema Farmácia')
@section('page-title', 'Compras')

@section('content')

<div class="compras-container">

    <section class="compras-section">

        <div class="section-header">
            <div>
                <h2>Compras</h2>
                <p>Consulte e registre as entradas de produtos da farmácia.</p>
            </div>

            <a href="{{ route('compras.create') }}" class="btn-finalizar">
                Nova Compra
            </a>
        </div>

        <div class="compras-filtros">

            <div class="compras-field">
                <label for="busca_compra">Buscar compra</label>
                <input
                    type="text"
                    id="busca_compra"
                    name="busca_compra"
                    placeholder="Fornecedor ou número da compra"
                >
            </div>

            <div class="compras-field compras-data">
                <label for="data_compra">Data</label>
                <input
                    type="date"
                    id="data_compra"
                    name="data_compra"
                >
            </div>

            <button type="button" class="btn-secondary">
                Buscar
            </button>

        </div>

        <div class="table-wrapper">

            <table class="sale-table">
                <thead>
                    <tr>
                        <th>Nº</th>
                        <th>Data</th>
                        <th>Fornecedor</th>
                        <th>Itens</th>
                        <th>Valor Total</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td colspan="6" class="empty-table">
                            Nenhuma compra registrada.
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>

    </section>

</div>

@endsection