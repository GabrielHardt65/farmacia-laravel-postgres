<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema Farmácia')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Cabeçalho: identidade visual da tela de login */
        .dashboard-main { min-width: 0; }
        .dashboard-topbar {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            gap: 20px;
            min-height: 90px;
            padding: 10px 28px !important;
            background: #242630 !important;
            border-bottom: 5px solid #d83e80 !important;
            box-shadow: 0 7px 22px rgba(36,38,48,.12);
            box-sizing: border-box;
        }
        .dashboard-topbar .topbar-left,
        .dashboard-topbar .topbar-actions {
            display: flex !important;
            align-items: center !important;
            gap: 16px;
            min-width: 0;
        }
        .topbar-brand { display: flex; align-items: center; gap: 12px; min-width: 0; }
        .topbar-mascot {
            display: block; width: 74px; height: 74px;
            object-fit: cover; object-position: center;
            border-radius: 12px; flex-shrink: 0;
        }
        .topbar-brand-cross {
            display: flex; align-items: center; justify-content: center;
            width: 44px; height: 44px; flex-shrink: 0;
            background: linear-gradient(135deg, #f15c9e, #d72c77);
            border-radius: 12px; color: #fff; font-size: 31px; font-weight: 800;
        }
        .dashboard-topbar .topbar-title { display: flex; flex-direction: column; gap: 3px; }
        .dashboard-topbar .topbar-title strong {
            color: #fff !important; font-size: 23px !important;
            line-height: 1.05; letter-spacing: .3px;
        }
        .dashboard-topbar .topbar-title span {
            color: #d8d8e1 !important; font-size: 10px !important;
            letter-spacing: 2px;
        }
        .dashboard-topbar .topbar-user { display: flex; align-items: center; gap: 10px; }
        .dashboard-topbar .topbar-avatar {
            display: flex; align-items: center; justify-content: center;
            width: 42px; height: 42px; border-radius: 50%;
            background: #fbe2ee !important; color: #d83e80 !important;
            font-weight: 800; flex-shrink: 0;
        }
        .dashboard-topbar .topbar-user-info { display: flex; flex-direction: column; gap: 3px; }
        .dashboard-topbar .topbar-user-info strong {
            color: #fff !important; font-size: 13px !important;
        }
        .dashboard-topbar .topbar-user-info span {
            color: #c8c7d1 !important; font-size: 11px !important;
        }
        .dashboard-topbar .topbar-logout {
            border: 0 !important; border-radius: 10px !important;
            background: linear-gradient(135deg, #ee599b, #d52e79) !important;
            color: #fff !important; font-weight: 700 !important;
            padding: 11px 22px !important; cursor: pointer;
        }
        .dashboard-topbar .topbar-logout:hover { filter: brightness(1.08); }
        .dashboard-topbar .sidebar-toggle {
            color: #fff !important; background: rgba(255,255,255,.1) !important;
            border: 1px solid rgba(255,255,255,.15) !important;
            border-radius: 8px; padding: 8px 11px; cursor: pointer;
        }
        @media (max-width: 700px) {
            .dashboard-topbar { padding: 9px 14px !important; min-height: 72px; gap: 8px; }
            .topbar-mascot { width: 50px; height: 55px; }
            .topbar-brand-cross { width: 34px; height: 34px; font-size: 24px; }
            .dashboard-topbar .topbar-title strong { font-size: 16px !important; }
            .dashboard-topbar .topbar-title span { font-size: 8px !important; letter-spacing: .8px; }
            .dashboard-topbar .topbar-user-info { display: none; }
            .dashboard-topbar .topbar-avatar { width: 32px; height: 32px; }
            .dashboard-topbar .topbar-logout { padding: 9px 12px !important; }
            .dashboard-topbar .topbar-left, .dashboard-topbar .topbar-actions { gap: 7px !important; }
        }
        @media (max-width: 440px) {
            .topbar-brand-cross { display: none; }
            .dashboard-topbar .topbar-avatar { display: none; }
        }
    </style>

    <style>
    .topbar-brand {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .topbar-mascote {
        width: 75px;
        height: 75px;
        object-fit: contain;
    }
</style>

</head>
<body class="dashboard-body">
    <div class="dashboard-layout">
        {{-- O menu lateral permanece disponível fora da página inicial. --}}
        @if (!request()->routeIs('inicio'))
            <aside class="dashboard-sidebar" id="dashboard-sidebar">
                <div class="sidebar-brand">
                    <div class="sidebar-brand-icon">✚</div>
                    <div class="topbar-brand">
    <img
        src="{{ asset('images/mascote-farmacia.png') }}"
        alt="Mascote da Farmácia"
        class="topbar-mascote"
    >

    <div class="topbar-title">
        <strong>FARMÁCIA</strong>
        <span>SISTEMA DE GESTÃO</span>
    </div>
</div>
                </div>
                <nav class="sidebar-nav" aria-label="Menu principal">
                    <a href="{{ route('inicio') }}" class="sidebar-link {{ request()->routeIs('inicio') ? 'active' : '' }}"><span class="sidebar-icon">⌂</span><span>Início</span></a>
                    <a href="{{ route('vendas.create') }}" class="sidebar-link {{ request()->routeIs('vendas.*') ? 'active' : '' }}"><span class="sidebar-icon">🛒</span><span>Nova Venda</span></a>
                    <a href="{{ route('receitas.index') }}" class="sidebar-link {{ request()->routeIs('receitas.*') ? 'active' : '' }}"><span class="sidebar-icon">▤</span><span>Receitas</span></a>
                    <a href="{{ route('compras.index') }}" class="sidebar-link {{ request()->routeIs('compras.*') ? 'active' : '' }}"><span class="sidebar-icon">▣</span><span>Compras</span></a>
                    <a href="{{ route('produtos.index') }}" class="sidebar-link {{ request()->routeIs('produtos.*') ? 'active' : '' }}"><span class="sidebar-icon">⬡</span><span>Produtos</span></a>
                    <a href="{{ route('categorias.index') }}" class="sidebar-link {{ request()->routeIs('categorias.*', 'fornecedores.*') ? 'active' : '' }}"><span class="sidebar-icon">▦</span><span>Cadastros</span></a>
                    <a href="{{ route('clientes.index') }}" class="sidebar-link {{ request()->routeIs('clientes.*') ? 'active' : '' }}"><span class="sidebar-icon">♙</span><span>Clientes</span></a>
                    <a href="{{ route('funcionarios.index') }}" class="sidebar-link {{ request()->routeIs('funcionarios.*') ? 'active' : '' }}"><span class="sidebar-icon">♙</span><span>Funcionários</span></a>
                    <a href="{{ route('relatorios.index') }}" class="sidebar-link {{ request()->routeIs('relatorios.*') ? 'active' : '' }}"><span class="sidebar-icon">▥</span><span>Relatórios</span></a>
                </nav>
                <div class="sidebar-bottom">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="sidebar-logout"><span>⇥</span><span>Sair do sistema</span></button>
                    </form>
                </div>
            </aside>
        @endif

        <div class="dashboard-main">
            <header class="dashboard-topbar">
                <div class="topbar-left">
                    @if (!request()->routeIs('inicio'))
                        <button type="button" class="sidebar-toggle" id="sidebar-toggle"
                                aria-label="Abrir ou fechar menu" aria-expanded="false"
                                aria-controls="dashboard-sidebar">☰</button>
                    @endif
                    <div class="topbar-brand">
                        <img class="topbar-mascot" src="{{ asset('images/mascote-farmacia.png') }}"
                             alt="Mascote farmacêutica" onerror="this.style.display='none'">
                        <div class="topbar-brand-cross" aria-hidden="true">✚</div>
                        <div class="topbar-title">
                            <strong>FARMÁCIA</strong>
                            <span>SISTEMA DE GESTÃO</span>
                        </div>
                    </div>
                </div>
                <div class="topbar-actions">
                    <div class="topbar-user">
                        <div class="topbar-avatar">{{ mb_strtoupper(mb_substr(Auth::user()?->nome ?? 'F', 0, 1)) }}</div>
                        <div class="topbar-user-info">
                            <strong>{{ Auth::user()?->nome ?? 'Funcionário' }}</strong>
                            <span>{{ Auth::user()?->cargo ?? 'Sistema Interno' }}</span>
                        </div>
                    </div>
                    @if (request()->routeIs('inicio'))
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="topbar-logout">Sair</button>
                        </form>
                    @endif
                </div>
            </header>
            <main class="dashboard-content">@yield('content')</main>
        </div>
    </div>
    <script>
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const dashboardSidebar = document.getElementById('dashboard-sidebar');
        if (sidebarToggle && dashboardSidebar) {
            sidebarToggle.addEventListener('click', function () {
                const aberto = dashboardSidebar.classList.toggle('sidebar-open');
                sidebarToggle.setAttribute('aria-expanded', String(aberto));
            });
        }
    </script>
</body>
</html>
