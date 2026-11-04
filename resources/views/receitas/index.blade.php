
@extends('layouts.app')

@section('title', 'Receitas - Sistema Farmácia')
@section('page-title', 'Receitas')

@section('content')

<div class="receitas-lista-container">
    <section class="receitas-lista-section">

        <div class="section-header receitas-cabecalho">
            <div>
                <h2>Receitas médicas</h2>
                <p>Consulte e acompanhe as receitas cadastradas no sistema.</p>
            </div>

            <a href="{{ route('receitas.create') }}"
               class="btn-finalizar receitas-btn-nova">
                + Nova Receita
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="receitas-consulta">

            <div class="receitas-consulta-titulo">
                <h3>Consultar receitas</h3>
                <p>Pesquise pelo cliente, CPF, número ou data de emissão.</p>
            </div>

            <form method="GET"
                  action="{{ route('receitas.index') }}"
                  class="receitas-lista-filtros">

                <div class="receitas-lista-field receitas-busca">
                    <label for="busca">Buscar receita</label>
                    <input
                        type="text"
                        id="busca"
                        name="busca"
                        value="{{ request('busca') }}"
                        placeholder="Cliente, CPF ou número da receita"
                    >
                </div>

                <div class="receitas-lista-field receitas-data">
                    <label for="data">Data de emissão</label>
                    <input
                        type="date"
                        id="data"
                        name="data"
                        value="{{ request('data') }}"
                    >
                </div>

                <button type="submit" class="btn-finalizar receitas-btn-buscar">
                    Buscar
                </button>

                <a href="{{ route('receitas.index') }}"
                   class="receitas-btn-limpar">
                    Limpar
                </a>

            </form>

            <div class="receitas-tabela-topo">
                <h3>Receitas cadastradas</h3>
                <span>
                    {{ $receitas->total() }}
                    {{ $receitas->total() === 1 ? 'registro' : 'registros' }}
                </span>
            </div>

            <div class="table-wrapper receitas-table-wrapper">
                <table class="sale-table receitas-table">
                    <thead>
                        <tr>
                            <th>Número</th>
                            <th>Data de emissão</th>
                            <th>Cliente</th>
                            <th>CPF</th>
                            <th>Médico</th>
                            <th>Medicamento</th>
                            <th>Tipo</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($receitas as $receita)
                            @php
                                $tipo = $receita->tipo_receita;

                                $tipos = [
                                    'branca' => 'Branca',
                                    'controle_especial' => 'Controle especial',
                                    'azul' => 'Azul',
                                    'amarela' => 'Amarela',
                                ];

                                $tipoTexto = $tipos[$tipo]
                                    ?? ucfirst(str_replace('_', ' ', $tipo ?? ''));

                                $tipoClasse = match ($tipo) {
                                    'branca' => 'receita-tipo-branca',
                                    'controle_especial' => 'receita-tipo-especial',
                                    'azul' => 'receita-tipo-azul',
                                    'amarela' => 'receita-tipo-amarela',
                                    default => 'receita-tipo-branca',
                                };
                            @endphp

                            <tr>
                                <td class="receitas-numero">
                                    {{ $receita->numero_receita ?: '—' }}
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($receita->data_emissao)->format('d/m/Y') }}
                                </td>

                                <td class="receitas-cliente">
                                    {{ $receita->cliente }}
                                </td>

                                <td>
                                    {{ $receita->cpf_paciente ?: '—' }}
                                </td>

                                <td>
                                    {{ $receita->medico }}
                                </td>

                                <td>
                                    {{ $receita->medicamento }}
                                </td>

                                <td>
                                    <span class="receita-tipo {{ $tipoClasse }}">
                                        {{ $tipoTexto }}
                                    </span>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="7" class="empty-table">
                                    Nenhuma receita encontrada.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="receitas-paginacao">
                {{ $receitas->links() }}
            </div>

        </div>

    </section>
</div>

@endsection
