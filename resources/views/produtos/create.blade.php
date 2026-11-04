
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

    .produto-form-subtitulo {
        grid-column: 1 / -1;
        margin-top: 6px;
        padding-bottom: 10px;
        border-bottom: 1px solid #edf0f5;
        color: #d83e80;
        font-size: 15px;
        font-weight: 800;
    }

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

    .produto-alerta {
        padding: 14px 18px;
        margin-bottom: 22px;
        border-radius: 9px;
        background: #fff0f3;
        border: 1px solid #f4b5c5;
        color: #a52b52;
        font-size: 13px;
    }

    .produto-alerta ul {
        margin: 8px 0 0;
        padding-left: 20px;
    }

    .produto-erro {
        color: #c62850;
        font-size: 11px;
        margin-top: 5px;
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

        .produto-field-full,
        .produto-form-subtitulo {
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

        <div class="produto-form-cabecalho">
            <h2>Cadastro de Produto</h2>
            <p>Preencha os dados para cadastrar um novo produto.</p>
        </div>

        @if ($errors->any())
            <div class="produto-alerta">
                <strong>Não foi possível salvar o produto.</strong>
                <ul>
                    @foreach ($errors->all() as $erro)
                        <li>{{ $erro }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('produtos.store') }}" method="POST">

            @csrf

            <div class="produto-form-grid">

                <div class="produto-form-subtitulo">
                    Informações do Produto
                </div>

                {{-- NOME --}}

                <div class="produto-field">
                    <label for="nome">Nome do produto *</label>
                    <input
                        type="text"
                        id="nome"
                        name="nome"
                        value="{{ old('nome') }}"
                        placeholder="Ex.: Dipirona 1g"
                        maxlength="255"
                        required
                    >
                    @error('nome')
                        <span class="produto-erro">{{ $message }}</span>
                    @enderror
                </div>

                {{-- CÓDIGO DE BARRAS --}}

                <div class="produto-field">
                    <label for="codigo_barras">Código de barras</label>
                    <input
                        type="text"
                        id="codigo_barras"
                        name="codigo_barras"
                        value="{{ old('codigo_barras') }}"
                        placeholder="Digite o código de barras"
                        maxlength="100"
                    >
                </div>

                {{-- CATEGORIA --}}

                <div class="produto-field">
                    <label for="id_categoria">Categoria *</label>
                    <select id="id_categoria" name="id_categoria" required>
                        <option value="">Selecione uma categoria</option>

                        @foreach ($categorias as $categoria)
                            <option
                                value="{{ $categoria->id_categoria }}"
                                @selected(old('id_categoria') == $categoria->id_categoria)
                            >
                                {{ $categoria->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- FABRICANTE --}}

                <div class="produto-field">
                    <label for="id_fabricante">Laboratório / Fabricante *</label>
                    <select id="id_fabricante" name="id_fabricante" required>
                        <option value="">Selecione um fabricante</option>

                        @foreach ($fabricantes as $fabricante)
                            <option
                                value="{{ $fabricante->id_fabricante }}"
                                @selected(old('id_fabricante') == $fabricante->id_fabricante)
                            >
                                {{ $fabricante->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- TIPO DE CONTROLE --}}

                <div class="produto-field">
                    <label for="tipo_controle">Tipo de controle *</label>
                    <select id="tipo_controle" name="tipo_controle" required>
                        <option value="livre" @selected(old('tipo_controle', 'livre') == 'livre')>
                            Venda livre
                        </option>
                        <option value="antibiotico" @selected(old('tipo_controle') == 'antibiotico')>
                            Antibiótico
                        </option>
                        <option value="tarja_vermelha" @selected(old('tipo_controle') == 'tarja_vermelha')>
                            Tarja vermelha
                        </option>
                        <option value="tarja_preta" @selected(old('tipo_controle') == 'tarja_preta')>
                            Tarja preta
                        </option>
                    </select>
                </div>

                {{-- EXIGE RECEITA --}}

                <div class="produto-field">
                    <label for="exige_receita">Exige receita médica? *</label>
                    <select id="exige_receita" name="exige_receita" required>
                        <option value="0" @selected(old('exige_receita', '0') == '0')>
                            Não
                        </option>
                        <option value="1" @selected(old('exige_receita') == '1')>
                            Sim
                        </option>
                    </select>
                </div>

                {{-- PREÇO DE VENDA --}}

                <div class="produto-field">
                    <label for="preco_unitario">Preço de venda (R$) *</label>
                    <input
                        type="number"
                        id="preco_unitario"
                        name="preco_unitario"
                        value="{{ old('preco_unitario') }}"
                        step="0.01"
                        min="0"
                        placeholder="0.00"
                        required
                    >
                </div>

                {{-- PRINCÍPIO ATIVO --}}

                <div class="produto-field">
                    <label for="principio_ativo">Princípio ativo</label>
                    <input
                        type="text"
                        id="principio_ativo"
                        name="principio_ativo"
                        value="{{ old('principio_ativo') }}"
                        placeholder="Ex.: Dipirona sódica"
                        maxlength="255"
                    >
                </div>

                {{-- FORMA FARMACÊUTICA --}}

                <div class="produto-field">
                    <label for="forma_farmaceutica">Forma farmacêutica</label>
                    <input
                        type="text"
                        id="forma_farmaceutica"
                        name="forma_farmaceutica"
                        value="{{ old('forma_farmaceutica') }}"
                        placeholder="Ex.: Comprimido, solução, cápsula"
                        maxlength="255"
                    >
                </div>

                {{-- UNIDADE DE MEDIDA --}}

                <div class="produto-field">
                    <label for="unidade_medida">Unidade de medida</label>
                    <input
                        type="text"
                        id="unidade_medida"
                        name="unidade_medida"
                        value="{{ old('unidade_medida') }}"
                        placeholder="Ex.: Unidade, caixa, frasco"
                        maxlength="50"
                    >
                </div>

                {{-- DESCRIÇÃO --}}

                <div class="produto-field produto-field-full">
                    <label for="descricao">Descrição</label>
                    <textarea
                        id="descricao"
                        name="descricao"
                        rows="4"
                        placeholder="Informações adicionais sobre o produto"
                    >{{ old('descricao') }}</textarea>
                </div>

                <div class="produto-form-subtitulo">
                    Lote e Estoque Inicial
                </div>

                {{-- FORNECEDOR --}}

                <div class="produto-field">
                    <label for="id_fornecedor">Fornecedor *</label>
                    <select id="id_fornecedor" name="id_fornecedor" required>
                        <option value="">Selecione um fornecedor</option>

                        @foreach ($fornecedores as $fornecedor)
                            <option
                                value="{{ $fornecedor->id_fornecedor }}"
                                @selected(old('id_fornecedor') == $fornecedor->id_fornecedor)
                            >
                                {{ $fornecedor->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- NÚMERO DO LOTE --}}

                <div class="produto-field">
                    <label for="numero_lote">Número do lote *</label>
                    <input
                        type="text"
                        id="numero_lote"
                        name="numero_lote"
                        value="{{ old('numero_lote') }}"
                        placeholder="Ex.: LT2026001"
                        maxlength="100"
                        required
                    >
                </div>

                {{-- DATA DE FABRICAÇÃO --}}

                <div class="produto-field">
                    <label for="data_fabricacao">Data de fabricação *</label>
                    <input
                        type="date"
                        id="data_fabricacao"
                        name="data_fabricacao"
                        value="{{ old('data_fabricacao') }}"
                        required
                    >
                </div>

                {{-- DATA DE VALIDADE --}}

                <div class="produto-field">
                    <label for="data_validade">Data de validade *</label>
                    <input
                        type="date"
                        id="data_validade"
                        name="data_validade"
                        value="{{ old('data_validade') }}"
                        required
                    >
                </div>

                {{-- QUANTIDADE EM ESTOQUE --}}

                <div class="produto-field">
                    <label for="estoque">Quantidade em estoque *</label>
                    <input
                        type="number"
                        id="estoque"
                        name="estoque"
                        value="{{ old('estoque') }}"
                        min="0"
                        step="1"
                        placeholder="0"
                        required
                    >
                </div>

                {{-- PREÇO DE CUSTO --}}

                <div class="produto-field">
                    <label for="preco_custo">Preço de custo (R$) *</label>
                    <input
                        type="number"
                        id="preco_custo"
                        name="preco_custo"
                        value="{{ old('preco_custo') }}"
                        step="0.01"
                        min="0"
                        placeholder="0.00"
                        required
                    >
                </div>

            </div>

            <div class="produto-form-actions">

                <a
                    href="{{ route('produtos.index') }}"
                    class="produto-btn-cancelar"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="produto-btn-salvar"
                    id="salvar-produto"
                >
                    Salvar Produto
                </button>

            </div>

        </form>

        <p class="produto-aviso">
            * Campos obrigatórios. O estoque inicial será registrado em um lote vinculado ao produto.
        </p>

    </section>

</div>

@endsection
