@extends('layouts.home')

@section('title', 'Início - Sistema Farmácia')

@section('content')

    <section class="home-welcome">
        <h1>Bem-vindo ao Sistema da Farmácia</h1>
        <p>Selecione uma opção para começar.</p>
    </section>

    <section class="home-menu">

        <a href="{{ route('vendas.create') }}" class="home-card">
            <h3>Nova Venda</h3>
            <p>Iniciar uma nova venda</p>
        </a>

        <a href="{{ route('receitas.create') }}" class="home-card">
            <h3>Receitas</h3>
            <p>Consultar receitas cadastradas</p>
        </a>

        <a href="{{ route('compras.index') }}" class="home-card">
            <h3>Compras</h3>
            <p>Registrar entrada de produtos</p>
        </a>

        <a href="{{ route('produtos.index') }}" class="home-card">
            <h3>Produtos</h3>
            <p>Consultar e cadastrar produtos</p>
        </a>

        <a href="{{ route('categorias.index') }}" class="home-card">
            <h3>Cadastros</h3>
            <p>Categorias e fornecedores</p>
        </a>

        <a href="{{ route('clientes.index') }}" class="home-card">
            <h3>Clientes</h3>
            <p>Consultar e cadastrar clientes</p>
        </a>

        <a href="{{ route('funcionarios.index') }}">
            <h3>Funcionários</h3>
            <p>Gerenciar funcionários</p>
        </a>

        <a href="{{ route('relatorios.index') }}">
            <h3>Relatórios</h3>
            <p>Visualizar relatórios da farmácia</p>
        </a>

    </section>

@endsection