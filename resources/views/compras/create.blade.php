
@extends('layouts.app')

@section('title', 'Nova Entrada de Estoque - Sistema Farmácia')
@section('page-title', 'Nova Entrada de Estoque')

@section('content')

<div class="compra-form-container">
    <section class="compra-form-section">

        <div class="section-header">
            <div>
                <h2>Registrar Entrada de Estoque</h2>
                <p>Informe o fornecedor, produto e dados do lote recebido.</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Verifique os dados informados:</strong>
                <ul>
                    @foreach ($errors->all() as $erro)
                        <li>{{ $erro }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('compras.store') }}" method="POST">
            @csrf

            <div class="compra-form-grid">

                <div class="compra-field">
                    <label for="id_fornecedor">Fornecedor</label>
                    <select id="id_fornecedor" name="id_fornecedor" required>
                        <option value="">Selecione um fornecedor</option>
                        @foreach ($fornecedores as $fornecedor)
                            <option value="{{ $fornecedor->id_fornecedor }}"
                                @selected(old('id_fornecedor') == $fornecedor->id_fornecedor)>
                                {{ $fornecedor->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_fornecedor')
                        <small>{{ $message }}</small>
                    @enderror
                </div>

                <div class="compra-field">
                    <label for="id_produto">Produto</label>
                    <select id="id_produto" name="id_produto" required>
                        <option value="">Selecione um produto</option>
                        @foreach ($produtos as $produto)
                            <option value="{{ $produto->id_produto }}"
                                @selected(old('id_produto') == $produto->id_produto)>
                                {{ $produto->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_produto')
                        <small>{{ $message }}</small>
                    @enderror
                </div>

                <div class="compra-field">
                    <label for="numero_lote">Número do lote</label>
                    <input type="text" id="numero_lote" name="numero_lote"
                        value="{{ old('numero_lote') }}" maxlength="30" required>
                    @error('numero_lote')
                        <small>{{ $message }}</small>
                    @enderror
                </div>

                <div class="compra-field">
                    <label for="data_fabricacao">Data de fabricação</label>
                    <input type="date" id="data_fabricacao" name="data_fabricacao"
                        value="{{ old('data_fabricacao') }}" required>
                    @error('data_fabricacao')
                        <small>{{ $message }}</small>
                    @enderror
                </div>

                <div class="compra-field">
                    <label for="data_validade">Data de validade</label>
                    <input type="date" id="data_validade" name="data_validade"
                        value="{{ old('data_validade') }}" required>
                    @error('data_validade')
                        <small>{{ $message }}</small>
                    @enderror
                </div>

                <div class="compra-field">
                    <label for="quantidade">Quantidade recebida</label>
                    <input type="number" id="quantidade" name="quantidade"
                        min="1" value="{{ old('quantidade') }}" required>
                    @error('quantidade')
                        <small>{{ $message }}</small>
                    @enderror
                </div>

                <div class="compra-field">
                    <label for="preco_custo">Preço de custo unitário (R$)</label>
                    <input type="number" id="preco_custo" name="preco_custo"
                        min="0" step="0.01" value="{{ old('preco_custo') }}" required>
                    @error('preco_custo')
                        <small>{{ $message }}</small>
                    @enderror
                </div>

            </div>

            <div class="compra-form-actions">
                <a href="{{ route('compras.index') }}" class="btn-outline">
                    Cancelar
                </a>

                <button type="submit" class="btn-finalizar">
                    Registrar Entrada
                </button>
            </div>
        </form>
    </section>
</div>

@endsection
