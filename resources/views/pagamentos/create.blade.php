@extends('layouts.app')

@section('title', 'Pagamento - Sistema Farmácia')
@section('page-title', 'Pagamento da Venda')

@section('content')
<div class="venda-container" id="pagina-pagamento"
    data-api-vendas="{{ url('/api/vendas') }}"
    data-url-comprovante="{{ route('comprovantes.show') }}"
    data-url-venda="{{ route('vendas.create') }}">

    <section class="venda-info">
        <div>
            <span class="venda-label">Venda</span>
            <strong id="pagamento-numero">Não identificada</strong>
        </div>
        <div>
            <span class="venda-label">Cliente</span>
            <strong id="pagamento-cliente">Não informado</strong>
        </div>
    </section>

    <section class="venda-section">
        <h2>Resumo</h2>
        <div class="venda-valores">
            <div>
                <span>Subtotal</span>
                <strong id="pagamento-subtotal">R$ 0,00</strong>
            </div>
            <div class="venda-total">
                <span>Total parcial</span>
                <strong id="pagamento-total">R$ 0,00</strong>
            </div>
        </div>
    </section>

    <section class="venda-section">
        <h2>Forma de pagamento</h2>

        <div class="venda-field">
            <label for="forma-pagamento">Selecione a forma de pagamento</label>
            <select id="forma-pagamento">
                <option value="1">Dinheiro</option>
                <option value="2">Cartão de Débito</option>
                <option value="3">Cartão de Crédito</option>
                <option value="4">PIX</option>
                <option value="5">Convênio</option>
            </select>
        </div>

        <div class="venda-field" style="margin-top: 15px;">
            <label for="parcelas">Parcelas</label>
            <select id="parcelas">
                @for ($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}">{{ $i }}x</option>
                @endfor
            </select>
        </div>
    </section>

    <p id="mensagem-pagamento" role="status" aria-live="polite"></p>

    <div class="comprovante-actions">
        <a href="{{ route('vendas.create') }}" class="btn-outline">Voltar</a>
        <button type="button" class="btn-finalizar" id="finalizar-venda">
            Confirmar pagamento e finalizar venda
        </button>
    </div>
</div>
@endsection