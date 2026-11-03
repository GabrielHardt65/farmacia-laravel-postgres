<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ReceitaController extends Controller
{
    public function create()
    {
        $itens = DB::table('samaraferreira_bd.itens_venda as iv')
            ->join('samaraferreira_bd.produtos as p', 'p.id_produto', '=', 'iv.id_produto')
            ->join('samaraferreira_bd.vendas as v', 'v.id_venda', '=', 'iv.id_venda')
            ->join('samaraferreira_bd.clientes as c', 'c.id_cliente', '=', 'v.id_cliente')
            ->where('p.tipo_controle', '<>', 'livre')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('samaraferreira_bd.receitas as r')
                    ->whereColumn('r.id_item_venda', 'iv.id_item_venda');
            })
            ->select(
                'iv.id_item_venda',
                'iv.id_venda',
                'p.nome as medicamento',
                'c.nome as cliente',
                'c.cpf',
                'p.tipo_controle'
            )
            ->orderByDesc('iv.id_item_venda')
            ->get();

        return view('receitas.create', compact('itens'));
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'id_item_venda' => ['required', 'integer'],
            'medico' => ['required', 'string', 'max:150'],
            'crm' => ['required', 'string', 'max:20'],
            'numero_receita' => ['nullable', 'string', 'max:30'],
            'data_emissao' => ['required', 'date', 'before_or_equal:today'],
            'validade_receita' => ['nullable', 'date', 'after_or_equal:data_emissao'],
            'tipo_receita' => [
                'required',
                Rule::in(['branca', 'controle_especial', 'azul', 'amarela']),
            ],
        ]);

        try {
            DB::transaction(function () use ($dados) {
                $item = DB::table('samaraferreira_bd.itens_venda as iv')
                    ->join('samaraferreira_bd.produtos as p', 'p.id_produto', '=', 'iv.id_produto')
                    ->join('samaraferreira_bd.vendas as v', 'v.id_venda', '=', 'iv.id_venda')
                    ->join('samaraferreira_bd.clientes as c', 'c.id_cliente', '=', 'v.id_cliente')
                    ->where('iv.id_item_venda', $dados['id_item_venda'])
                    ->where('p.tipo_controle', '<>', 'livre')
                    ->select('iv.id_item_venda', 'iv.id_venda', 'c.cpf')
                    ->first();

                if (!$item) {
                    throw new \RuntimeException(
                        'O item selecionado não existe ou não exige receita.'
                    );
                }

                $receitaExistente = DB::table('samaraferreira_bd.receitas')
                    ->where('id_item_venda', $item->id_item_venda)
                    ->exists();

                if ($receitaExistente) {
                    throw new \RuntimeException(
                        'Já existe uma receita cadastrada para este item.'
                    );
                }

                $medico = DB::table('samaraferreira_bd.medicos')
                    ->where('crm', $dados['crm'])
                    ->first();

                if ($medico) {
                    if (mb_strtolower($medico->nome) !== mb_strtolower($dados['medico'])) {
                        throw new \RuntimeException(
                            'O CRM informado já está cadastrado para outro nome.'
                        );
                    }

                    $idMedico = $medico->id_medico;
                } else {
                    $novoMedico = DB::selectOne(
                        'INSERT INTO samaraferreira_bd.medicos (nome, crm)
                         VALUES (?, ?)
                         RETURNING id_medico',
                        [$dados['medico'], $dados['crm']]
                    );

                    $idMedico = $novoMedico->id_medico;
                }

                DB::table('samaraferreira_bd.receitas')->insert([
                    'id_item_venda' => $item->id_item_venda,
                    'id_medico' => $idMedico,
                    'numero_receita' => $dados['numero_receita'] ?? null,
                    'data_emissao' => $dados['data_emissao'],
                    'validade_receita' => $dados['validade_receita'] ?? null,
                    'id_venda' => $item->id_venda,
                    'cpf_paciente' => $item->cpf,
                    'tipo_receita' => $dados['tipo_receita'],
                ]);
            });

            return redirect()
                ->route('receitas.index')
                ->with('success', 'Receita cadastrada com sucesso.');
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->withErrors(['receita' => $e->getMessage()]);
        }
    }

    public function index(Request $request)
    {
        $consulta = DB::table('samaraferreira_bd.receitas as r')
            ->join('samaraferreira_bd.medicos as m', 'm.id_medico', '=', 'r.id_medico')
            ->join('samaraferreira_bd.itens_venda as iv', 'iv.id_item_venda', '=', 'r.id_item_venda')
            ->join('samaraferreira_bd.produtos as p', 'p.id_produto', '=', 'iv.id_produto')
            ->join('samaraferreira_bd.vendas as v', 'v.id_venda', '=', 'r.id_venda')
            ->join('samaraferreira_bd.clientes as c', 'c.id_cliente', '=', 'v.id_cliente')
            ->select(
                'r.id_receita',
                'r.numero_receita',
                'r.data_emissao',
                'r.cpf_paciente',
                'r.tipo_receita',
                'm.nome as medico',
                'p.nome as medicamento',
                'c.nome as cliente'
            );

        if ($request->filled('busca')) {
            $termo = $request->input('busca');

            $consulta->where(function ($query) use ($termo) {
                $query->where('c.nome', 'ilike', "%{$termo}%")
                    ->orWhere('r.cpf_paciente', 'ilike', "%{$termo}%")
                    ->orWhere('r.numero_receita', 'ilike', "%{$termo}%");
            });
        }

        if ($request->filled('data')) {
            $consulta->whereDate('r.data_emissao', $request->input('data'));
        }

        $receitas = $consulta
            ->orderByDesc('r.id_receita')
            ->paginate(15)
            ->withQueryString();

        return view('receitas.index', compact('receitas'));
    }
}