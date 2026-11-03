<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $dados = $request->validate([
            'login' => ['required', 'string', 'max:150'],
            'password' => ['required', 'string'],
        ]);

        $chave = 'login:' . sha1(
            mb_strtolower($dados['login']) . '|' . $request->ip()
        );

        if (RateLimiter::tooManyAttempts($chave, 5)) {
            throw ValidationException::withMessages([
                'login' => 'Muitas tentativas. Aguarde alguns minutos.',
            ]);
        }

        if (!Auth::attempt([
            'login' => $dados['login'],
            'password' => $dados['password'],
        ])) {
            RateLimiter::hit($chave, 300);

            throw ValidationException::withMessages([
                'login' => 'Login ou senha inválidos.',
            ]);
        }

        RateLimiter::clear($chave);

        $request->session()->regenerate();

        return redirect()->intended(route('inicio'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
