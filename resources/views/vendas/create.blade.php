
@extends('layouts.app')

@section('title', 'Nova Venda - Sistema Farmácia')
@section('page-title', 'Nova Venda')

@section('content')

<style>
#pagina-venda {
    width: 100%;
    max-width: none;
    margin: 0;
    padding: 0 0 12px;
    color: #242630;
}

#pagina-venda *,
#pagina-venda *::before,
#pagina-venda *::after {
    box-sizing: border-box;
}

#pagina-venda .venda-cabecalho {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 14px;
}

#pagina-venda .venda-etiqueta {
    color: #d83e80;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 1px;
}

#pagina-venda .venda-cabecalho h1 {
    margin: 4px 0;
    font-size: 24px;
    font-weight: 800;
}

#pagina-venda .venda-cabecalho p {
    margin: 0;
    font-size: 12px;
    color: #858999;
}

#pagina-venda .venda-identificacao {
    display: flex;
    gap: 20px;
    padding: 11px 15px;
    background: #fff;
    border: 1px solid #e8e9ef;
    border-radius: 12px;
}

#pagina-venda .venda-identificacao span {
    display: block;
    margin-bottom: 4px;
    font-size: 11px;
    color: #9295a3;
}

#pagina-venda .venda-identificacao strong {
    font-size: 12px;
}

#pagina-venda .venda-painel-grid {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
    gap: 16px;
    align-items: start;
}

#pagina-venda .venda-coluna-esquerda,
#pagina-venda .venda-coluna-direita {
    display: flex;
    flex-direction: column;
    gap: 12px;
    min-width: 0;
}

#pagina-venda .venda-etapa {
    margin: 0;
    padding: 0;
    background: #fff;
    border: 1px solid #e8e9f0;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(30,32,45,.035);
}

#pagina-venda .venda-etapa-topo {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 14px 16px 11px;
}

#pagina-venda .venda-etapa-icone {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 39px;
    height: 39px;
    flex-shrink: 0;
    border-radius: 11px;
    background: #fce8f1;
    color: #d83e80;
}

#pagina-venda .venda-etapa-icone svg {
    width: 19px;
    height: 19px;
    fill: none;
    stroke: currentColor;
    stroke-width: 2;
    stroke-linecap: round;
    stroke-linejoin: round;
}

#pagina-venda .venda-etapa-titulo h2 {
    margin: 0;
    font-size: 15px;
    font-weight: 800;
}

#pagina-venda .venda-etapa-titulo p {
    margin: 3px 0 0;
    font-size: 12px;
    color: #9295a3;
}

#pagina-venda .venda-etapa-conteudo {
    padding: 0 16px 15px;
}

#pagina-venda .venda-campo {
    flex: 1;
    min-width: 0;
}

#pagina-venda .venda-campo label {
    display: block;
    margin-bottom: 6px;
    font-size: 12px;
    font-weight: 700;
}

#pagina-venda .venda-campo input,
#pagina-venda .venda-campo select {
    width: 100%;
    height: 40px;
    padding: 0 12px;
    border: 1px solid #dfe2ed;
    border-radius: 9px;
    background: #f8f9fc;
    color: #242630;
    font-family: inherit;
    font-size: 13px;
    outline: none;
}

#pagina-venda .venda-campo input:focus,
#pagina-venda .venda-campo select:focus {
    border-color: #d83e80;
    box-shadow: 0 0 0 3px rgba(216,62,128,.1);
}

#pagina-venda .venda-busca-linha {
    display: flex;
    align-items: flex-end;
    gap: 9px;
}

#pagina-venda .venda-botao-busca,
#pagina-venda .venda-botao-adicionar,
#pagina-venda .cliente-selecionar {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 40px;
    padding: 0 15px;
    border: 1px solid #f1b8d0;
    border-radius: 9px;
    background: #fff0f6;
    color: #cc3676;
    font-family: inherit;
    font-size: 12px;
    font-weight: 800;
    white-space: nowrap;
    cursor: pointer;
}

#pagina-venda .venda-botao-busca:hover,
#pagina-venda .venda-botao-adicionar:hover,
#pagina-venda .cliente-selecionar:hover {
    background: #fbdde9;
}

#pagina-venda .venda-cliente-acoes {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 11px;
}

#pagina-venda .venda-acao-cliente {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 34px;
    padding: 7px 12px;
    border: 1px solid #e4e5ed;
    border-radius: 8px;
    background: #fff;
    color: #424657;
    font-family: inherit;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
}

#pagina-venda .venda-acao-cliente.destaque {
    color: #cc3676;
    background: #fff0f6;
    border-color: #f1b8d0;
}

#pagina-venda #resultado-cliente {
    margin: 10px 0 0;
    font-size: 12px;
    color: #777d8d;
}

#pagina-venda .venda-selecao-produto {
    display: grid;
    gap: 11px;
    margin-top: 12px;
}

#pagina-venda .venda-adicionar-linha {
    display: flex;
    align-items: flex-end;
    gap: 10px;
}

#pagina-venda .venda-campo-quantidade {
    flex: 0 0 115px;
}

#pagina-venda .venda-mensagem {
    margin: 0;
    font-size: 12px;
    color: #c63c76;
}

#pagina-venda .venda-mensagem:empty {
    display: none;
}

#pagina-venda .venda-table-wrapper {
    width: 100%;
    max-height: 260px;
    overflow: auto;
}

#pagina-venda .venda-table {
    width: 100%;
    border-collapse: collapse;
}

#pagina-venda .venda-table th {
    padding: 11px 8px;
    background: #f7f8fb;
    color: #666b7a;
    font-size: 11px;
    text-align: left;
    white-space: nowrap;
}

#pagina-venda .venda-table td {
    padding: 12px 8px;
    border-bottom: 1px solid #edf0f5;
    font-size: 12px;
}

#pagina-venda .empty-row td {
    padding: 35px 12px;
    text-align: center;
    color: #9295a3;
}

#pagina-venda .venda-resumo {
    display: block;
    margin: 0;
    padding: 18px;
    border-radius: 14px;
    background: linear-gradient(135deg,#242630,#30313d);
    color: #fff;
}

#pagina-venda .venda-resumo-titulo {
    padding-bottom: 12px;
    border-bottom: 1px solid rgba(255,255,255,.13);
}

#pagina-venda .venda-resumo-titulo h2 {
    margin: 0;
    color: #fff;
    font-size: 15px;
}

#pagina-venda .venda-valores {
    display: block;
    margin-top: 13px;
}

#pagina-venda .venda-valores > div {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    margin-bottom: 11px;
    font-size: 12px;
    color: #c9cbd4;
}

#pagina-venda .venda-valores strong {
    color: #fff;
    font-size: 12px;
}

#pagina-venda .venda-valores .venda-total {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 5px;
    padding-top: 13px;
    border-top: 1px solid rgba(255,255,255,.15);
}

#pagina-venda .venda-total strong {
    font-size: 27px;
    font-weight: 800;
}

#pagina-venda .btn-finalizar {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    min-height: 43px;
    margin-top: 14px;
    padding: 10px;
    border-radius: 9px;
    background: linear-gradient(180deg,#f15a9c,#cb2c6d);
    color: #fff;
    font-size: 13px;
    font-weight: 800;
    text-decoration: none;
}

/* JANELA COM A TABELA DE CLIENTES */

#pagina-venda .clientes-modal {
    position: fixed;
    inset: 0;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    background: rgba(20,22,32,.65);
}

#pagina-venda .clientes-modal[hidden] {
    display: none !important;
}

#pagina-venda .clientes-modal-caixa {
    display: flex;
    flex-direction: column;
    width: min(1050px, 96vw);
    max-height: 88vh;
    overflow: hidden;
    border-radius: 15px;
    background: #fff;
    box-shadow: 0 20px 65px rgba(0,0,0,.22);
}

#pagina-venda .clientes-modal-topo {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 20px 24px;
    border-bottom: 1px solid #ececf2;
}

#pagina-venda .clientes-modal-topo h2 {
    margin: 0;
    font-size: 21px;
}

#pagina-venda .clientes-modal-topo p {
    margin: 5px 0 0;
    font-size: 12px;
    color: #9295a3;
}

#pagina-venda .clientes-modal-fechar {
    border: none;
    background: transparent;
    font-size: 28px;
    color: #666b7a;
    cursor: pointer;
}

#pagina-venda .clientes-modal-corpo {
    padding: 20px 24px;
    overflow-y: auto;
}

#pagina-venda .clientes-modal-busca {
    display: flex;
    align-items: flex-end;
    gap: 10px;
    margin-bottom: 18px;
}

#pagina-venda .clientes-lista-wrapper {
    width: 100%;
    overflow-x: auto;
}

#pagina-venda .clientes-lista-tabela {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
}

#pagina-venda .clientes-lista-tabela th {
    padding: 14px 12px;
    background: #f7f8fb;
    color: #606679;
    text-align: left;
    font-weight: 700;
}

#pagina-venda .clientes-lista-tabela td {
    padding: 12px;
    border-bottom: 1px solid #e7e9f0;
    color: #242630;
}

#pagina-venda .clientes-lista-tabela tr:hover td {
    background: #fff8fb;
}

#pagina-venda .clientes-modal-aviso {
    padding: 15px;
    text-align: center;
    font-size: 12px;
    color: #777d8d;
}

#pagina-venda .cliente-selecionar {
    min-height: 32px;
    padding: 0 12px;
}

@media (max-width: 1100px) {
    #pagina-venda .venda-painel-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 650px) {
    #pagina-venda .venda-cabecalho {
        flex-direction: column;
        align-items: flex-start;
    }

    #pagina-venda .venda-identificacao {
        width: 100%;
    }

    #pagina-venda .venda-busca-linha,
    #pagina-venda .clientes-modal-busca {
        flex-direction: column;
        align-items: stretch;
    }

    #pagina-venda .venda-adicionar-linha {
        flex-wrap: wrap;
    }

    #pagina-venda .clientes-modal-corpo {
        padding: 12px;
    }
}
</style>

<div
    class="venda-container venda-moderna"
    id="pagina-venda"
    data-api-clientes="{{ url('/api/clientes/buscar') }}"
    data-api-produtos="{{ url('/api/produtos/buscar') }}"
    data-api-vendas="{{ url('/api/vendas') }}"
    data-url-pagamento="{{ route('pagamentos.create') }}"
    data-url-novo-cliente="{{ route('clientes.create') }}"
    data-url-lista-clientes="{{ route('clientes.index') }}"
>

    <div class="venda-cabecalho">
        <div>
            <span class="venda-etiqueta">ÁREA DE VENDAS</span>
            <h1>Nova venda</h1>
            <p>Realize uma venda de forma rápida e segura.</p>
        </div>

        <div class="venda-identificacao">
            <div>
                <span>Funcionário</span>
                <strong>{{ Auth::user()?->nome ?? 'Funcionário' }}</strong>
            </div>

            <div>
                <span>Venda</span>
                <strong id="numero-venda">Ainda não iniciada</strong>
            </div>
        </div>
    </div>

    <div class="venda-painel-grid">

        <div class="venda-coluna-esquerda">

            {{-- CLIENTE --}}

            <section class="venda-etapa">
                <div class="venda-etapa-topo">
                    <div class="venda-etapa-icone">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="8" r="4"/>
                            <path d="M4 21v-2a8 8 0 0 1 16 0v2"/>
                        </svg>
                    </div>

                    <div class="venda-etapa-titulo">
                        <h2>Cliente</h2>
                        <p>Localize ou selecione um cliente</p>
                    </div>
                </div>

                <div class="venda-etapa-conteudo">

                    <div class="venda-busca-linha">
                        <div class="venda-campo">
                            <label for="cpf">CPF do cliente</label>
                            <input
                                type="text"
                                id="cpf"
                                placeholder="000.000.000-00"
                                maxlength="14"
                                autocomplete="off"
                            >
                        </div>

                        <button
                            type="button"
                            class="venda-botao-busca"
                            id="buscar-cliente"
                        >
                            Buscar
                        </button>
                    </div>

                    <div class="venda-cliente-acoes">
                        <button
                            type="button"
                            class="venda-acao-cliente"
                            id="abrir-lista-clientes"
                        >
                            Ver clientes
                        </button>

                        <a
                            href="{{ route('clientes.create') }}"
                            class="venda-acao-cliente destaque"
                        >
                            + Novo cliente
                        </a>
                    </div>

                    <p id="resultado-cliente" aria-live="polite">
                        Nenhum cliente selecionado.
                    </p>
                </div>
            </section>

            {{-- PRODUTOS --}}

            <section class="venda-etapa">
                <div class="venda-etapa-topo">
                    <div class="venda-etapa-icone">
                        <svg viewBox="0 0 24 24">
                            <circle cx="10.5" cy="10.5" r="6.5"/>
                            <path d="m16 16 5 5"/>
                        </svg>
                    </div>

                    <div class="venda-etapa-titulo">
                        <h2>Buscar produto</h2>
                        <p>Pesquise pelo nome ou código de barras</p>
                    </div>
                </div>

                <div class="venda-etapa-conteudo">
                    <div class="venda-busca-linha">
                        <div class="venda-campo">
                            <label for="produto">
                                Nome ou código de barras
                            </label>
                            <input
                                type="text"
                                id="produto"
                                placeholder="Digite ou escaneie o produto"
                                autocomplete="off"
                            >
                        </div>

                        <button
                            type="button"
                            class="venda-botao-busca"
                            id="buscar-produto"
                        >
                            Buscar
                        </button>
                    </div>

                    <div class="venda-selecao-produto">
                        <div class="venda-campo">
                            <label for="resultado-produtos">
                                Produto e lote disponíveis
                            </label>
                            <select id="resultado-produtos">
                                <option value="">
                                    Pesquise um produto primeiro
                                </option>
                            </select>
                        </div>

                        <div class="venda-adicionar-linha">
                            <div class="venda-campo venda-campo-quantidade">
                                <label for="quantidade-produto">
                                    Quantidade
                                </label>
                                <input
                                    type="number"
                                    id="quantidade-produto"
                                    min="1"
                                    step="1"
                                    value="1"
                                >
                            </div>

                            <button
                                type="button"
                                class="venda-botao-adicionar"
                                id="adicionar-produto"
                            >
                                + Adicionar à venda
                            </button>
                        </div>
                    </div>
                </div>
            </section>

        </div>

        <div class="venda-coluna-direita">

            <p
                id="mensagem-venda"
                class="venda-mensagem"
                role="status"
                aria-live="polite"
            ></p>

            {{-- ITENS --}}

            <section class="venda-etapa">
                <div class="venda-etapa-topo">
                    <div class="venda-etapa-icone">
                        <svg viewBox="0 0 24 24">
                            <path d="M4 7h16v13H4z"/>
                            <path d="M2 4h20v3H2z"/>
                            <path d="M10 12h4"/>
                        </svg>
                    </div>

                    <div class="venda-etapa-titulo">
                        <h2>Itens da venda</h2>
                        <p>Produtos adicionados à venda</p>
                    </div>
                </div>

                <div class="venda-etapa-conteudo">
                    <div class="venda-table-wrapper">
                        <table class="venda-table">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Lote</th>
                                    <th>Quantidade</th>
                                    <th>Valor unitário</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>

                            <tbody id="itens-venda">
                                <tr class="empty-row">
                                    <td colspan="5">
                                        Nenhum produto adicionado.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            {{-- RESUMO --}}

            <section class="venda-resumo">
                <div class="venda-resumo-titulo">
                    <h2>Resumo da venda</h2>
                </div>

                <div class="venda-valores">
                    <div>
                        <span>Subtotal</span>
                        <strong id="subtotal-venda">R$ 0,00</strong>
                    </div>

                    <div>
                        <span>Descontos</span>
                        <strong id="desconto-venda">
                            Calculados ao finalizar
                        </strong>
                    </div>

                    <div class="venda-total">
                        <span>Total parcial</span>
                        <strong id="total-venda">R$ 0,00</strong>
                    </div>
                </div>

                <a
                    href="{{ route('pagamentos.create') }}"
                    class="btn-finalizar"
                    id="ir-pagamento"
                    style="pointer-events:none; opacity:.5;"
                >
                    Ir para pagamento →
                </a>
            </section>

        </div>
    </div>

    {{-- MODAL: CLIENTES CADASTRADOS --}}

    <div
        class="clientes-modal"
        id="modal-clientes"
        role="dialog"
        aria-modal="true"
        aria-labelledby="titulo-modal-clientes"
        hidden
    >
        <div class="clientes-modal-caixa">

            <div class="clientes-modal-topo">
                <div>
                    <h2 id="titulo-modal-clientes">Clientes</h2>
                    <p>
                        Consulte e selecione um cliente cadastrado na farmácia.
                    </p>
                </div>

                <button
                    type="button"
                    class="clientes-modal-fechar"
                    id="fechar-modal-clientes"
                    aria-label="Fechar"
                >
                    &times;
                </button>
            </div>

            <div class="clientes-modal-corpo">

                <div class="clientes-modal-busca">
                    <div class="venda-campo">
                        <label for="pesquisa-modal-clientes">
                            Buscar cliente
                        </label>
                        <input
                            type="text"
                            id="pesquisa-modal-clientes"
                            placeholder="Nome, CPF ou telefone"
                            autocomplete="off"
                        >
                    </div>

                    <button
                        type="button"
                        class="venda-botao-busca"
                        id="executar-pesquisa-clientes"
                    >
                        Buscar
                    </button>
                </div>

                <div class="clientes-lista-wrapper">
                    <table class="clientes-lista-tabela">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nome</th>
                                <th>CPF</th>
                                <th>Telefone</th>
                                <th>E-mail</th>
                                <th>Ações</th>
                            </tr>
                        </thead>

                        <tbody id="corpo-lista-clientes">
                            <tr>
                                <td colspan="6" class="clientes-modal-aviso">
                                    Clique em Ver clientes para carregar.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <p
                    id="aviso-clientes-modal"
                    class="clientes-modal-aviso"
                    hidden
                >
                    Nenhum cliente encontrado.
                </p>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const pagina = document.getElementById('pagina-venda');
    if (!pagina) return;

    const modal = document.getElementById('modal-clientes');
    const abrir = document.getElementById('abrir-lista-clientes');
    const fechar = document.getElementById('fechar-modal-clientes');

    const campoBusca = document.getElementById(
        'pesquisa-modal-clientes'
    );

    const botaoBusca = document.getElementById(
        'executar-pesquisa-clientes'
    );

    const corpo = document.getElementById(
        'corpo-lista-clientes'
    );

    const aviso = document.getElementById(
        'aviso-clientes-modal'
    );

    const campoCpf = document.getElementById('cpf');
    const botaoBuscarCpf = document.getElementById('buscar-cliente');

    let clientes = [];

    function texto(valor) {
        return (valor || '').trim();
    }

    function normalizar(valor) {
        return texto(valor).toLocaleLowerCase('pt-BR');
    }

    function somenteNumeros(valor) {
        return texto(valor).replace(/\D/g, '');
    }

    function mostrarAviso(mensagem) {
        corpo.replaceChildren();

        const linha = document.createElement('tr');
        const celula = document.createElement('td');

        celula.colSpan = 6;
        celula.className = 'clientes-modal-aviso';
        celula.textContent = mensagem;

        linha.appendChild(celula);
        corpo.appendChild(linha);

        aviso.hidden = true;
    }

    function criarCelula(linha, valor) {
        const celula = document.createElement('td');
        celula.textContent = valor || '-';
        linha.appendChild(celula);
        return celula;
    }

    function renderizarClientes() {

        const termo = normalizar(campoBusca.value);
        const numeros = somenteNumeros(termo);

        const encontrados = clientes.filter(function (cliente) {

            const nome = normalizar(cliente.nome);
            const cpf = normalizar(cliente.cpf);
            const telefone = normalizar(cliente.telefone);

            return (
                nome.includes(termo) ||
                cpf.includes(termo) ||
                telefone.includes(termo) ||
                (
                    numeros.length > 0 &&
                    (
                        somenteNumeros(cpf).includes(numeros) ||
                        somenteNumeros(telefone).includes(numeros)
                    )
                )
            );
        });

        corpo.replaceChildren();

        if (!encontrados.length) {
            mostrarAviso('Nenhum cliente encontrado.');
            return;
        }

        encontrados.forEach(function (cliente) {

            const linha = document.createElement('tr');

            criarCelula(linha, cliente.codigo);
            criarCelula(linha, cliente.nome);
            criarCelula(linha, cliente.cpf);
            criarCelula(linha, cliente.telefone);
            criarCelula(linha, cliente.email);

            const acao = document.createElement('td');
            const selecionar = document.createElement('button');

            selecionar.type = 'button';
            selecionar.className = 'cliente-selecionar';
            selecionar.textContent = 'Selecionar';

            selecionar.addEventListener('click', function () {

                campoCpf.value = cliente.cpf;

                campoCpf.dispatchEvent(
                    new Event('input', { bubbles: true })
                );

                modal.hidden = true;

                // Usa a busca de CPF já implementada na venda.
                botaoBuscarCpf.click();
            });

            acao.appendChild(selecionar);
            linha.appendChild(acao);
            corpo.appendChild(linha);
        });
    }

    async function carregarClientes() {

        mostrarAviso('Carregando clientes...');

        try {

            const resposta = await fetch(
                pagina.dataset.urlListaClientes,
                {
                    method: 'GET',
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'text/html'
                    }
                }
            );

            if (!resposta.ok) {
                throw new Error('HTTP ' + resposta.status);
            }

            const html = await resposta.text();

            const documento = new DOMParser().parseFromString(
                html,
                'text/html'
            );

            const linhas = documento.querySelectorAll(
                '#tabela-clientes tbody tr.linha-cliente'
            );

            if (!documento.querySelector('#tabela-clientes')) {
                throw new Error(
                    'Tabela de clientes não encontrada na resposta.'
                );
            }

            clientes = Array.from(linhas).map(function (linha) {

                const celulas = linha.querySelectorAll('td');

                return {
                    codigo: texto(celulas[0]?.textContent),
                    nome: texto(celulas[1]?.textContent),
                    cpf: texto(celulas[2]?.textContent),
                    telefone: texto(celulas[3]?.textContent),
                    email: texto(celulas[4]?.textContent)
                };
            }).filter(function (cliente) {
                return cliente.cpf !== '';
            });

            renderizarClientes();

        } catch (erro) {

            console.error('Erro ao carregar clientes:', erro);

            mostrarAviso(
                'Não foi possível carregar a lista de clientes.'
            );
        }
    }

    abrir.addEventListener('click', function () {

        modal.hidden = false;
        campoBusca.value = '';
        campoBusca.focus();

        carregarClientes();
    });

    fechar.addEventListener('click', function () {
        modal.hidden = true;
    });

    modal.addEventListener('click', function (evento) {
        if (evento.target === modal) {
            modal.hidden = true;
        }
    });

    document.addEventListener('keydown', function (evento) {
        if (evento.key === 'Escape' && !modal.hidden) {
            modal.hidden = true;
        }
    });

    campoBusca.addEventListener('input', renderizarClientes);

    botaoBusca.addEventListener('click', renderizarClientes);

});
</script>

@endsection
