@extends('layouts.app')

@section('title', 'Novo Cliente - Sistema Farmácia')
@section('page-title', 'Novo Cliente')

@section('content')

<div class="cliente-form-container">

    <section class="cliente-form-section">

        <div class="section-header">
            <div>
                <h2>Cadastro de Cliente</h2>
                <p>Preencha os dados para cadastrar um novo cliente.</p>
            </div>
        </div>

        <div class="cliente-form-grid">

            <div class="cliente-field">
                <label for="nome">Nome completo</label>
                <input
                    type="text"
                    id="nome"
                    name="nome"
                    placeholder="Digite o nome completo"
                >
            </div>

            <div class="cliente-field">
                <label for="cpf">CPF</label>
                <input
                    type="text"
                    id="cpf"
                    name="cpf"
                    placeholder="000.000.000-00"
                >
            </div>

            <div class="cliente-field">
                <label for="data_nascimento">Data de nascimento</label>
                <input
                    type="date"
                    id="data_nascimento"
                    name="data_nascimento"
                >
            </div>

            <div class="cliente-field">
                <label for="telefone">Telefone</label>
                <input
                    type="text"
                    id="telefone"
                    name="telefone"
                    placeholder="(00) 00000-0000"
                >
            </div>

            <div class="cliente-field">
                <label for="email">E-mail</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="cliente@email.com"
                >
            </div>

            <div class="cliente-field">
                <label for="cidade">Cidade</label>
                <input
                    type="text"
                    id="cidade"
                    name="cidade"
                    placeholder="Digite a cidade"
                >
            </div>

            <div class="cliente-field">
                <label for="estado">Estado</label>
                <input
                    type="text"
                    id="estado"
                    name="estado"
                    maxlength="2"
                    placeholder="PR"
                >
            </div>

        </div>

        <div class="cliente-field cliente-field-full">
            <label for="endereco">Endereço</label>
            <input
                type="text"
                id="endereco"
                name="endereco"
                placeholder="Rua, número e complemento"
            >
        </div>

        <div class="cliente-form-actions">

            <a href="{{ route('clientes.index') }}" class="btn-outline">
                Cancelar
            </a>

            <button type="button" class="btn-finalizar">
                Salvar Cliente
            </button>

        </div>

    </section>

</div>

@endsection