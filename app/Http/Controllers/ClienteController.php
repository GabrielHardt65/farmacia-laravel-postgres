<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::orderBy('nome')->get();

        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'min:3', 'max:150'],
            'cpf' => [
                'required',
                'string',
                'regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$/',
                function ($attribute, $value, $fail) {
                    $cpf = preg_replace('/\D/', '', $value);

                    if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
                        $fail('O CPF informado é inválido.');
                        return;
                    }

                    for ($tamanho = 9; $tamanho < 11; $tamanho++) {
                        $soma = 0;

                        for ($i = 0; $i < $tamanho; $i++) {
                            $soma += (int) $cpf[$i] * ($tamanho + 1 - $i);
                        }

                        $resto = ($soma * 10) % 11;
                        $digito = $resto === 10 ? 0 : $resto;

                        if ($digito !== (int) $cpf[$tamanho]) {
                            $fail('O CPF informado é inválido.');
                            return;
                        }
                    }
                },
                Rule::unique('samaraferreira_bd.clientes', 'cpf'),
            ],
            'data_nascimento' => ['required', 'date', 'after_or_equal:1900-01-01', 'before_or_equal:today'],
            'telefone' => ['required', 'string', 'max:15'],
            'email' => ['required', 'email', 'max:150'],
        ]);

        $dados['data_cadastro'] = now()->toDateString();
        $dados['programa_fidelidade'] = false;
        $dados['pontos_fidelidade'] = 0;

        Cliente::create($dados);

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente cadastrado com sucesso!');
    }
}