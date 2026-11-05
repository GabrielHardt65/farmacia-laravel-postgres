<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    private function regrasCpf(?Cliente $cliente = null): array
    {
        $unico = Rule::unique('pgsql.samaraferreira_bd.clientes', 'cpf');

        if ($cliente) {
            $unico->ignore($cliente->id_cliente, 'id_cliente');
        }

        return [
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
            $unico,
        ];
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'min:3', 'max:150'],
            'cpf' => $this->regrasCpf(),
            'data_nascimento' => [
                'required',
                'date',
                'after_or_equal:1900-01-01',
                'before_or_equal:today',
            ],
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

    public function show(Cliente $cliente)
    {
        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'min:3', 'max:150'],
            'cpf' => $this->regrasCpf($cliente),
            'data_nascimento' => [
                'required',
                'date',
                'after_or_equal:1900-01-01',
                'before_or_equal:today',
            ],
            'telefone' => ['required', 'string', 'max:15'],
            'email' => ['required', 'email', 'max:150'],
            'programa_fidelidade' => ['required', 'boolean'],
            'pontos_fidelidade' => ['required', 'integer', 'min:0'],
        ]);

        $cliente->update($dados);

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente atualizado com sucesso!');
    }

    public function destroy(Cliente $cliente)
    {
        if (!Auth::check()) {
            abort(403);
        }

        try {
            $cliente->delete();

            return redirect()
                ->route('clientes.index')
                ->with('success', 'Cliente excluído com sucesso!');
        } catch (QueryException $e) {
            if ($e->getCode() === '23503') {
                return redirect()
                    ->route('clientes.index')
                    ->with('error', 'Não é possível excluir este cliente porque existem registros vinculados a ele.');
            }

            throw $e;
        }
    }
}
