@extends('layouts.app')

@section('title', 'Nova Categoria - Sistema Farmácia')
@section('page-title', 'Nova Categoria')

@section('content')

<div class="categoria-form-container">

    <section class="categoria-form-section">

        <div class="section-header">
            <div>
                <h2>Cadastro de Categoria</h2>
                <p>Preencha os dados para cadastrar uma nova categoria.</p>
            </div>
        </div>

        <div class="categoria-form-grid">

            <div class="categoria-field">
                <label for="nome">Nome da categoria</label>
                <input
                    type="text"
                    id="nome"
                    name="nome"
                    placeholder="Digite o nome da categoria"
                >
            </div>

            <div class="categoria-field categoria-field-full">
                <label for="descricao">Descrição</label>
                <textarea
                    id="descricao"
                    name="descricao"
                    rows="4"
                    placeholder="Digite uma descrição para a categoria"
                ></textarea>
            </div>

        </div>

        <div class="categoria-form-actions">

            <a href="{{ route('categorias.index') }}" class="btn-outline">
                Cancelar
            </a>

            <button type="button" class="btn-finalizar">
                Salvar Categoria
            </button>

        </div>

    </section>

</div>

@endsection