<?php

namespace App\Http\Controllers;

use App\Models\Funcionario;
use Illuminate\Database\QueryException;
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

    public function show($id)
    {
        $funcionario = Funcionario::findOrFail($id);

        return view('funcionarios.show', compact('funcionario'));
    }

    public function edit($id)
    {
        $funcionario = Funcionario::findOrFail($id);

        $supervisores = Funcionario::where('id_funcionario', '!=', $id)
            ->orderBy('nome')
            ->get();

        return view('funcionarios.edit', compact('funcionario', 'supervisores'));
    }

    public function update(Request $request, $id)
    {
        $funcionario = Funcionario::findOrFail($id);

        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:150'],
            'cpf' => [
                'required',
                'string',
                'max:14',
                Rule::unique('pgsql.samaraferreira_bd.funcionarios', 'cpf')
                    ->ignore($funcionario->id_funcionario, 'id_funcionario'),
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
                Rule::unique('pgsql.samaraferreira_bd.funcionarios', 'login')
                    ->ignore($funcionario->id_funcionario, 'id_funcionario'),
            ],
            'senha' => ['nullable', 'string', 'min:6', 'max:255'],
            'id_supervisor' => [
                'nullable',
                'integer',
                Rule::exists('pgsql.samaraferreira_bd.funcionarios', 'id_funcionario'),
                Rule::notIn([$funcionario->id_funcionario]),
            ],
            'e_responsavel_tecnico' => ['nullable', 'boolean'],
        ]);

        if (!empty($dados['senha'])) {
            $dados['senha'] = Hash::make($dados['senha']);
        } else {
            unset($dados['senha']);
        }

        $dados['e_responsavel_tecnico'] = $request->boolean('e_responsavel_tecnico');

        $funcionario->update($dados);

        return redirect()
            ->route('funcionarios.index')
            ->with('success', 'Funcionário atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $funcionario = Funcionario::findOrFail($id);

        if ((int) auth()->id() === (int) $funcionario->id_funcionario) {
            return redirect()
                ->route('funcionarios.index')
                ->with('error', 'Você não pode excluir sua própria conta.');
        }

        try {
            $funcionario->delete();

            return redirect()
                ->route('funcionarios.index')
                ->with('success', 'Funcionário excluído com sucesso!');
        } catch (QueryException $e) {
            if ($e->getCode() === '23503') {
                return redirect()
                    ->route('funcionarios.index')
                    ->with('error', 'Não é possível excluir este funcionário porque existem registros vinculados a ele.');
            }

            throw $e;
        }
    }
}
