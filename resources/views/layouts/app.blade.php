
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Sistema Farmácia')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .app-container {
            display: flex !important;
            width: 100%;
            min-height: 100vh;
            align-items: stretch;
        }

        .app-container > .sidebar {
            position: relative !important;
            left: auto !important;
            right: auto !important;
            top: auto !important;
            bottom: auto !important;
            transform: none !important;
            flex: 0 0 270px;
            width: 270px !important;
            min-width: 270px;
            max-width: 270px;
            min-height: 100vh;
            height: auto;
            margin: 0 !important;
            background: #242530;
            padding: 26px 16px;
            display: flex;
            flex-direction: column;
            gap: 24px;
            overflow-y: auto;
            box-sizing: border-box;
            z-index: 1;
        }

        .app-container > .main-content {
            position: relative !important;
            left: auto !important;
            right: auto !important;
            top: auto !important;
            transform: none !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
            flex: 1 1 0;
            min-width: 0;
            width: auto !important;
            max-width: none;
            box-sizing: border-box;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 8px 8px 22px;
            border-bottom: 1px solid #41414f;
        }

        .sidebar-mascote {
            width: 52px;
            height: 52px;
            min-width: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fbe0ec;
            border-radius: 15px;
            color: #c63579;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
        }

        .sidebar-mascote svg {
            width: 30px;
            height: 30px;
        }

        .sidebar-logo-texto h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.2;
        }

        .sidebar-logo-texto span {
            display: block;
            margin-top: 5px;
            font-size: 12px;
            color: #c8c5d2;
        }

        .sidebar-menu {
            display: flex;
            flex-direction: column;
            gap: 22px;
        }

        .sidebar-grupo {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .sidebar-grupo-titulo {
            margin: 0 10px 5px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #a8a2b7;
        }

        .sidebar-menu .sidebar-link {
            display: flex;
            align-items: center;
            gap: 13px;
            width: 100%;
            min-height: 45px;
            padding: 11px 13px;
            border-radius: 11px;
            background: #32323e;
            color: #f6f2f7;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            box-sizing: border-box;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .sidebar-menu .sidebar-link:hover {
            background: #494050;
            color: #ffffff;
            transform: translateX(3px);
        }

        .sidebar-menu .sidebar-link.ativo {
            background: #b93675;
            color: #ffffff;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(185, 54, 117, 0.22);
        }

        .sidebar-link svg {
            width: 19px;
            height: 19px;
            min-width: 19px;
            stroke: #f1c3d9;
            stroke-width: 1.8;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .sidebar-link.ativo svg {
            stroke: #ffffff;
        }

        .sidebar-link .sidebar-seta {
            margin-left: auto;
            width: 15px;
            height: 15px;
            stroke: #ffffff;
        }

        .sidebar-rodape {
            margin-top: auto;
            padding: 15px 10px 0;
            border-top: 1px solid #41414f;
            text-align: center;
            color: #858394;
            font-size: 11px;
        }

        .app-container > .main-content > .topbar {
            width: 100%;
            box-sizing: border-box;
        }

        .app-container > .main-content > .page-content {
            width: 100%;
            min-width: 0;
            box-sizing: border-box;
        }

        @media (max-width: 900px) {
            .app-container > .sidebar {
                flex-basis: 225px;
                width: 225px !important;
                min-width: 225px;
                max-width: 225px;
                padding: 20px 12px;
            }

            .sidebar-logo-texto h2 {
                font-size: 20px;
            }
        }

        @media (max-width: 650px) {
            .app-container {
                flex-direction: column !important;
            }

            .app-container > .sidebar {
                flex: 0 0 auto;
                width: 100% !important;
                min-width: 0;
                max-width: none;
                min-height: auto;
                gap: 15px;
            }

            .app-container > .main-content {
                width: 100% !important;
                flex: 1 1 auto;
            }

            .sidebar-menu {
                gap: 15px;
            }

            .sidebar-grupo {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .sidebar-grupo-titulo {
                grid-column: 1 / -1;
            }

            .sidebar-menu .sidebar-link {
                font-size: 12px;
                padding: 10px;
                gap: 8px;
            }

            .sidebar-rodape {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="app-container">

        <aside class="sidebar">

            <div class="sidebar-logo">

                <div class="sidebar-mascote">
                    <svg viewBox="0 0 24 24">
                        <g transform="rotate(-45 12 12)">
                            <rect x="5" y="2" width="14" height="20" rx="7" fill="#ffffff" stroke="#c63579" stroke-width="1.5"/>
                            <path d="M5 12h14v3a7 7 0 0 1-14 0z" fill="#e975a9"/>
                            <path d="M5 12h14" stroke="#c63579" stroke-width="1.5"/>
                        </g>
                    </svg>
                </div>

                <div class="sidebar-logo-texto">
                    <h2>Farmácia</h2>
                    <span>Sistema Interno</span>
                </div>

            </div>

            <nav class="sidebar-menu">

                <div class="sidebar-grupo">
                    <p class="sidebar-grupo-titulo">Principal</p>

                    <a href="{{ route('inicio') }}"
                       class="sidebar-link {{ request()->routeIs('inicio') ? 'ativo' : '' }}"
                       @if(request()->routeIs('inicio')) aria-current="page" @endif>
                        <svg viewBox="0 0 24 24">
                            <path d="m3 10 9-7 9 7"/>
                            <path d="M5 9v12h14V9"/>
                            <path d="M9 21v-7h6v7"/>
                        </svg>
                        <span>Início</span>
                        @if(request()->routeIs('inicio'))
                            <svg class="sidebar-seta" viewBox="0 0 24 24">
                                <path d="m9 18 6-6-6-6"/>
                            </svg>
                        @endif
                    </a>

                    <a href="{{ route('vendas.create') }}"
                       class="sidebar-link {{ request()->routeIs('vendas.*') ? 'ativo' : '' }}"
                       @if(request()->routeIs('vendas.*')) aria-current="page" @endif>
                        <svg viewBox="0 0 24 24">
                            <circle cx="9" cy="21" r="1"/>
                            <circle cx="20" cy="21" r="1"/>
                            <path d="M1 1h4l2.5 13h12l3-9H6"/>
                        </svg>
                        <span>Nova venda</span>
                        @if(request()->routeIs('vendas.*'))
                            <svg class="sidebar-seta" viewBox="0 0 24 24">
                                <path d="m9 18 6-6-6-6"/>
                            </svg>
                        @endif
                    </a>
                </div>

                <div class="sidebar-grupo">
                    <p class="sidebar-grupo-titulo">Gerenciamento</p>

                    <a href="{{ route('compras.index') }}"
                       class="sidebar-link {{ request()->routeIs('compras.*') ? 'ativo' : '' }}"
                       @if(request()->routeIs('compras.*')) aria-current="page" @endif>
                        <svg viewBox="0 0 24 24">
                            <path d="m3 7 9-4 9 4-9 4-9-4z"/>
                            <path d="M3 7v10l9 4 9-4V7"/>
                            <path d="M12 11v10"/>
                        </svg>
                        <span>Estoque</span>
                        @if(request()->routeIs('compras.*'))
                            <svg class="sidebar-seta" viewBox="0 0 24 24">
                                <path d="m9 18 6-6-6-6"/>
                            </svg>
                        @endif
                    </a>

                    <a href="{{ route('receitas.index') }}"
                       class="sidebar-link {{ request()->routeIs('receitas.*') ? 'ativo' : '' }}"
                       @if(request()->routeIs('receitas.*')) aria-current="page" @endif>
                        <svg viewBox="0 0 24 24">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <path d="M14 2v6h6"/>
                            <path d="M8 13h8M8 17h8"/>
                        </svg>
                        <span>Receitas</span>
                        @if(request()->routeIs('receitas.*'))
                            <svg class="sidebar-seta" viewBox="0 0 24 24">
                                <path d="m9 18 6-6-6-6"/>
                            </svg>
                        @endif
                    </a>

                    <a href="{{ route('produtos.index') }}"
                       class="sidebar-link {{ request()->routeIs('produtos.*') ? 'ativo' : '' }}"
                       @if(request()->routeIs('produtos.*')) aria-current="page" @endif>
                        <svg viewBox="0 0 24 24">
                            <path d="m10.5 13.5 3-3"/>
                            <rect x="3" y="7" width="18" height="10" rx="5" transform="rotate(-45 12 12)"/>
                        </svg>
                        <span>Produtos</span>
                        @if(request()->routeIs('produtos.*'))
                            <svg class="sidebar-seta" viewBox="0 0 24 24">
                                <path d="m9 18 6-6-6-6"/>
                            </svg>
                        @endif
                    </a>

                    <a href="{{ route('clientes.index') }}"
                       class="sidebar-link {{ request()->routeIs('clientes.*') ? 'ativo' : '' }}"
                       @if(request()->routeIs('clientes.*')) aria-current="page" @endif>
                        <svg viewBox="0 0 24 24">
                            <circle cx="9" cy="8" r="3"/>
                            <path d="M3 20v-2a6 6 0 0 1 12 0v2"/>
                            <path d="M17 5a3 3 0 0 1 0 6"/>
                            <path d="M18 14a5 5 0 0 1 3 5v1"/>
                        </svg>
                        <span>Clientes</span>
                        @if(request()->routeIs('clientes.*'))
                            <svg class="sidebar-seta" viewBox="0 0 24 24">
                                <path d="m9 18 6-6-6-6"/>
                            </svg>
                        @endif
                    </a>

                    <a href="{{ route('funcionarios.index') }}"
                       class="sidebar-link {{ request()->routeIs('funcionarios.*') ? 'ativo' : '' }}"
                       @if(request()->routeIs('funcionarios.*')) aria-current="page" @endif>
                        <svg viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="16" rx="2"/>
                            <circle cx="9" cy="10" r="2"/>
                            <path d="M5 17a4 4 0 0 1 8 0"/>
                            <path d="M15 9h4M15 13h4"/>
                        </svg>
                        <span>Funcionários</span>
                        @if(request()->routeIs('funcionarios.*'))
                            <svg class="sidebar-seta" viewBox="0 0 24 24">
                                <path d="m9 18 6-6-6-6"/>
                            </svg>
                        @endif
                    </a>
                </div>

                <div class="sidebar-grupo">
                    <p class="sidebar-grupo-titulo">Análises</p>

                    <a href="{{ route('relatorios.index') }}"
                       class="sidebar-link {{ request()->routeIs('relatorios.*') ? 'ativo' : '' }}"
                       @if(request()->routeIs('relatorios.*')) aria-current="page" @endif>
                        <svg viewBox="0 0 24 24">
                            <path d="M3 3v18h18"/>
                            <path d="M7 16v-5M12 16V7M17 16V4"/>
                        </svg>
                        <span>Relatórios</span>
                        @if(request()->routeIs('relatorios.*'))
                            <svg class="sidebar-seta" viewBox="0 0 24 24">
                                <path d="m9 18 6-6-6-6"/>
                            </svg>
                        @endif
                    </a>
                </div>

            </nav>

            <div class="sidebar-rodape">
                Sistema de Gestão Farmacêutica
            </div>

        </aside>

        <main class="main-content">

            <header class="topbar">

                <div>
                    <h1>@yield('page-title', 'Início')</h1>
                </div>

                <div class="user-info">
                    <span>Funcionário</span>
                </div>

            </header>

            <section class="page-content">
                @yield('content')
            </section>

        </main>

    </div>

</body>
</html>
