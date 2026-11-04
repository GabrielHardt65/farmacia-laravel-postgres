@extends('layouts.app')

@section('title', 'Comprovante - Sistema Farmácia')
@section('page-title', 'Comprovante da Venda')

@section('content')
<div class="comprovante-container" id="pagina-comprovante">
    <section class="comprovante-card">
        <div class="comprovante-header">
            <h2>Venda finalizada com sucesso!</h2>
            <p>Confira abaixo os dados da venda.</p>
        </div>

        <div class="comprovante-info">
            <div>
                <span>Venda</span>
                <strong id="comprovante-numero">-</strong>
            </div>
            <div>
                <span>Data</span>
                <strong id="comprovante-data">-</strong>
            </div>
            <div>
                <span>Funcionário</span>
                <strong>Funcionário 1</strong>
            </div>
            <div>
                <span>Cliente</span>
                <strong id="comprovante-cliente">Não informado</strong>
            </div>
        </div>

        <div class="comprovante-produtos">
            <h3>Itens da venda</h3>
            <div class="table-wrapper">
                <table class="sale-table">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Quantidade</th>
                            <th>Valor unitário</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="comprovante-itens">
                        <tr>
                            <td colspan="4" class="empty-table">Nenhum item encontrado.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="comprovante-resumo">
            <div>
                <span>Subtotal</span>
                <strong id="comprovante-subtotal">R$ 0,00</strong>
            </div>
            <div>
                <span>Descontos</span>
                <strong id="comprovante-desconto">R$ 0,00</strong>
            </div>
            <div>
                <span>Forma de pagamento</span>
                <strong id="comprovante-pagamento">-</strong>
            </div>
            <div class="comprovante-total">
                <span>Total pago</span>
                <strong id="comprovante-total">R$ 0,00</strong>
            </div>
        </div>

        <p id="mensagem-comprovante"></p>

        <div class="comprovante-actions">
            <a href="{{ route('inicio') }}" class="btn-outline">Voltar ao início</a>
            <a href="{{ route('vendas.create') }}" class="btn-finalizar"
               id="nova-venda">Nova venda</a>
        </div>
    </section>
</div>
@endsection