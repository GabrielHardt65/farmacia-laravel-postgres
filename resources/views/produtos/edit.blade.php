
@extends('layouts.app')

@section('title', 'Editar Produto')
@section('page-title', 'Editar Produto')

@section('content')

<style>
    .produto-editar {
        background: #fff;
        padding: 28px;
        border: 1px solid #e7e9f0;
        border-radius: 14px;
    }

    .produto-editar h2 {
        color: #242630;
        margin-top: 0;
    }

    .produto-editar-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .produto-editar-campo label {
        display: block;
        margin-bottom: 7px;
        color: #35394b;
        font-size: 12px;
        font-weight: 700;
    }

    .produto-editar-campo input,
    .produto-editar-campo select,
    .produto-editar-campo textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #dfe2ed;
        border-radius: 9px;
        background: #f8f9fc;
        font-family: inherit;
        box-sizing: border-box;
    }

    .produto-editar-campo textarea {
        min-height: 100px;
        resize: vertical;
    }

    .produto-editar-acoes {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 25px;
    }

    .produto-editar-acoes button,
    .produto-editar-acoes a {
        padding: 12px 20px;
        border-radius: 9px;
        font-weight: 700;
        text-decoration: none;
        font-size: 13px;
    }

    .produto-editar-acoes button {
        border: none;
        background: #d83e80;
        color: #fff;
        cursor: pointer;
    }

    .produto-editar-acoes a {
        background: #f1f2f7;
        color: #505566;
    }

    @media (max-width: 700px) {
        .produto-editar-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="produto-editar">
    <h2>Editar: {{ $produto->nome }}</h2>

    @if($errors->any())
        <div style="color:#bf3449; margin-bottom:20px;">
            <ul>
                @foreach($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        action="{{ route('produtos.update', $produto->id_produto) }}"
        method="POST"
    >
        @csrf
        @method('PUT')

        <div class="produto-editar-grid">
            <div class="produto-editar-campo">
                <label for="nome">Nome *</label>
                <input
                    id="nome"
                    name="nome"
                    value="{{ old('nome', $produto->nome) }}"
                    required
                    maxlength="255"
                >
            </div>

            <div class="produto-editar-campo">
                <label for="codigo_barras">Código de barras</label>
                <input
                    id="codigo_barras"
                    name="codigo_barras"
                    value="{{ old('codigo_barras', $produto->codigo_barras) }}"
                    maxlength="100"
                >
            </div>

            <div class="produto-editar-campo">
                <label for="id_categoria">Categoria *</label>
                <select id="id_categoria" name="id_categoria" required>
                    @foreach($categorias as $categoria)
                        <option
                            value="{{ $categoria->id_categoria }}"
                            @selected(old('id_categoria', $produto->id_categoria) == $categoria->id_categoria)
                        >
                            {{ $categoria->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="produto-editar-campo">
                <label for="id_fabricante">Fabricante *</label>
                <select id="id_fabricante" name="id_fabricante" required>
                    @foreach($fabricantes as $fabricante)
                        <option
                            value="{{ $fabricante->id_fabricante }}"
                            @selected(old('id_fabricante', $produto->id_fabricante) == $fabricante->id_fabricante)
                        >
                            {{ $fabricante->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="produto-editar-campo">
                <label for="tipo_controle">Tipo de controle *</label>
                <select id="tipo_controle" name="tipo_controle" required>
                    @foreach([
                        'livre' => 'Venda livre',
                        'antibiotico' => 'Antibiótico',
                        'tarja_vermelha' => 'Tarja vermelha',
                        'tarja_preta' => 'Tarja preta'
                    ] as $valor => $rotulo)
                        <option
                            value="{{ $valor }}"
                            @selected(old('tipo_controle', $produto->tipo_controle) == $valor)
                        >
                            {{ $rotulo }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="produto-editar-campo">
                <label for="exige_receita">Exige receita? *</label>
                <select id="exige_receita" name="exige_receita" required>
                    <option
                        value="0"
                        @selected((string) old('exige_receita', (int) $produto->exige_receita) === '0')
                    >
                        Não
                    </option>
                    <option
                        value="1"
                        @selected((string) old('exige_receita', (int) $produto->exige_receita) === '1')
                    >
                        Sim
                    </option>
                </select>
            </div>

            <div class="produto-editar-campo">
                <label for="preco_unitario">Preço de venda *</label>
                <input
                    type="number"
                    id="preco_unitario"
                    name="preco_unitario"
                    step="0.01"
                    min="0"
                    value="{{ old('preco_unitario', $produto->preco_unitario) }}"
                    required
                >
            </div>

            <div class="produto-editar-campo">
                <label for="principio_ativo">Princípio ativo</label>
                <input
                    id="principio_ativo"
                    name="principio_ativo"
                    value="{{ old('principio_ativo', $produto->principio_ativo) }}"
                    maxlength="255"
                >
            </div>

            <div class="produto-editar-campo">
                <label for="forma_farmaceutica">Forma farmacêutica</label>
                <input
                    id="forma_farmaceutica"
                    name="forma_farmaceutica"
                    value="{{ old('forma_farmaceutica', $produto->forma_farmaceutica) }}"
                    maxlength="255"
                >
            </div>

            <div class="produto-editar-campo">
                <label for="unidade_medida">Unidade de medida</label>
                <input
                    id="unidade_medida"
                    name="unidade_medida"
                    value="{{ old('unidade_medida', $produto->unidade_medida) }}"
                    maxlength="50"
                >
            </div>

            <div class="produto-editar-campo" style="grid-column:1/-1;">
                <label for="descricao">Descrição</label>
                <textarea
                    id="descricao"
                    name="descricao"
                >{{ old('descricao', $produto->descricao) }}</textarea>
            </div>
        </div>

        <div class="produto-editar-acoes">
            <a href="{{ route('produtos.index') }}">
                Cancelar
            </a>

            <button type="submit">
                Salvar alterações
            </button>
        </div>
    </form>
</div>

@endsection
