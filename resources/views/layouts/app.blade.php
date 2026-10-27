<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Sistema Farmácia')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <div class="app-container">

        {{-- Menu lateral --}}
        <aside class="sidebar">

            <div class="sidebar-logo">
                <h2>Farmácia</h2>
                <span>Sistema Interno</span>
            </div>

            <nav class="sidebar-menu">
                <a href="{{ route('inicio') }}">Início</a>
                <a href="{{ route('vendas.create') }}">Nova venda</a>
                <a href="{{ route('receitas.create') }}">Receitas</a>
                <a href="{{ route('compras.index') }}">Compras</a>
                <a href="{{ route('produtos.index') }}">Produtos</a>
                <a href="{{ route('categorias.index') }}">Categorias</a>
                <a href="{{ route('fornecedores.index') }}">Fornecedores</a>
                <a href="{{ route('clientes.index') }}">Clientes</a>
                <a href="{{ route('funcionarios.index') }}">Funcionários</a>
                <a href="#">Relatórios</a>
            </nav>

        </aside>

        {{-- Área principal --}}
        <main class="main-content">

            {{-- Cabeçalho --}}
            <header class="topbar">

                <div>
                    <h1>@yield('page-title', 'Início')</h1>
                </div>

                <div class="user-info">
                    <span>Funcionário</span>
                </div>

            </header>

            {{-- Conteúdo específico de cada página --}}
            <section class="page-content">
                @yield('content')
            </section>

        </main>

    </div>

</body>
</html>