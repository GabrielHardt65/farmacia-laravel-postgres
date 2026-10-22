<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Sistema Farmácia</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="login-page">

    <main class="login-container">

        <section class="login-card">

            <div class="login-header">
                <h1>Farmácia</h1>
                <p>Sistema Interno</p>
            </div>

            <div class="login-welcome">
                <h2>Bem-vindo!</h2>
                <p>Entre com seus dados para acessar o sistema.</p>
            </div>

            <form class="login-form" method="GET" action="{{ route('inicio') }}">     

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Digite seu e-mail"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password">Senha</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Digite sua senha"
                        required
                    >
                </div>

                <button type="submit" class="login-button">
                    Entrar
                </button>

            </form>

        </section>

    </main>

</body>
</html>