@extends('layouts.app')

@section('title', 'Cadastrar Receita - Sistema Farmácia')
@section('page-title', 'Cadastrar Receita')

@section('content')
<div class="receita-container">

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Não foi possível cadastrar a receita:</strong>
            <ul>
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('receitas.store') }}" method="POST">
        @csrf

        <section class="receita-section">
            <div class="section-header">
                <div>
                    <h2>Item da venda</h2>
                    <p>Selecione o medicamento controlado que está vinculado à venda.</p>
                </div>
            </div>

            <div class="receita-grid">
                <div class="receita-field receita-field-full">
                    <label for="id_item_venda">Medicamento / cliente</label>
                    <select id="id_item_venda" name="id_item_venda" required>
                        <option value="">Selecione um item</option>
                        @forelse ($itens as $item)
                            <option
                                value="{{ $item->id_item_venda }}"
                                @selected(old('id_item_venda') == $item->id_item_venda)
                            >
                                Venda #{{ $item->id_venda }} —
                                {{ $item->medicamento }} —
                                {{ $item->cliente }} —
                                {{ $item->tipo_controle }}
                            </option>
                        @empty
                            <option value="" disabled>
                                Nenhum item de venda controlado disponível
                            </option>
                        @endforelse
                    </select>
                </div>
            </div>
        </section>

        <section class="receita-section">
            <div class="section-header">
                <div>
                    <h2>Dados da receita</h2>
                    <p>Informe os dados presentes na prescrição médica.</p>
                </div>
            </div>

            <div class="receita-grid">
                <div class="receita-field">
                    <label for="numero_receita">Número da receita</label>
                    <input
                        type="text"
                        id="numero_receita"
                        name="numero_receita"
                        maxlength="30"
                        value="{{ old('numero_receita') }}"
                        placeholder="Número (opcional)"
                    >
                </div>

                <div class="receita-field">
                    <label for="data_emissao">Data de emissão</label>
                    <input
                        type="date"
                        id="data_emissao"
                        name="data_emissao"
                        value="{{ old('data_emissao', date('Y-m-d')) }}"
                        max="{{ date('Y-m-d') }}"
                        required
                    >
                </div>

                <div class="receita-field">
                    <label for="validade_receita">Validade</label>
                    <input
                        type="date"
                        id="validade_receita"
                        name="validade_receita"
                        value="{{ old('validade_receita') }}"
                    >
                </div>

                <div class="receita-field">
                    <label for="tipo_receita">Tipo de receita</label>
                    <select id="tipo_receita" name="tipo_receita" required>
                        <option value="">Selecione</option>
                        @foreach ([
                            'branca' => 'Branca',
                            'controle_especial' => 'Controle especial',
                            'azul' => 'Azul',
                            'amarela' => 'Amarela',
                        ] as $valor => $rotulo)
                            <option value="{{ $valor }}" @selected(old('tipo_receita') === $valor)>
                                {{ $rotulo }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </section>

        <section class="receita-section">
            <div class="section-header">
                <div>
                    <h2>Profissional responsável</h2>
                    <p>Informe o médico e o CRM registrados na receita.</p>
                </div>
            </div>

            <div class="receita-grid">
                <div class="receita-field">
                    <label for="medico">Nome do médico</label>
                    <input
                        type="text"
                        id="medico"
                        name="medico"
                        maxlength="150"
                        value="{{ old('medico') }}"
                        placeholder="Nome completo"
                        required
                    >
                </div>

                <div class="receita-field">
                    <label for="crm">CRM</label>
                    <input
                        type="text"
                        id="crm"
                        name="crm"
                        maxlength="20"
                        value="{{ old('crm') }}"
                        placeholder="Número do CRM"
                        required
                    >
                </div>
            </div>
        </section>

        <div class="receita-actions">
            <a href="{{ route('receitas.index') }}" class="btn-outline">
                Cancelar
            </a>

            <button type="submit" class="btn-secondary">
                Salvar Receita
            </button>
        </div>
    </form>
</div>
@endsection