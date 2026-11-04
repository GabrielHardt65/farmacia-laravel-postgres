<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        $busca = trim((string) $request->query('busca', ''));

        $estoques = DB::table('samaraferreira_bd.lotes')
            ->select('id_produto')
            ->selectRaw('SUM(quantidade_atual) AS estoque')
            ->groupBy('id_produto');

        $query = DB::table('samaraferreira_bd.produtos as p')
            ->leftJoinSub($estoques, 'e', function ($join) {
                $join->on('e.id_produto', '=', 'p.id_produto');
            })
            ->select(
                'p.id_produto',
                'p.nome',
                'p.codigo_barras',
                'p.preco_unitario',
                'p.tipo_controle'
            )
            ->selectRaw('COALESCE(e.estoque, 0) AS estoque');

        if ($busca !== '') {
            $query->where(function ($q) use ($busca) {
                $q->where('p.nome', 'ILIKE', "%{$busca}%")
                    ->orWhere('p.codigo_barras', 'ILIKE', "%{$busca}%");

                if (ctype_digit($busca)) {
                    $q->orWhere('p.id_produto', (int) $busca);
                }
            });
        }

        $produtos = $query
            ->orderBy('p.nome')
            ->get();

        return view('produtos.index', compact('produtos', 'busca'));
    }

    public function create()
    {
        $categorias = DB::table('samaraferreira_bd.categorias')
            ->orderBy('nome')
            ->get();

        $fabricantes = DB::table('samaraferreira_bd.fabricantes')
            ->orderBy('nome')
            ->get();

        $fornecedores = DB::table('samaraferreira_bd.fornecedores')
            ->orderBy('nome')
            ->get();

        return view('produtos.create', compact(
            'categorias',
            'fabricantes',
            'fornecedores'
        ));
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'id_categoria' => 'required|integer|exists:pgsql.samaraferreira_bd.categorias,id_categoria',
            'id_fabricante' => 'required|integer|exists:pgsql.samaraferreira_bd.fabricantes,id_fabricante',
            'principio_ativo' => 'nullable|string|max:255',
            'forma_farmaceutica' => 'nullable|string|max:255',
            'exige_receita' => 'required|boolean',
            'preco_unitario' => 'required|numeric|min:0',
            'unidade_medida' => 'nullable|string|max:50',
            'codigo_barras' => 'nullable|string|max:100|unique:pgsql.samaraferreira_bd.produtos,codigo_barras',
            'tipo_controle' => 'required|in:livre,antibiotico,tarja_vermelha,tarja_preta',

            // Dados do lote e estoque inicial
            'id_fornecedor' => 'required|integer|exists:pgsql.samaraferreira_bd.fornecedores,id_fornecedor',
            'numero_lote' => 'required|string|max:100',
            'data_fabricacao' => 'required|date',
            'data_validade' => 'required|date|after:data_fabricacao',
            'estoque' => 'required|integer|min:0',
            'preco_custo' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($dados) {

            // Separa os dados do produto
            $dadosProduto = collect($dados)->only([
                'nome',
                'descricao',
                'id_categoria',
                'id_fabricante',
                'principio_ativo',
                'forma_farmaceutica',
                'exige_receita',
                'preco_unitario',
                'unidade_medida',
                'codigo_barras',
                'tipo_controle',
            ])->all();

            // Cadastra o produto
            $produto = Produto::create($dadosProduto);

            // Cadastra o lote e o estoque inicial
            DB::table('samaraferreira_bd.lotes')->insert([
                'id_produto' => $produto->id_produto,
                'id_fornecedor' => $dados['id_fornecedor'],
                'numero_lote' => $dados['numero_lote'],
                'data_fabricacao' => $dados['data_fabricacao'],
                'data_validade' => $dados['data_validade'],
                'quantidade_recebida' => $dados['estoque'],
                'quantidade_atual' => $dados['estoque'],
                'preco_custo' => $dados['preco_custo'],
            ]);
        });

        return redirect()
            ->route('produtos.index')
            ->with(
                'success',
                'Produto e estoque inicial cadastrados com sucesso!'
            );
    }
}
