@extends('layouts.app')

@section('title', 'Entradas de Estoque - Sistema Farmácia')
@section('page-title', 'Entradas de Estoque')

@section('content')

<div class="compras-container">
    <section class="compras-section">

        <div class="section-header">
            <div>
                <h2>Entradas de Estoque</h2>
                <p>Consulte os lotes e registre produtos recebidos pela farmácia.</p>
            </div>

            <a href="{{ route('compras.create') }}" class="btn-finalizar">
                Nova Entrada
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-wrapper">
            <table class="sale-table">
                <thead>
                    <tr>
                        <th>Nº</th>
                        <th>Lote</th>
                        <th>Produto</th>
                        <th>Fornecedor</th>
                        <th>Fabricação</th>
                        <th>Validade</th>
                        <th>Recebido</th>
                        <th>Estoque atual</th>
                        <th>Custo unitário</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($lotes as $lote)
                        <tr>
                            <td>{{ $lote->id_lote }}</td>
                            <td>{{ $lote->numero_lote }}</td>
                            <td>{{ $lote->produto_nome }}</td>
                            <td>{{ $lote->fornecedor_nome }}</td>
                            <td>{{ \Carbon\Carbon::parse($lote->data_fabricacao)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($lote->data_validade)->format('d/m/Y') }}</td>
                            <td>{{ $lote->quantidade_recebida }}</td>
                            <td>{{ $lote->quantidade_atual }}</td>
                            <td>R$ {{ number_format((float) $lote->preco_custo, 2, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="empty-table">
                                Nenhuma entrada de estoque registrada.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $lotes->links() }}

    </section>
</div>

@endsection
