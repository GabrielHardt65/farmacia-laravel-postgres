
@extends('layouts.app')

@section('title', 'Receitas - Sistema Farmácia')
@section('page-title', 'Receitas')

@section('content')

<style>
/* ==========================================
   RECEITAS MÉDICAS - LISTAGEM
========================================== */

.receitas-lista-container {
    width: 100%;
    max-width: 100%;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.receitas-lista-container * {
    box-sizing: border-box;
}

.receitas-lista-section {
    background: #ffffff;
    border: 1px solid #e8e9f0;
    border-radius: 14px;
    padding: 24px;
    box-shadow: 0 4px 16px rgba(30, 32, 45, 0.035);
}

/* CABEÇALHO */

.receitas-lista-cabecalho {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 24px;
}

.receitas-lista-icone {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 46px;
    height: 46px;
    flex-shrink: 0;
    background: #fce8f1;
    color: #d83e80;
    border-radius: 12px;
}

.receitas-lista-icone svg {
    width: 23px;
    height: 23px;
    fill: none;
    stroke: currentColor;
    stroke-width: 2;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.receitas-lista-cabecalho h2 {
    margin: 0 0 5px;
    color: #242630;
    font-size: 23px;
    font-weight: 800;
}

.receitas-lista-cabecalho p {
    margin: 0;
    color: #858999;
    font-size: 13px;
}

/* PESQUISA */

.receitas-lista-filtros {
    display: flex;
    align-items: flex-end;
    gap: 12px;
    margin-bottom: 22px;
}

.receitas-lista-field {
    flex: 1;
    min-width: 0;
}

.receitas-lista-field label {
    display: block;
    margin-bottom: 8px;
    color: #35394b;
    font-size: 12px;
    font-weight: 700;
}

.receitas-lista-field input {
    width: 100%;
    height: 43px;
    padding: 0 14px;
    border: 1px solid #dfe2ed;
    border-radius: 9px;
    background: #f8f9fc;
    color: #242630;
    font-family: inherit;
    font-size: 13px;
    outline: none;
}

.receitas-lista-field input:focus {
    border-color: #d83e80;
    box-shadow: 0 0 0 3px rgba(216, 62, 128, 0.1);
}

.receitas-lista-buscar {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    height: 43px;
    padding: 0 20px;
    border: 1px solid #d83e80;
    border-radius: 9px;
    background: linear-gradient(135deg, #ed5797, #d83e80);
    color: #ffffff;
    font-family: inherit;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    white-space: nowrap;
}

.receitas-lista-buscar:hover {
    background: #c82d6d;
}

.receitas-lista-buscar svg {
    width: 16px;
    height: 16px;
    fill: none;
    stroke: currentColor;
    stroke-width: 2;
}

/* TABELA */

.receitas-lista-tabela-wrapper {
    width: 100%;
    overflow-x: auto;
    border: 1px solid #e9eaf0;
    border-radius: 11px;
}

.receitas-lista-tabela {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
}

.receitas-lista-tabela thead {
    background: #f7f8fb;
}

.receitas-lista-tabela th {
    padding: 15px 16px;
    color: #5e6475;
    font-size: 12px;
    font-weight: 700;
    text-align: left;
    white-space: nowrap;
    border-bottom: 1px solid #e9eaf0;
}

.receitas-lista-tabela td {
    padding: 15px 16px;
    color: #35394b;
    font-size: 13px;
    border-bottom: 1px solid #edf0f5;
}

.receitas-lista-tabela tbody tr:last-child td {
    border-bottom: none;
}

.receitas-lista-tabela tbody tr:hover {
    background: #fff8fb;
}

/* NÚMERO DA RECEITA */

.receita-numero {
    color: #d83e80;
    font-weight: 800;
}

/* BOTÃO VISUALIZAR */

.receita-botao-visualizar {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 8px 13px;
    border: 1px solid #f1b8d0;
    border-radius: 8px;
    background: #fff0f6;
    color: #cc3676;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
}

.receita-botao-visualizar:hover {
    background: #fbdde9;
}

/* ESTADO VAZIO */

.receitas-lista-vazio {
    padding: 50px 20px !important;
    text-align: center;
}

.receitas-lista-vazio-conteudo {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
}

.receitas-lista-vazio-conteudo svg {
    width: 35px;
    height: 35px;
    fill: none;
    stroke: #c4c7d1;
    stroke-width: 1.6;
}

.receitas-lista-vazio-conteudo strong {
    color: #505566;
    font-size: 14px;
}

.receitas-lista-vazio-conteudo span {
    color: #9295a3;
    font-size: 12px;
}

.receitas-lista-sem-resultado {
    padding: 30px 15px !important;
    text-align: center;
    color: #858999 !important;
}

/* RESPONSIVIDADE */

@media (max-width: 650px) {
    .receitas-lista-section {
        padding: 15px;
    }

    .receitas-lista-cabecalho h2 {
        font-size: 19px;
    }

    .receitas-lista-filtros {
        flex-direction: column;
        align-items: stretch;
    }

    .receitas-lista-buscar {
        width: 100%;
    }
}
</style>

<div class="receitas-lista-container">

    <section class="receitas-lista-section">

        {{-- CABEÇALHO --}}

        <div class="receitas-lista-cabecalho">

            <div class="receitas-lista-icone">
                <svg viewBox="0 0 24 24">
                    <path d="M7 3h8l5 5v13H7z"/>
                    <path d="M15 3v6h5"/>
                    <path d="M10 13h7"/>
                    <path d="M10 17h7"/>
                </svg>
            </div>

            <div>
                <h2>Receitas Médicas</h2>
                <p>
                    Consulte as receitas médicas registradas
                    durante as vendas.
                </p>
            </div>

        </div>

        {{-- PESQUISA POR PACIENTE --}}

        <div class="receitas-lista-filtros">

            <div class="receitas-lista-field">

                <label for="busca_receita">
                    Buscar receita
                </label>

                <input
                    type="text"
                    id="busca_receita"
                    name="busca_receita"
                    placeholder="Digite o nome do paciente"
                    autocomplete="off"
                >

            </div>

            <button
                type="button"
                class="receitas-lista-buscar"
                id="btn-buscar-receita"
            >
                <svg viewBox="0 0 24 24">
                    <circle cx="10.5" cy="10.5" r="6.5"/>
                    <path d="m16 16 5 5"/>
                </svg>

                Buscar
            </button>

        </div>

        {{-- LISTAGEM --}}

        <div class="receitas-lista-tabela-wrapper">

            <table
                class="receitas-lista-tabela"
                id="tabela-receitas"
            >

                <thead>
                    <tr>
                        <th>Número</th>
                        <th>Paciente</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse (($receitas ?? collect()) as $receita)

                        <tr class="linha-receita">

                            <td>
                                <span class="receita-numero">
                                    #{{ $receita->id_receita }}
                                </span>
                            </td>

                            <td class="nome-paciente">
                                {{ $receita->cliente->nome ?? $receita->nome_paciente ?? '-' }}
                            </td>

                            <td>
                                @if (!empty($receita->data_receita))
                                    {{ \Carbon\Carbon::parse($receita->data_receita)->format('d/m/Y') }}
                                @else
                                    -
                                @endif
                            </td>

                            <td>
                                @if (\Illuminate\Support\Facades\Route::has('receitas.show'))
                                    <a
                                        href="{{ route('receitas.show', $receita->id_receita) }}"
                                        class="receita-botao-visualizar"
                                    >
                                        Visualizar
                                    </a>
                                @else
                                    <span style="color:#9295a3;">-</span>
                                @endif
                            </td>

                        </tr>

                    @empty

                        <tr id="nenhuma-receita">
                            <td colspan="4" class="receitas-lista-vazio">

                                <div class="receitas-lista-vazio-conteudo">

                                    <svg viewBox="0 0 24 24">
                                        <path d="M7 3h8l5 5v13H7z"/>
                                        <path d="M15 3v6h5"/>
                                        <path d="M10 13h7"/>
                                        <path d="M10 17h7"/>
                                    </svg>

                                    <strong>
                                        Nenhuma receita cadastrada.
                                    </strong>

                                    <span>
                                        As receitas registradas nas vendas
                                        aparecerão aqui.
                                    </span>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                    <tr
                        id="nenhum-resultado-receita"
                        style="display:none;"
                    >
                        <td
                            colspan="4"
                            class="receitas-lista-sem-resultado"
                        >
                            Nenhuma receita encontrada para este paciente.
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

    </section>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const campoBusca = document.getElementById('busca_receita');

    const botaoBusca = document.getElementById(
        'btn-buscar-receita'
    );

    const linhas = document.querySelectorAll(
        '#tabela-receitas .linha-receita'
    );

    const nenhumResultado = document.getElementById(
        'nenhum-resultado-receita'
    );

    function normalizarTexto(texto) {

        return String(texto || '')
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .toLowerCase()
            .trim();

    }

    function buscarReceitas() {

        const termo = normalizarTexto(campoBusca.value);

        let encontrados = 0;

        linhas.forEach(function (linha) {

            const paciente = linha.querySelector(
                '.nome-paciente'
            );

            const nome = normalizarTexto(
                paciente ? paciente.textContent : ''
            );

            const corresponde = nome.includes(termo);

            linha.style.display = corresponde ? '' : 'none';

            if (corresponde) {
                encontrados++;
            }

        });

        if (nenhumResultado) {

            nenhumResultado.style.display =
                linhas.length > 0 && encontrados === 0
                    ? ''
                    : 'none';

        }

    }

    campoBusca.addEventListener(
        'input',
        buscarReceitas
    );

    botaoBusca.addEventListener(
        'click',
        buscarReceitas
    );

});
</script>

@endsection
