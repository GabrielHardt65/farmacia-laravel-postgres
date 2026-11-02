@extends('layouts.app')

@section('title', 'Cadastrar Receita - Sistema Farmácia')
@section('page-title', 'Cadastrar Receita')

@section('content')

    <div class="receita-container">

        {{-- Informações do cliente --}}
        <section class="receita-section">

            <div class="section-header">
                <div>
                    <h2>Cliente</h2>
                    <p>Informe os dados do cliente relacionado à receita.</p>
                </div>
            </div>

            <div class="receita-grid">

                <div class="receita-field">
                    <label for="cpf_cliente">CPF do cliente</label>
                    <input
                        type="text"
                        id="cpf_cliente"
                        name="cpf_cliente"
                        placeholder="Digite o CPF"
                    >
                </div>

                <div class="receita-field">
                    <label for="nome_cliente">Nome do cliente</label>
                    <input
                        type="text"
                        id="nome_cliente"
                        name="nome_cliente"
                        placeholder="Nome do cliente"
                    >
                </div>

            </div>

        </section>


        {{-- Informações da receita --}}
        <section class="receita-section">

            <div class="section-header">
                <div>
                    <h2>Dados da Receita</h2>
                    <p>Preencha as informações da prescrição médica.</p>
                </div>
            </div>

            <div class="receita-grid">

                <div class="receita-field">
                    <label for="numero_receita">Número da receita</label>
                    <input
                        type="text"
                        id="numero_receita"
                        name="numero_receita"
                        placeholder="Número da receita"
                    >
                </div>

                <div class="receita-field">
                    <label for="data_receita">Data da receita</label>
                    <input
                        type="date"
                        id="data_receita"
                        name="data_receita"
                    >
                </div>

                <div class="receita-field">
                    <label for="medico">Médico</label>
                    <input
                        type="text"
                        id="medico"
                        name="medico"
                        placeholder="Nome do médico"
                    >
                </div>

                <div class="receita-field">
                    <label for="crm">CRM</label>
                    <input
                        type="text"
                        id="crm"
                        name="crm"
                        placeholder="CRM do médico"
                    >
                </div>

            </div>

        </section>


        {{-- Medicamento --}}
        <section class="receita-section">

            <div class="section-header">
                <div>
                    <h2>Medicamento</h2>
                    <p>Informe o medicamento controlado relacionado à receita.</p>
                </div>
            </div>

            <div class="receita-grid">

                <div class="receita-field receita-field-full">
                    <label for="medicamento">Medicamento</label>
                    <input
                        type="text"
                        id="medicamento"
                        name="medicamento"
                        placeholder="Digite o nome ou código do medicamento"
                    >
                </div>

            </div>

        </section>


        {{-- Observações --}}
        <section class="receita-section">

            <div class="section-header">
                <div>
                    <h2>Observações</h2>
                    <p>Adicione informações complementares, se necessário.</p>
                </div>
            </div>

            <div class="receita-field receita-field-full">
                <textarea
                    id="observacoes"
                    name="observacoes"
                    rows="4"
                    placeholder="Digite as observações da receita"
                ></textarea>
            </div>

        </section>


        {{-- Ações --}}
        <div class="receita-actions">

            <a href="{{ route('vendas.create') }}" class="btn-outline">
                Voltar
            </a>

            <button type="button" class="btn-secondary">
                Salvar Receita
            </button>

        </div>

    </div>

@endsection