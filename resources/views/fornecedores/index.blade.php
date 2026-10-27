@extends('layouts.app')

@section('title', 'Fornecedores - Sistema Farmácia')
@section('page-title', 'Fornecedores')

@section('content')

<div class="fornecedores-container">

    <section class="fornecedores-section">

        <div class="section-header">
            <div>
                <h2>Fornecedores</h2>
                <p>Consulte e gerencie os fornecedores da farmácia.</p>
            </div>

            <a href="{{ route('fornecedores.create') }}" class="btn-finalizar">
                Novo Fornecedor
            </a>
        </div>

        <div class="fornecedores-filtros">

            <div class="fornecedores-field">
                <label for="busca_fornecedor">Buscar fornecedor</label>
                <input
                    type="text"
                    id="busca_fornecedor"
                    name="busca_fornecedor"
                    placeholder="Nome, CNPJ ou telefone"
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
                        <th>Fornecedor</th>
                        <th>CNPJ</th>
                        <th>Telefone</th>
                        <th>E-mail</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td colspan="6" class="empty-table">
                            Nenhum fornecedor cadastrado.
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>

    </section>

</div>

@endsection