@extends('layouts.app')

@section('title', 'Receitas - Sistema Farmácia')
@section('page-title', 'Receitas')

@section('content')
<div class="receitas-lista-container">
    <section class="receitas-lista-section">

        <div class="section-header">
            <div>
                <h2>Receitas médicas</h2>
                <p>Consulte as receitas cadastradas no sistema.</p>
            </div>

            <a href="{{ route('receitas.create') }}" class="btn-finalizar">
                + Nova Receita
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="GET" action="{{ route('receitas.index') }}"
              class="receitas-lista-filtros">

            <div class="receitas-lista-field">
                <label for="busca">Buscar</label>
                <input
                    type="text"
                    id="busca"
                    name="busca"
                    value="{{ request('busca') }}"
                    placeholder="Cliente, CPF ou número da receita"
                >
            </div>

            <div class="receitas-lista-field">
                <label for="data">Data de emissão</label>
                <input
                    type="date"
                    id="data"
                    name="data"
                    value="{{ request('data') }}"
                >
            </div>

            <button type="submit" class="btn-secondary">
                Buscar
            </button>

            <a href="{{ route('receitas.index') }}" class="btn-outline">
                Limpar
            </a>
        </form>

        <div class="table-wrapper">
            <table class="sale-table">
                <thead>
                    <tr>
                        <th>Número</th>
                        <th>Data</th>
                        <th>Cliente</th>
                        <th>CPF</th>
                        <th>Médico</th>
                        <th>Medicamento</th>
                        <th>Tipo</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($receitas as $receita)
                        <tr>
                            <td>{{ $receita->numero_receita ?: '—' }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($receita->data_emissao)->format('d/m/Y') }}
                            </td>
                            <td>{{ $receita->cliente }}</td>
                            <td>{{ $receita->cpf_paciente ?: '—' }}</td>
                            <td>{{ $receita->medico }}</td>
                            <td>{{ $receita->medicamento }}</td>
                            <td>{{ str_replace('_', ' ', ucfirst($receita->tipo_receita)) }}</td>
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

    </section>
</div>
@endsection