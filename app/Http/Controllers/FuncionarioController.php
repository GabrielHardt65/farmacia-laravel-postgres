<?php

namespace App\Http\Controllers;

use App\Models\Funcionario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class FuncionarioController extends Controller
{
    public function index(Request $request)
    {
        $busca = $request->query('busca');

        $funcionarios = Funcionario::query()
            ->when($busca, function ($query, $busca) {
                $query->where(function ($q) use ($busca) {
                    $q->where('nome', 'ILIKE', "%{$busca}%")
                      ->orWhere('cpf', 'ILIKE', "%{$busca}%")
                      ->orWhere('cargo', 'ILIKE', "%{$busca}%");
                });
            })
            ->orderBy('nome')
            ->paginate(15)
            ->withQueryString();

        return view('funcionarios.index', compact('funcionarios', 'busca'));
    }

    public function create()
    {
        $supervisores = Funcionario::orderBy('nome')->get();

        return view('funcionarios.create', compact('supervisores'));
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:150'],
            'cpf' => [
                'required',
                'string',
                'max:14',
                Rule::unique('pgsql.samaraferreira_bd.funcionarios', 'cpf'),
            ],
            'cargo' => ['required', 'string', 'max:60'],
            'data_admissao' => ['required', 'date'],
            'salario' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:120'],
            'login' => [
                'required',
                'string',
                'max:50',
                Rule::unique('pgsql.samaraferreira_bd.funcionarios', 'login'),
            ],
            'senha' => ['required', 'string', 'min:6', 'max:255'],
            'id_supervisor' => [
                'nullable',
                'integer',
                Rule::exists('pgsql.samaraferreira_bd.funcionarios', 'id_funcionario'),
            ],
            'e_responsavel_tecnico' => ['nullable', 'boolean'],
        ]);

        $dados['senha'] = Hash::make($dados['senha']);
        $dados['e_responsavel_tecnico'] = $request->boolean('e_responsavel_tecnico');

        Funcionario::create($dados);

        return redirect()
            ->route('funcionarios.index')
            ->with('success', 'Funcionário cadastrado com sucesso.');
    }
}
