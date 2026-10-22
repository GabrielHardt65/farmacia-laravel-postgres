<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Sistema Farmácia')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <div class="home-container">

        {{-- Cabeçalho da tela inicial --}}
        <header class="home-header">

            <div class="home-brand">
                <h2>Farmácia</h2>
                <span>Sistema Interno</span>
            </div>

            <div class="home-user">
                <span>Funcionário</span>
            </div>

        </header>

        {{-- Conteúdo da página inicial --}}
        <main class="home-content">
            @yield('content')
        </main>

    </div>

</body>
</html>