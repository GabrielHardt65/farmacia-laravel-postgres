@extends('layouts.app')

@section('title', 'Clientes - Sistema Farmácia')
@section('page-title', 'Clientes')

@section('content')

    <div class="clientes-container">

        <section class="clientes-section">

            <div class="section-header">
                <div>
                    <h2>Clientes</h2>
                    <p>Consulte e gerencie os clientes cadastrados na farmácia.</p>
                </div>

                <a href="{{ route('clientes.create') }}" class="btn-finalizar">
                    Novo Cliente
                </a>
            </div>

            @if (session('success'))
                <div role="status"
                    style="padding: 14px; margin-bottom: 20px; border-radius: 8px; background-color: #e8f5e9; color: #256029; border: 1px solid #b7dfbd;">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div role="alert"
                    style="padding: 14px; margin-bottom: 20px; border-radius: 8px; background-color: #fff0f0; color: #b42318; border: 1px solid #f3b7b7;">
                    {{ session('error') }}
                </div>
            @endif

            <div class="clientes-filtros">

                <div class="clientes-field">
                    <label for="busca_cliente">Buscar cliente</label>
                    <input type="text" id="busca_cliente" name="busca_cliente" placeholder="Nome, CPF ou telefone">
                </div>

                <button type="button" class="btn-secondary" id="btn-buscar-cliente">
                    Buscar
                </button>

            </div>

            <div class="table-wrapper">

                <table class="sale-table" id="tabela-clientes">

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

                    <tbody>

                        @forelse ($clientes as $cliente)

                            <tr class="linha-cliente">

                                <td>{{ $cliente->id_cliente }}</td>

                                <td>{{ $cliente->nome }}</td>

                                <td>{{ $cliente->cpf }}</td>

                                <td>{{ $cliente->telefone ?? '-' }}</td>

                                <td>{{ $cliente->email ?? '-' }}</td>

                                <td>

                                    <div class="clientes-acoes">


                                        <form action="{{ route('clientes.destroy', $cliente->id_cliente) }}" method="POST"
                                            onsubmit="return confirm('Tem certeza que deseja excluir este cliente? Esta ação não poderá ser desfeita.')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="cliente-btn-acao" title="Excluir cliente"
                                                style="color: #dc3545; border: none; background: transparent; cursor: pointer;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M3 6h18" />
                                                    <path d="M8 6V4h8v2" />
                                                    <path d="M19 6l-1 14H6L5 6" />
                                                    <path d="M10 11v6" />
                                                    <path d="M14 11v6" />
                                                </svg>
                                            </button>
                                        </form>


                                        <a href="{{ route('clientes.show', $cliente->id_cliente) }}" class="cliente-btn-acao"
                                            title="Visualizar cliente" aria-label="Visualizar cliente">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </a>

                                        <a href="{{ route('clientes.edit', $cliente->id_cliente) }}" class="cliente-btn-acao"
                                            title="Editar cliente" aria-label="Editar cliente">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M12 20h9" />
                                                <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L9 17l-4 1 1-4Z" />
                                            </svg>
                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="empty-table">
                                    Nenhum cliente cadastrado.
                                </td>
                            </tr>

                        @endforelse

                        @if ($clientes->isNotEmpty())
                            <tr id="nenhum-resultado" style="display: none;">
                                <td colspan="6" class="empty-table">
                                    Nenhum cliente encontrado para esta busca.
                                </td>
                            </tr>
                        @endif

                    </tbody>

                </table>

            </div>

        </section>

    </div>

    <style>
        .clientes-acoes {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .cliente-btn-acao {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            padding: 0;
            background: #fff0f6;
            color: #cc3676;
            border: 1px solid #f1b8d0;
            border-radius: 8px;
            text-decoration: none;
            transition: 0.2s ease;
        }

        .cliente-btn-acao:hover {
            background: #fbdde9;
            color: #c52e6d;
        }

        .cliente-btn-acao:focus-visible {
            outline: 2px solid #cc3676;
            outline-offset: 2px;
        }

        .cliente-btn-acao svg {
            width: 19px;
            height: 19px;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const campoBusca = document.getElementById('busca_cliente');
            const botaoBusca = document.getElementById('btn-buscar-cliente');
            const linhas = document.querySelectorAll('.linha-cliente');
            const nenhumResultado = document.getElementById('nenhum-resultado');

            function buscarClientes() {

                const termo = campoBusca.value.toLowerCase().trim();
                const termoNumerico = termo.replace(/\D/g, '');

                let encontrados = 0;

                linhas.forEach(function (linha) {

                    const nome = linha.cells[1].textContent.toLowerCase();
                    const cpf = linha.cells[2].textContent.toLowerCase();
                    const telefone = linha.cells[3].textContent.toLowerCase();

                    const corresponde =
                        nome.includes(termo) ||
                        cpf.includes(termo) ||
                        telefone.includes(termo) ||
                        (
                            termoNumerico.length > 0 &&
                            (
                                cpf.replace(/\D/g, '').includes(termoNumerico) ||
                                telefone.replace(/\D/g, '').includes(termoNumerico)
                            )
                        );

                    linha.style.display = corresponde ? '' : 'none';

                    if (corresponde) {
                        encontrados++;
                    }

                });

                if (nenhumResultado) {
                    nenhumResultado.style.display =
                        encontrados === 0 ? '' : 'none';
                }

            }

            campoBusca.addEventListener('input', buscarClientes);
            botaoBusca.addEventListener('click', buscarClientes);

        });
    </script>

@endsection