
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Sistema Farmácia')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="dashboard-body">

    <div class="dashboard-layout">

        {{-- MENU LATERAL: NÃO APARECE NA TELA INICIAL --}}
        @if (!request()->routeIs('inicio'))

            <aside class="dashboard-sidebar" id="dashboard-sidebar">

                <div class="sidebar-brand">
                    <div class="sidebar-brand-icon">✚</div>

                    <div>
                        <strong>FARMÁCIA</strong>
                        <span>SISTEMA DE GESTÃO</span>
                    </div>
                </div>

                <nav class="sidebar-nav" aria-label="Menu principal">

                    <a href="{{ route('inicio') }}"
                       class="sidebar-link {{ request()->routeIs('inicio') ? 'active' : '' }}">
                        <span class="sidebar-icon">⌂</span>
                        <span>Início</span>
                    </a>

                    <a href="{{ route('vendas.create') }}"
                       class="sidebar-link {{ request()->routeIs('vendas.*') ? 'active' : '' }}">
                        <span class="sidebar-icon">🛒</span>
                        <span>Nova Venda</span>
                    </a>

                    <a href="{{ route('receitas.index') }}"
                       class="sidebar-link {{ request()->routeIs('receitas.*') ? 'active' : '' }}">
                        <span class="sidebar-icon">▤</span>
                        <span>Receitas</span>
                    </a>

                    <a href="{{ route('compras.index') }}"
                       class="sidebar-link {{ request()->routeIs('compras.*') ? 'active' : '' }}">
                        <span class="sidebar-icon">▣</span>
                        <span>Compras</span>
                    </a>

                    <a href="{{ route('produtos.index') }}"
                       class="sidebar-link {{ request()->routeIs('produtos.*') ? 'active' : '' }}">
                        <span class="sidebar-icon">⬡</span>
                        <span>Produtos</span>
                    </a>

                    <a href="{{ route('categorias.index') }}"
                       class="sidebar-link {{ request()->routeIs('categorias.*', 'fornecedores.*') ? 'active' : '' }}">
                        <span class="sidebar-icon">▦</span>
                        <span>Cadastros</span>
                    </a>

                    <a href="{{ route('clientes.index') }}"
                       class="sidebar-link {{ request()->routeIs('clientes.*') ? 'active' : '' }}">
                        <span class="sidebar-icon">♙</span>
                        <span>Clientes</span>
                    </a>

                    <a href="{{ route('funcionarios.index') }}"
                       class="sidebar-link {{ request()->routeIs('funcionarios.*') ? 'active' : '' }}">
                        <span class="sidebar-icon">♙</span>
                        <span>Funcionários</span>
                    </a>

                    <a href="{{ route('relatorios.index') }}"
                       class="sidebar-link {{ request()->routeIs('relatorios.*') ? 'active' : '' }}">
                        <span class="sidebar-icon">▥</span>
                        <span>Relatórios</span>
                    </a>

                </nav>

                <div class="sidebar-bottom">

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf

                        <button type="submit" class="sidebar-logout">
                            <span>⇥</span>
                            <span>Sair do sistema</span>
                        </button>
                    </form>

                </div>

            </aside>

        @endif

        {{-- ÁREA PRINCIPAL --}}
        <div class="dashboard-main">

            <header class="dashboard-topbar">

                <div class="topbar-left">

                    {{-- BOTÃO DO MENU APENAS NAS OUTRAS TELAS --}}
                    @if (!request()->routeIs('inicio'))

                        <button
                            type="button"
                            class="sidebar-toggle"
                            id="sidebar-toggle"
                            aria-label="Abrir ou fechar menu"
                            aria-expanded="false"
                            aria-controls="dashboard-sidebar"
                        >
                            ☰
                        </button>

                    @endif

                    <div class="topbar-title">
                        <strong>FARMÁCIA</strong>
                        <span>SISTEMA DE GESTÃO</span>
                    </div>

                </div>

                <div class="topbar-actions">

                    <div class="topbar-user">

                        <div class="topbar-avatar">
                            {{ strtoupper(substr(Auth::user()?->nome ?? 'F', 0, 1)) }}
                        </div>

                        <div class="topbar-user-info">
                            <strong>
                                {{ Auth::user()?->nome ?? 'Funcionário' }}
                            </strong>

                            <span>
                                {{ Auth::user()?->cargo ?? 'Sistema Interno' }}
                            </span>
                        </div>

                    </div>

                    {{-- SAIR NO CABEÇALHO APENAS NA TELA INICIAL --}}
                    @if (request()->routeIs('inicio'))

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf

                            <button type="submit" class="topbar-logout">
                                Sair
                            </button>
                        </form>

                    @endif

                </div>

            </header>

            <main class="dashboard-content">
                @yield('content')
            </main>

        </div>

    </div>

    <script>
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const dashboardSidebar = document.getElementById('dashboard-sidebar');

        if (sidebarToggle && dashboardSidebar) {
            sidebarToggle.addEventListener('click', function () {
                const aberto = dashboardSidebar.classList.toggle('sidebar-open');

                sidebarToggle.setAttribute(
                    'aria-expanded',
                    String(aberto)
                );
            });
        }
    </script>

</body>
</html>
