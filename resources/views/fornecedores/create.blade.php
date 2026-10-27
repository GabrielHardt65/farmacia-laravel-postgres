@extends('layouts.app')

@section('title', 'Novo Fornecedor - Sistema Farmácia')
@section('page-title', 'Novo Fornecedor')

@section('content')

<div class="fornecedor-form-container">

    <section class="fornecedor-form-section">

        <div class="section-header">
            <div>
                <h2>Cadastro de Fornecedor</h2>
                <p>Preencha os dados para cadastrar um novo fornecedor.</p>
            </div>
        </div>

        <div class="fornecedor-form-grid">

            <div class="fornecedor-field">
                <label for="razao_social">Razão Social</label>
                <input
                    type="text"
                    id="razao_social"
                    name="razao_social"
                    placeholder="Digite a razão social"
                >
            </div>

            <div class="fornecedor-field">
                <label for="nome_fantasia">Nome Fantasia</label>
                <input
                    type="text"
                    id="nome_fantasia"
                    name="nome_fantasia"
                    placeholder="Digite o nome fantasia"
                >
            </div>

            <div class="fornecedor-field">
                <label for="cnpj">CNPJ</label>
                <input
                    type="text"
                    id="cnpj"
                    name="cnpj"
                    placeholder="00.000.000/0000-00"
                >
            </div>

            <div class="fornecedor-field">
                <label for="telefone">Telefone</label>
                <input
                    type="text"
                    id="telefone"
                    name="telefone"
                    placeholder="(00) 00000-0000"
                >
            </div>

            <div class="fornecedor-field">
                <label for="email">E-mail</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="fornecedor@email.com"
                >
            </div>

            <div class="fornecedor-field">
                <label for="cidade">Cidade</label>
                <input
                    type="text"
                    id="cidade"
                    name="cidade"
                    placeholder="Digite a cidade"
                >
            </div>

            <div class="fornecedor-field">
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

        <div class="fornecedor-field fornecedor-field-full">
            <label for="endereco">Endereço</label>
            <input
                type="text"
                id="endereco"
                name="endereco"
                placeholder="Rua, número e complemento"
            >
        </div>

        <div class="fornecedor-form-actions">

            <a href="{{ route('fornecedores.index') }}" class="btn-outline">
                Cancelar
            </a>

            <button type="button" class="btn-finalizar">
                Salvar Fornecedor
            </button>

        </div>

    </section>

</div>

@endsection