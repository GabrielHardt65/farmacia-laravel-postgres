<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | Sistema Farmácia</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="login-page">

    <main class="login-container">
        <section class="login-card">

            <div class="login-brand">
                <div class="login-logo">✚</div>

                <div>
                    <h1>FARMÁCIA</h1>
                    <span>SISTEMA DE GESTÃO</span>
                </div>
            </div>

            <div class="login-header">
                <div class="login-decoration">✚</div>

                <span class="login-badge">ACESSO RESTRITO</span>

                <h2>Bem-vindo de volta!</h2>
                <p>Acesse sua conta para continuar.</p>
            </div>

            <div class="login-content">

                @if (session('status'))
                    <div class="login-success" role="status">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="login-error" role="alert">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form
                    class="login-form"
                    method="POST"
                    action="{{ route('login.authenticate') }}"
                >
                    @csrf

                    <div class="login-field">
                        <label for="login">Usuário</label>

                        <div class="login-input-wrapper">
                            <span class="login-input-icon" aria-hidden="true">
                                ♙
                            </span>

                            <input
                                type="text"
                                id="login"
                                name="login"
                                value="{{ old('login') }}"
                                placeholder="Digite seu usuário"
                                autocomplete="username"
                                maxlength="150"
                                required
                                autofocus
                            >
                        </div>
                    </div>

                    <div class="login-field">
                        <label for="password">Senha</label>

                        <div class="login-input-wrapper">
                            <span class="login-input-icon" aria-hidden="true">
                                🔒
                            </span>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Digite sua senha"
                                autocomplete="current-password"
                                required
                            >

                            <button
                                type="button"
                                class="login-toggle-password"
                                id="toggle-password"
                                aria-label="Mostrar senha"
                                aria-pressed="false"
                            >
                                Mostrar
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="login-button">
                        Entrar no sistema
                        <span aria-hidden="true">→</span>
                    </button>

                </form>

                <div class="login-footer">
                    <span class="login-footer-icon" aria-hidden="true">◆</span>
                    Ambiente interno e seguro
                </div>

            </div>

        </section>

        <p class="login-copyright">
            © {{ date('Y') }} Farmácia — Sistema de Gestão
        </p>
    </main>

    <script>
        const togglePassword = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function () {
            const mostrar = passwordInput.type === 'password';

            passwordInput.type = mostrar ? 'text' : 'password';
            this.textContent = mostrar ? 'Ocultar' : 'Mostrar';
            this.setAttribute('aria-pressed', String(mostrar));
            this.setAttribute(
                'aria-label',
                mostrar ? 'Ocultar senha' : 'Mostrar senha'
            );
        });
    </script>

</body>
</html>