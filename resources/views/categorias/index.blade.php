@extends('layouts.app')

@section('title', 'Categorias - Sistema Farmácia')
@section('page-title', 'Categorias')

@section('content')

<div class="categorias-container">

    <section class="categorias-section">

        <div class="section-header">
            <div>
                <h2>Categorias</h2>
                <p>Consulte e gerencie as categorias dos produtos.</p>
            </div>

            <a href="{{ route('categorias.create') }}" class="btn-finalizar">
                Nova Categoria
            </a>
        </div>

        <div class="categorias-filtros">

            <div class="categorias-field">
                <label for="busca_categoria">Buscar categoria</label>
                <input
                    type="text"
                    id="busca_categoria"
                    name="busca_categoria"
                    placeholder="Digite o nome da categoria"
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
                        <th>Categoria</th>
                        <th>Descrição</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td colspan="4" class="empty-table">
                            Nenhuma categoria cadastrada.
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>

    </section>

</div>

@endsection