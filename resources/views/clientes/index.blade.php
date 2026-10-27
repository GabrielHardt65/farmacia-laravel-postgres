@extends('layouts.app')

@section('title', 'Clientes - Sistema Farmácia')
@section('page-title', 'Clientes')

@section('content')

<div class="clientes-container">

    <section class="clientes-section">

        <div class="section-header">
            <div>
                <h2>Clientes</h2>
                <p>Consulte e gerencie os clientes cadastrados na farmácia.</p>
            </div>

            <a href="{{ route('clientes.create') }}" class="btn-finalizar">
                Novo Cliente
            </a>
        </div>

        <div class="clientes-filtros">

            <div class="clientes-field">
                <label for="busca_cliente">Buscar cliente</label>
                <input
                    type="text"
                    id="busca_cliente"
                    name="busca_cliente"
                    placeholder="Nome, CPF ou telefone"
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
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Telefone</th>
                        <th>E-mail</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td colspan="6" class="empty-table">
                            Nenhum cliente cadastrado.
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>

    </section>

</div>

@endsection