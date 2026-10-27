@extends('layouts.app')

@section('title', 'Funcionários - Sistema Farmácia')
@section('page-title', 'Funcionários')

@section('content')

<div class="funcionarios-container">

    <section class="funcionarios-section">

        <div class="section-header">
            <div>
                <h2>Funcionários</h2>
                <p>Consulte e gerencie os funcionários cadastrados na farmácia.</p>
            </div>

            <a href="{{ route('funcionarios.create') }}" class="btn-finalizar">
                Novo Funcionário
            </a>
        </div>

        <div class="funcionarios-filtros">

            <div class="funcionarios-field">
                <label for="busca_funcionario">Buscar funcionário</label>
                <input
                    type="text"
                    id="busca_funcionario"
                    name="busca_funcionario"
                    placeholder="Nome, CPF ou cargo"
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
                        <th>Cargo</th>
                        <th>Telefone</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td colspan="6" class="empty-table">
                            Nenhum funcionário cadastrado.
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>

    </section>

</div>

@endsection