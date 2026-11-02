@extends('layouts.app')

@section('title', 'Receitas - Sistema Farmácia')
@section('page-title', 'Receitas')

@section('content')

<div class="receitas-lista-container">

    <section class="receitas-lista-section">

        <div class="section-header">
            <div>
                <h2>Receitas Médicas</h2>
                <p>Consulte e gerencie as receitas médicas cadastradas na farmácia.</p>
            </div>

            <a href="{{ route('receitas.create') }}" class="btn-finalizar">
                Nova Receita
            </a>
        </div>

        <div class="receitas-lista-filtros">

            <div class="receitas-lista-field">
                <label for="busca_receita">Buscar receita</label>
                <input
                    type="text"
                    id="busca_receita"
                    name="busca_receita"
                    placeholder="Nome do cliente, CPF ou número da receita"
                >
            </div>

            <div class="receitas-lista-field">
                <label for="data_receita">Data</label>
                <input
                    type="date"
                    id="data_receita"
                    name="data_receita"
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
                        <th>Número</th>
                        <th>Data</th>
                        <th>Cliente</th>
                        <th>CPF</th>
                        <th>Médico</th>
                        <th>Medicamento</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td colspan="7" class="empty-table">
                            Nenhuma receita cadastrada.
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>

    </section>

</div>

@endsection