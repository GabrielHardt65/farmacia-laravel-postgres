
@extends('layouts.app')

@section('title', 'Novo Produto - Sistema Farmácia')
@section('page-title', 'Novo Produto')

@section('content')

<style>
    .produto-form-container,
    .produto-form-container * {
        box-sizing: border-box;
    }

    .produto-form-container {
        width: 100%;
    }

    .produto-form-section {
        background: #ffffff;
        border: 1px solid #e7e9f0;
        border-radius: 14px;
        padding: 28px;
        box-shadow: 0 5px 20px rgba(30, 32, 45, 0.04);
    }

    /* CABEÇALHO */

    .produto-form-cabecalho {
        margin-bottom: 28px;
    }

    .produto-form-cabecalho h2 {
        margin: 0 0 7px;
        color: #242630;
        font-size: 24px;
        font-weight: 800;
    }

    .produto-form-cabecalho p {
        margin: 0;
        color: #858999;
        font-size: 13px;
    }

    /* FORMULÁRIO */

    .produto-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 22px;
    }

    .produto-field {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    .produto-field label {
        margin-bottom: 9px;
        color: #35394b;
        font-size: 12px;
        font-weight: 700;
    }

    .produto-field input,
    .produto-field select,
    .produto-field textarea {
        width: 100%;
        padding: 0 15px;
        border: 1px solid #dfe2ed;
        border-radius: 9px;
        background: #f8f9fc;
        color: #242630;
        font-family: inherit;
        font-size: 13px;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .produto-field input,
    .produto-field select {
        height: 46px;
    }

    .produto-field textarea {
        min-height: 105px;
        padding: 14px 15px;
        resize: vertical;
    }

    .produto-field input:focus,
    .produto-field select:focus,
    .produto-field textarea:focus {
        border-color: #d83e80;
        box-shadow: 0 0 0 3px rgba(216, 62, 128, 0.1);
    }

    .produto-field input::placeholder,
    .produto-field textarea::placeholder {
        color: #a1a5b2;
    }

    .produto-field-full {
        grid-column: 1 / -1;
    }

    /* AÇÕES */

    .produto-form-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 12px;
        margin-top: 28px;
        padding-top: 22px;
        border-top: 1px solid #edf0f5;
    }

    .produto-btn-cancelar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 44px;
        padding: 0 22px;
        border: 1px solid #dfe2ed;
        border-radius: 9px;
        background: #ffffff;
        color: #505566;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
    }

    .produto-btn-cancelar:hover {
        background: #f5f6fa;
    }

    .produto-btn-salvar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 44px;
        padding: 0 24px;
        border: none;
        border-radius: 9px;
        background: linear-gradient(135deg, #ed5797, #d83e80);
        color: #ffffff;
        font-family: inherit;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
    }

    .produto-btn-salvar:hover {
        background: #c82d6d;
    }

    .produto-aviso {
        margin-top: 16px;
        color: #858999;
        font-size: 12px;
    }

    @media (max-width: 700px) {
        .produto-form-section {
            padding: 18px;
        }

        .produto-form-grid {
            grid-template-columns: 1fr;
            gap: 18px;
        }

        .produto-field-full {
            grid-column: auto;
        }

        .produto-form-actions {
            flex-direction: column-reverse;
        }

        .produto-btn-cancelar,
        .produto-btn-salvar {
            width: 100%;
        }
    }
</style>

<div class="produto-form-container">

    <section class="produto-form-section">

        {{-- CABEÇALHO --}}

        <div class="produto-form-cabecalho">
            <h2>Cadastro de Produto</h2>
            <p>Preencha os dados para cadastrar um novo produto.</p>
        </div>

        <div class="produto-form-grid">

            {{-- NOME --}}

            <div class="produto-field">
                <label for="nome">Nome do produto</label>
                <input
                    type="text"
                    id="nome"
                    name="nome"
                    placeholder="Ex.: Dipirona 1g"
                    autocomplete="off"
                >
            </div>

            {{-- CÓDIGO DE BARRAS --}}

            <div class="produto-field">
                <label for="codigo_barras">Código de barras</label>
                <input
                    type="text"
                    id="codigo_barras"
                    name="codigo_barras"
                    placeholder="Digite o código de barras"
                    autocomplete="off"
                >
            </div>

            {{-- TIPO DE VENDA --}}

            <div class="produto-field">
                <label for="tipo_venda">Tipo de venda</label>
                <select id="tipo_venda" name="tipo_venda">
                    <option value="">Selecione o tipo de venda</option>
                    <option value="livre">Venda livre</option>
                    <option value="prescricao">Venda sob prescrição</option>
                </select>
            </div>

            {{-- LABORATÓRIO --}}

            <div class="produto-field">
                <label for="laboratorio">Laboratório</label>
                <input
                    type="text"
                    id="laboratorio"
                    name="laboratorio"
                    placeholder="Ex.: EMS, Medley, Neo Química"
                    autocomplete="off"
                >
            </div>

            {{-- PREÇO --}}

            <div class="produto-field">
                <label for="preco">Preço de venda</label>
                <input
                    type="number"
                    id="preco"
                    name="preco"
                    step="0.01"
                    min="0"
                    placeholder="0,00"
                >
            </div>

            {{-- ESTOQUE --}}

            <div class="produto-field">
                <label for="estoque">Quantidade em estoque</label>
                <input
                    type="number"
                    id="estoque"
                    name="estoque"
                    min="0"
                    step="1"
                    placeholder="0"
                >
            </div>

            {{-- VALIDADE --}}

            <div class="produto-field">
                <label for="validade">Data de validade</label>
                <input
                    type="date"
                    id="validade"
                    name="validade"
                >
            </div>

            {{-- CONTROLADO --}}

            <div class="produto-field">
                <label for="controlado">Medicamento controlado</label>
                <select id="controlado" name="controlado">
                    <option value="nao">Não</option>
                    <option value="sim">Sim</option>
                </select>
            </div>

            {{-- DESCRIÇÃO --}}

            <div class="produto-field produto-field-full">
                <label for="descricao">Descrição</label>
                <textarea
                    id="descricao"
                    name="descricao"
                    rows="4"
                    placeholder="Informações adicionais sobre o produto"
                ></textarea>
            </div>

        </div>

        {{-- BOTÕES --}}

        <div class="produto-form-actions">

            <a
                href="{{ route('produtos.index') }}"
                class="produto-btn-cancelar"
            >
                Cancelar
            </a>

            <button
                type="button"
                class="produto-btn-salvar"
                id="salvar-produto"
            >
                Salvar Produto
            </button>

        </div>

    </section>

</div>

@endsection
