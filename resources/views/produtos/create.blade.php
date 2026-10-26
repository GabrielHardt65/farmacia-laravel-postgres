@extends('layouts.app')

@section('title', 'Novo Produto - Sistema Farmácia')
@section('page-title', 'Novo Produto')

@section('content')

<div class="produto-form-container">

    <section class="produto-form-section">

        <div class="section-header">
            <div>
                <h2>Cadastro de Produto</h2>
                <p>Preencha os dados para cadastrar um novo produto.</p>
            </div>
        </div>

        <div class="produto-form-grid">

            <div class="produto-field">
                <label for="nome">Nome do produto</label>
                <input
                    type="text"
                    id="nome"
                    name="nome"
                    placeholder="Digite o nome do produto"
                >
            </div>

            <div class="produto-field">
                <label for="codigo_barras">Código de barras</label>
                <input
                    type="text"
                    id="codigo_barras"
                    name="codigo_barras"
                    placeholder="Digite o código de barras"
                >
            </div>

            <div class="produto-field">
                <label for="categoria">Categoria</label>
                <select id="categoria" name="categoria">
                    <option value="">Selecione uma categoria</option>
                </select>
            </div>

            <div class="produto-field">
                <label for="fornecedor">Fornecedor</label>
                <select id="fornecedor" name="fornecedor">
                    <option value="">Selecione um fornecedor</option>
                </select>
            </div>

            <div class="produto-field">
                <label for="preco">Preço de venda</label>
                <input
                    type="number"
                    id="preco"
                    name="preco"
                    step="0.01"
                    min="0"
                    placeholder="R$ 0,00"
                >
            </div>

            <div class="produto-field">
                <label for="estoque">Quantidade em estoque</label>
                <input
                    type="number"
                    id="estoque"
                    name="estoque"
                    min="0"
                    placeholder="0"
                >
            </div>

            <div class="produto-field">
                <label for="validade">Data de validade</label>
                <input
                    type="date"
                    id="validade"
                    name="validade"
                >
            </div>

            <div class="produto-field">
                <label for="controlado">Medicamento controlado</label>
                <select id="controlado" name="controlado">
                    <option value="nao">Não</option>
                    <option value="sim">Sim</option>
                </select>
            </div>

        </div>

        <div class="produto-field produto-field-full">
            <label for="descricao">Descrição</label>
            <textarea
                id="descricao"
                name="descricao"
                rows="4"
                placeholder="Informações adicionais sobre o produto"
            ></textarea>
        </div>

        <div class="produto-form-actions">

            <a href="{{ route('produtos.index') }}" class="btn-outline">
                Cancelar
            </a>

            <button type="button" class="btn-finalizar">
                Salvar Produto
            </button>

        </div>

    </section>

</div>

@endsection