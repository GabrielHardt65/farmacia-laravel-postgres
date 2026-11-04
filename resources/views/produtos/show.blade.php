
@extends('layouts.app')

@section('title', 'Visualizar Produto')
@section('page-title', 'Detalhes do Produto')

@section('content')

<style>
    .produto-detalhes {
        background: #ffffff;
        padding: 28px;
        border: 1px solid #e7e9f0;
        border-radius: 14px;
    }

    .produto-detalhes h2 {
        color: #242630;
        font-size: 24px;
        font-weight: 700;
        margin: 0 0 25px;
    }

    .produto-detalhes h3 {
        color: #7a1e3a;
        font-size: 18px;
        font-weight: 700;
        margin: 30px 0 15px;
    }

    .produto-dados {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .produto-dado {
        padding: 15px;
        background: #f8f9fc;
        border: 1px solid #edf0f5;
        border-radius: 9px;
        overflow-wrap: anywhere;
    }

    .produto-dado strong {
        display: block;
        margin-bottom: 7px;
        font-size: 12px;
        color: #858999;
    }

    .produto-dado span {
        color: #242630;
        font-size: 14px;
        font-weight: 500;
    }

    .produto-lotes-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .produto-lotes {
        width: 100%;
        border-collapse: collapse;
    }

    .produto-lotes th {
        background: #f8f9fc;
        color: #5e6475;
        font-weight: 700;
    }

    .produto-lotes th,
    .produto-lotes td {
        padding: 14px;
        text-align: left;
        border-bottom: 1px solid #edf0f5;
        font-size: 13px;
    }

    .produto-botoes {
        display: flex;
        gap: 12px;
        margin-top: 28px;
        flex-wrap: wrap;
    }

    .produto-botoes a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px 20px;
        border-radius: 9px;
        text-decoration: none;
        background: #7a1e3a;
        color: #ffffff;
        font-weight: 700;
        font-size: 13px;
    }

    .produto-botoes a.voltar {
        background: #f1f2f7;
        color: #505566;
    }

    .produto-botoes a:hover {
        opacity: 0.9;
    }

    @media (max-width: 700px) {
        .produto-detalhes {
            padding: 18px;
        }

        .produto-dados {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="produto-detalhes">

    <h2>{{ $produto->nome }}</h2>

    <div class="produto-dados">

        @foreach([
            'Código do produto' => $produto->id_produto,
            'Código de barras' => $produto->codigo_barras,
            'Categoria' => $categoria,
            'Fabricante' => $fabricante,
            'Descrição' => $produto->descricao,
            'Princípio ativo' => $produto->principio_ativo,
            'Forma farmacêutica' => $produto->forma_farmaceutica,
            'Unidade de medida' => $produto->unidade_medida,
            'Tipo de controle' => $produto->tipo_controle,
            'Exige receita' => $produto->exige_receita ? 'Sim' : 'Não',
            'Preço de venda' => 'R$ ' . number_format(
                (float) $produto->preco_unitario,
                2,
                ',',
                '.'
            )
        ] as $rotulo => $valor)

            <div class="produto-dado">
                <strong>{{ $rotulo }}</strong>

                <span>
                    {{ $valor !== null && $valor !== '' ? $valor : 'Não informado' }}
                </span>
            </div>

        @endforeach

    </div>

    <h3>Lotes cadastrados</h3>

    <div class="produto-lotes-wrapper">

        <table class="produto-lotes">

            <thead>
                <tr>
                    <th>Lote</th>
                    <th>Fabricação</th>
                    <th>Validade</th>
                    <th>Estoque atual</th>
                    <th>Preço de custo</th>
                </tr>
            </thead>

            <tbody>

                @forelse($lotes as $lote)

                    <tr>
                        <td>{{ $lote->numero_lote }}</td>

                        <td>
                            {{ \Carbon\Carbon::parse($lote->data_fabricacao)->format('d/m/Y') }}
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($lote->data_validade)->format('d/m/Y') }}
                        </td>

                        <td>{{ $lote->quantidade_atual }}</td>

                        <td>
                            R$ {{ number_format(
                                (float) $lote->preco_custo,
                                2,
                                ',',
                                '.'
                            ) }}
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="5">
                            Nenhum lote cadastrado para este produto.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="produto-botoes">

        <a href="{{ route('produtos.index') }}" class="voltar">
            Voltar
        </a>

        <a href="{{ route('produtos.edit', $produto->id_produto) }}">
            Editar produto
        </a>

    </div>

</div>

@endsection
