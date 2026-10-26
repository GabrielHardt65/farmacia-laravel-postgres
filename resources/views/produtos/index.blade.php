@extends('layouts.app')

@section('title', 'Produtos - Sistema Farmácia')
@section('page-title', 'Produtos')

@section('content')

<div class="produtos-container">

    <section class="produtos-section">

        <div class="section-header">
            <div>
                <h2>Produtos</h2>
                <p>Consulte e gerencie os produtos cadastrados na farmácia.</p>
            </div>

            <a href="{{ route('produtos.create') }}" class="btn-finalizar">
                Novo Produto
            </a>
        </div>

        <div class="produtos-filtros">

            <div class="produtos-field">
                <label for="busca_produto">Buscar produto</label>
                <input
                    type="text"
                    id="busca_produto"
                    name="busca_produto"
                    placeholder="Nome, código ou código de barras"
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
                        <th>Código</th>
                        <th>Produto</th>
                        <th>Categoria</th>
                        <th>Estoque</th>
                        <th>Preço</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td colspan="6" class="empty-table">
                            Nenhum produto cadastrado.
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>

    </section>

</div>

@endsection