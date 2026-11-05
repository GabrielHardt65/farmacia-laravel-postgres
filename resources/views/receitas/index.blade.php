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
                            <th>Ações</th>
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

                                <td>
                                    <div class="receitas-acoes">

                                        <a href="{{ route('receitas.show', $receita->id_receita) }}"
                                           class="receitas-btn-visualizar"
                                           title="Visualizar receita"
                                           aria-label="Visualizar receita">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 width="19"
                                                 height="19"
                                                 viewBox="0 0 24 24"
                                                 fill="none"
                                                 stroke="currentColor"
                                                 stroke-width="2"
                                                 stroke-linecap="round"
                                                 stroke-linejoin="round">
                                                <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                        </a>

                                        <form method="POST"
                                              action="{{ route('receitas.destroy', $receita->id_receita) }}"
                                              onsubmit="return confirm('Tem certeza que deseja excluir esta receita? Esta ação não poderá ser desfeita.');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="receitas-btn-excluir"
                                                    title="Excluir receita"
                                                    aria-label="Excluir receita">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     width="19"
                                                     height="19"
                                                     viewBox="0 0 24 24"
                                                     fill="none"
                                                     stroke="currentColor"
                                                     stroke-width="2"
                                                     stroke-linecap="round"
                                                     stroke-linejoin="round">
                                                    <path d="M3 6h18"/>
                                                    <path d="M8 6V4c0-.55.45-1 1-1h6c.55 0 1 .45 1 1v2"/>
                                                    <path d="M19 6l-1 14c-.05.55-.45 1-1 1H7c-.55 0-.95-.45-1-1L5 6"/>
                                                    <path d="M10 11v6"/>
                                                    <path d="M14 11v6"/>
                                                </svg>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="8" class="empty-table">
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

<style>
    .receitas-acoes {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        flex-wrap: nowrap;
    }

    .receitas-acoes form {
        margin: 0;
        display: flex;
        align-items: center;
    }

    .receitas-btn-visualizar,
    .receitas-btn-excluir {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        padding: 0;
        border-radius: 8px;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .receitas-btn-visualizar {
        background: #f7f3f0;
        color: #7a1e3a;
        border: 1px solid #e8d5dc;
    }

    .receitas-btn-visualizar:hover {
        background: #7a1e3a;
        color: #fff;
        transform: translateY(-2px);
    }

    .receitas-btn-excluir {
        background: #fff1f0;
        color: #b42318;
        border: 1px solid #fecaca;
    }

    .receitas-btn-excluir:hover {
        background: #b42318;
        color: #fff;
        transform: translateY(-2px);
    }

    .receitas-btn-visualizar svg,
    .receitas-btn-excluir svg {
        width: 19px;
        height: 19px;
        flex-shrink: 0;
    }

    .receitas-table-wrapper {
        overflow-x: auto;
    }

    .receitas-table th:last-child,
    .receitas-table td:last-child {
        min-width: 115px;
        text-align: center;
    }

    @media (max-width: 600px) {
        .receitas-acoes {
            gap: 6px;
        }

        .receitas-btn-visualizar,
        .receitas-btn-excluir {
            width: 34px;
            height: 34px;
        }
    }
</style>

@endsection
