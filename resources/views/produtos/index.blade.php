
@extends('layouts.app')

@section('title', 'Produtos - Sistema Farmácia')
@section('page-title', 'Produtos')

@section('content')

<style>
    .produtos-container,
    .produtos-container * {
        box-sizing: border-box;
    }

    .produtos-container {
        width: 100%;
    }

    .produtos-section {
        background: #ffffff;
        border: 1px solid #e7e9f0;
        border-radius: 14px;
        padding: 26px;
        box-shadow: 0 5px 20px rgba(30, 32, 45, 0.04);
    }

    .produtos-cabecalho {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 28px;
    }

    .produtos-cabecalho h2 {
        margin: 0 0 6px;
        color: #242630;
        font-size: 24px;
        font-weight: 800;
    }

    .produtos-cabecalho p {
        margin: 0;
        color: #858999;
        font-size: 13px;
    }

    .produtos-btn-novo {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 43px;
        padding: 0 20px;
        border-radius: 9px;
        background: linear-gradient(135deg, #ed5797, #d83e80);
        color: #ffffff !important;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .produtos-btn-novo:hover {
        background: #c82d6d;
    }

    .produtos-filtros {
        display: flex;
        align-items: flex-end;
        gap: 12px;
        margin-bottom: 24px;
    }

    .produtos-field {
        flex: 1;
        min-width: 0;
    }

    .produtos-field label {
        display: block;
        margin-bottom: 8px;
        color: #35394b;
        font-size: 12px;
        font-weight: 700;
    }

    .produtos-field input {
        width: 100%;
        height: 44px;
        padding: 0 14px;
        background: #f8f9fc;
        border: 1px solid #dfe2ed;
        border-radius: 9px;
        outline: none;
        color: #242630;
        font-family: inherit;
        font-size: 13px;
    }

    .produtos-field input:focus {
        border-color: #d83e80;
        box-shadow: 0 0 0 3px rgba(216, 62, 128, 0.1);
    }

    .produtos-btn-buscar {
        height: 44px;
        padding: 0 23px;
        border: none;
        border-radius: 9px;
        background: #d83e80;
        color: #ffffff;
        font-family: inherit;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
    }

    .produtos-btn-buscar:hover {
        background: #c52e6d;
    }

    .produtos-tabela-wrapper {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #e9eaf0;
        border-radius: 11px;
    }

    .produtos-tabela {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        background: #ffffff;
    }

    .produtos-tabela thead {
        background: #f7f8fb;
    }

    .produtos-tabela th {
        padding: 15px 17px;
        text-align: left;
        color: #5e6475;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
        border-bottom: 1px solid #e9eaf0;
    }

    .produtos-tabela td {
        padding: 16px 17px;
        color: #35394b;
        font-size: 13px;
        vertical-align: middle;
        border-bottom: 1px solid #edf0f5;
    }

    .produtos-tabela tbody tr:last-child td {
        border-bottom: none;
    }

    .produtos-tabela tbody tr:hover {
        background: #fff8fb;
    }

    .produto-codigo {
        color: #d83e80;
        font-weight: 800;
    }

    .produto-nome {
        color: #242630;
        font-weight: 700;
    }

    .produto-codigo-barras {
        display: block;
        margin-top: 4px;
        color: #9295a3;
        font-size: 11px;
    }

    .produto-estoque {
        display: inline-flex;
        align-items: center;
        padding: 6px 11px;
        border-radius: 7px;
        background: #e9f8ee;
        color: #25844a;
        font-size: 12px;
        font-weight: 700;
    }

    .produto-estoque.zerado {
        background: #fff0f1;
        color: #c94050;
    }

    .produto-preco {
        color: #242630;
        font-weight: 800;
        white-space: nowrap;
    }

    .produto-controle {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 7px;
        background: #f1f2f7;
        color: #5e6475;
        font-size: 11px;
        font-weight: 700;
    }

    .produtos-vazio {
        padding: 48px 20px !important;
        color: #858999 !important;
        text-align: center;
        font-size: 13px;
    }

    .produtos-rodape {
        margin-top: 15px;
        color: #9295a3;
        font-size: 12px;
    }

    .produtos-acoes {
        display: flex;
        align-items: center;
        gap: 7px;
        flex-wrap: wrap;
    }

    .produtos-acoes form {
        margin: 0;
    }

    .produto-btn-acao {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 11px;
        border: 1px solid #f1b8d0;
        border-radius: 8px;
        background: #fff0f6;
        color: #cc3676;
        font-size: 12px;
        font-weight: 700;
        font-family: inherit;
        text-decoration: none;
        white-space: nowrap;
        cursor: pointer;
    }

    .produto-btn-acao:hover {
        background: #fbdde9;
    }

    .produto-btn-acao.excluir {
        background: #fff0f1;
        border-color: #f3c0c6;
        color: #bf3449;
    }

    .produtos-alerta {
        padding: 13px 16px;
        margin-bottom: 16px;
        border-radius: 9px;
        background: #e9f8ee;
        color: #25844a;
        font-size: 13px;
    }

    .produtos-alerta.erro {
        background: #fff0f1;
        color: #bf3449;
    }

    @media (max-width: 700px) {
        .produtos-section {
            padding: 17px;
        }

        .produtos-cabecalho {
            align-items: flex-start;
            flex-direction: column;
        }

        .produtos-filtros {
            align-items: stretch;
            flex-direction: column;
        }

        .produtos-btn-buscar {
            width: 100%;
        }
    }
</style>

<div class="produtos-container">
    <section class="produtos-section">

        <div class="produtos-cabecalho">
            <div>
                <h2>Produtos</h2>
                <p>
                    Consulte os produtos cadastrados na farmácia
                    e acompanhe o estoque.
                </p>
            </div>

            <a
                href="{{ route('produtos.create') }}"
                class="produtos-btn-novo"
            >
                + Novo Produto
            </a>
        </div>

        <form
            action="{{ route('produtos.index') }}"
            method="GET"
            class="produtos-filtros"
        >
            <div class="produtos-field">
                <label for="busca_produto">Buscar produto</label>

                <input
                    type="text"
                    id="busca_produto"
                    name="busca"
                    value="{{ $busca ?? '' }}"
                    placeholder="Nome, código ou código de barras"
                    autocomplete="off"
                >
            </div>

            <button type="submit" class="produtos-btn-buscar">
                Buscar
            </button>
        </form>

        @if(session('success'))
            <div class="produtos-alerta">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="produtos-alerta erro">
                {{ session('error') }}
            </div>
        @endif

        <div class="produtos-tabela-wrapper">
            <table class="produtos-tabela">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Produto</th>
                        <th>Tipo de controle</th>
                        <th>Estoque</th>
                        <th>Preço</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($produtos ?? [] as $produto)
                        <tr>
                            <td>
                                <span class="produto-codigo">
                                    #{{ $produto->id_produto }}
                                </span>
                            </td>

                            <td>
                                <span class="produto-nome">
                                    {{ $produto->nome }}
                                </span>

                                @if($produto->codigo_barras)
                                    <span class="produto-codigo-barras">
                                        Código de barras:
                                        {{ $produto->codigo_barras }}
                                    </span>
                                @endif
                            </td>

                            <td>
                                <span class="produto-controle">
                                    {{ $produto->tipo_controle ?: 'Não informado' }}
                                </span>
                            </td>

                            <td>
                                <span class="produto-estoque {{ $produto->estoque <= 0 ? 'zerado' : '' }}">
                                    {{ number_format((float) $produto->estoque, 0, ',', '.') }}
                                    un.
                                </span>
                            </td>

                            <td>
                                <span class="produto-preco">
                                    R$ {{ number_format((float) $produto->preco_unitario, 2, ',', '.') }}
                                </span>
                            </td>

                            <td>
                                <div class="produtos-acoes">
                                    <a
                                        href="{{ route('produtos.show', $produto->id_produto) }}"
                                        class="produto-btn-acao"
                                    >
                                        Visualizar
                                    </a>

                                    <a
                                        href="{{ route('produtos.edit', $produto->id_produto) }}"
                                        class="produto-btn-acao"
                                    >
                                        Editar
                                    </a>

                                    <form
                                        action="{{ route('produtos.destroy', $produto->id_produto) }}"
                                        method="POST"
                                        onsubmit="return confirm('Deseja realmente excluir este produto?');"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="produto-btn-acao excluir"
                                        >
                                            Excluir
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="produtos-vazio">
                                @if(!empty($busca))
                                    Nenhum produto encontrado para
                                    "{{ $busca }}".
                                @else
                                    Nenhum produto cadastrado.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="produtos-rodape">
            Total de produtos encontrados:
            <strong>{{ count($produtos ?? []) }}</strong>
        </div>

    </section>
</div>

@endsection
