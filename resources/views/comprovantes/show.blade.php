@extends('layouts.app')

@section('title', 'Comprovante - Sistema Farmácia')
@section('page-title', 'Comprovante da Venda')

@section('content')

<div class="comprovante-container">

    <section class="comprovante-card">

        <div class="comprovante-header">
            <h2>Venda finalizada com sucesso!</h2>
            <p>Confira abaixo os dados da venda.</p>
        </div>

        <div class="comprovante-info">
            <div>
                <span>Venda</span>
                <strong>#0001</strong>
            </div>

            <div>
                <span>Data</span>
                <strong>{{ date('d/m/Y') }}</strong>
            </div>

            <div>
                <span>Funcionário</span>
                <strong>Funcionário logado</strong>
            </div>

            <div>
                <span>Cliente</span>
                <strong>Cliente não informado</strong>
            </div>
        </div>

        <div class="comprovante-produtos">

            <h3>Itens da Venda</h3>

            <div class="table-wrapper">
                <table class="sale-table">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Quantidade</th>
                            <th>Valor Unitário</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td colspan="4" class="empty-table">
                                Nenhum item informado.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

        <div class="comprovante-resumo">

            <div>
                <span>Subtotal</span>
                <strong>R$ 0,00</strong>
            </div>

            <div>
                <span>Descontos</span>
                <strong>R$ 0,00</strong>
            </div>

            <div>
                <span>Forma de pagamento</span>
                <strong>Não informada</strong>
            </div>

            <div class="comprovante-total">
                <span>Total pago</span>
                <strong>R$ 0,00</strong>
            </div>

        </div>

        <div class="comprovante-actions">

            <a href="{{ route('inicio') }}" class="btn-outline">
                Voltar ao início
            </a>

            <a href="{{ route('vendas.create') }}" class="btn-finalizar">
                Nova venda
            </a>

        </div>

    </section>

</div>

@endsection  