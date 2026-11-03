
@extends('layouts.home')

@section('title', 'Início - Sistema Farmácia')

@section('content')

<div class="dashboard-home">

    <section class="dashboard-welcome">
        <div>

            <h1>
                Olá, {{ Auth::user()?->nome ?? 'Funcionário' }}!
            </h1>

            <p>
                Bem-vindo ao sistema de gestão da farmácia.
                Selecione uma opção para começar.
            </p>
        </div>

        <div class="dashboard-welcome-icon" aria-hidden="true">
            ✚
        </div>
    </section>

    <section class="dashboard-section">

        <div class="dashboard-section-heading">
            <div>
                <h2>Gestão da Farmácia</h2>
            </div>
        </div>

        <div class="dashboard-grid">

            {{-- NOVA VENDA --}}
            <a href="{{ route('vendas.create') }}" class="dashboard-card">
                <div class="dashboard-card-icon">🛒</div>
                <div class="dashboard-card-content">
                    <h3>Nova Venda</h3>
                    <p>Iniciar uma nova venda</p>
                </div>
                <span class="dashboard-card-arrow">›</span>
            </a>

            {{-- RECEITAS --}}
            <a href="{{ route('receitas.index') }}" class="dashboard-card">
                <div class="dashboard-card-icon">▤</div>
                <div class="dashboard-card-content">
                    <h3>Receitas</h3>
                    <p>Consultar receitas cadastradas</p>
                </div>
                <span class="dashboard-card-arrow">›</span>
            </a>

            {{-- PRODUTOS --}}
            <a href="{{ route('produtos.index') }}" class="dashboard-card">
                <div class="dashboard-card-icon">⬡</div>
                <div class="dashboard-card-content">
                    <h3>Produtos</h3>
                    <p>Consultar e cadastrar produtos</p>
                </div>
                <span class="dashboard-card-arrow">›</span>
            </a>

            {{-- CLIENTES --}}
            <a href="{{ route('clientes.index') }}" class="dashboard-card">
                <div class="dashboard-card-icon">♙</div>
                <div class="dashboard-card-content">
                    <h3>Clientes</h3>
                    <p>Consultar e cadastrar clientes</p>
                </div>
                <span class="dashboard-card-arrow">›</span>
            </a>

            {{-- FUNCIONÁRIOS --}}
            <a href="{{ route('funcionarios.index') }}" class="dashboard-card">
                <div class="dashboard-card-icon">♙</div>
                <div class="dashboard-card-content">
                    <h3>Funcionários</h3>
                    <p>Gerenciar funcionários</p>
                </div>
                <span class="dashboard-card-arrow">›</span>
            </a>

            {{-- RELATÓRIOS --}}
            <a href="{{ route('relatorios.index') }}" class="dashboard-card">
                <div class="dashboard-card-icon">▥</div>
                <div class="dashboard-card-content">
                    <h3>Relatórios</h3>
                    <p>Visualizar relatórios da farmácia</p>
                </div>
                <span class="dashboard-card-arrow">›</span>
            </a>

        </div>

    </section>

</div>

@endsection
