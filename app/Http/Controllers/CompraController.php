<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompraRequest;
use App\Models\Fornecedor;
use App\Models\Produto;
use App\Services\CompraService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    public function __construct(
        private CompraService $service
    ) {}

    public function index(Request $request)
    {
        $hoje = now()->toDateString();
        $limiteValidade = now()->addDays(30)->toDateString();

        // Total de unidades disponíveis no estoque.
        $totalUnidades = DB::table('samaraferreira_bd.lotes')
            ->sum('quantidade_atual');

        // Quantidade de medicamentos com estoque baixo.
        // Considera a soma dos lotes de cada produto.
        $estoquePorProduto = DB::table(
            'samaraferreira_bd.produtos as p'
        )
            ->leftJoin(
                'samaraferreira_bd.lotes as l',
                'l.id_produto',
                '=',
                'p.id_produto'
            )
            ->select(
                'p.id_produto',
                'p.nome'
            )
            ->selectRaw(
                'COALESCE(SUM(l.quantidade_atual), 0) as quantidade_total'
            )
            ->groupBy(
                'p.id_produto',
                'p.nome'
            );

        $produtosEstoqueBaixo = DB::query()
            ->fromSub($estoquePorProduto, 'estoque')
            ->where('quantidade_total', '<=', 5)
            ->orderBy('quantidade_total')
            ->get();

        $totalEstoqueBaixo = $produtosEstoqueBaixo->count();

        // Lotes com vencimento nos próximos 30 dias.
        $lotesProximosVencimento = DB::table(
            'samaraferreira_bd.lotes'
        )
            ->where('quantidade_atual', '>', 0)
            ->whereBetween(
                'data_validade',
                [$hoje, $limiteValidade]
            )
            ->count();

        // Lotes vencidos que ainda possuem estoque.
        $lotesVencidos = DB::table(
            'samaraferreira_bd.lotes'
        )
            ->where('quantidade_atual', '>', 0)
            ->where('data_validade', '<', $hoje)
            ->count();

        // Consulta dos lotes cadastrados.
        $consulta = DB::table('samaraferreira_bd.lotes as l')
            ->join(
                'samaraferreira_bd.produtos as p',
                'p.id_produto',
                '=',
                'l.id_produto'
            )
            ->join(
                'samaraferreira_bd.fornecedores as f',
                'f.id_fornecedor',
                '=',
                'l.id_fornecedor'
            )
            ->select(
                'l.*',
                'p.nome as produto_nome',
                'f.nome as fornecedor_nome'
            );

        // Pesquisa por medicamento ou número do lote.
        if ($request->filled('busca')) {
            $termo = trim($request->input('busca'));

            $consulta->where(function ($query) use ($termo) {
                $query->where(
                    'p.nome',
                    'ilike',
                    "%{$termo}%"
                )
                ->orWhere(
                    'l.numero_lote',
                    'ilike',
                    "%{$termo}%"
                );
            });
        }

        // Filtros do painel.
        if ($request->filled('filtro')) {
            switch ($request->input('filtro')) {
                case 'baixo':
                    $consulta->whereIn(
                        'p.id_produto',
                        $produtosEstoqueBaixo->pluck('id_produto')
                    );
                    break;

                case 'vencendo':
                    $consulta
                        ->where('l.quantidade_atual', '>', 0)
                        ->whereBetween(
                            'l.data_validade',
                            [$hoje, $limiteValidade]
                        );
                    break;

                case 'vencidos':
                    $consulta
                        ->where('l.quantidade_atual', '>', 0)
                        ->where('l.data_validade', '<', $hoje);
                    break;
            }
        }

        $lotes = $consulta
            ->orderByDesc('l.id_lote')
            ->paginate(15)
            ->withQueryString();

        return view('compras.index', compact(
            'lotes',
            'totalUnidades',
            'totalEstoqueBaixo',
            'lotesProximosVencimento',
            'lotesVencidos',
            'produtosEstoqueBaixo'
        ));
    }

    public function create()
    {
        $produtos = Produto::orderBy('nome')->get();

        $fornecedores = Fornecedor::orderBy('nome')->get();

        return view('compras.create', compact(
            'produtos',
            'fornecedores'
        ));
    }

    public function store(CompraRequest $request)
    {
        $this->service->registrarLote(
            $request->validated()
        );

        return redirect()
            ->route('compras.index')
            ->with(
                'success',
                'Entrada de estoque registrada com sucesso.'
            );
    }
}
