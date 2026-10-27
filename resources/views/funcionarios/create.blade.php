@extends('layouts.app')

@section('title', 'Novo Funcionário - Sistema Farmácia')
@section('page-title', 'Novo Funcionário')

@section('content')

<div class="funcionario-form-container">

    <section class="funcionario-form-section">

        <div class="section-header">
            <div>
                <h2>Cadastro de Funcionário</h2>
                <p>Preencha os dados para cadastrar um novo funcionário.</p>
            </div>
        </div>

        <div class="funcionario-form-grid">

            <div class="funcionario-field">
                <label for="nome">Nome completo</label>
                <input
                    type="text"
                    id="nome"
                    name="nome"
                    placeholder="Digite o nome completo"
                >
            </div>

            <div class="funcionario-field">
                <label for="cpf">CPF</label>
                <input
                    type="text"
                    id="cpf"
                    name="cpf"
                    placeholder="000.000.000-00"
                >
            </div>

            <div class="funcionario-field">
                <label for="data_nascimento">Data de nascimento</label>
                <input
                    type="date"
                    id="data_nascimento"
                    name="data_nascimento"
                >
            </div>

            <div class="funcionario-field">
                <label for="telefone">Telefone</label>
                <input
                    type="text"
                    id="telefone"
                    name="telefone"
                    placeholder="(00) 00000-0000"
                >
            </div>

            <div class="funcionario-field">
                <label for="email">E-mail</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="funcionario@email.com"
                >
            </div>

            <div class="funcionario-field">
                <label for="cargo">Cargo</label>
                <select id="cargo" name="cargo">
                    <option value="">Selecione o cargo</option>
                    <option value="farmaceutico">Farmacêutico</option>
                    <option value="atendente">Atendente</option>
                    <option value="caixa">Caixa</option>
                    <option value="gerente">Gerente</option>
                    <option value="administrativo">Administrativo</option>
                </select>
            </div>

            <div class="funcionario-field">
                <label for="data_admissao">Data de admissão</label>
                <input
                    type="date"
                    id="data_admissao"
                    name="data_admissao"
                >
            </div>

            <div class="funcionario-field">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="ativo">Ativo</option>
                    <option value="inativo">Inativo</option>
                </select>
            </div>

        </div>

        <div class="funcionario-field funcionario-field-full">
            <label for="endereco">Endereço</label>
            <input
                type="text"
                id="endereco"
                name="endereco"
                placeholder="Rua, número e complemento"
            >
        </div>

        <div class="funcionario-form-actions">

            <a href="{{ route('funcionarios.index') }}" class="btn-outline">
                Cancelar
            </a>

            <button type="button" class="btn-finalizar">
                Salvar Funcionário
            </button>

        </div>

    </section>

</div>

@endsection