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
                                    <span style="color: #888;">
                                        -
                                    </span>
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
                        (termoNumerico.length > 0 && (
                            cpf.replace(/\D/g, '').includes(termoNumerico) ||
                            telefone.replace(/\D/g, '').includes(termoNumerico)
                        ));

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