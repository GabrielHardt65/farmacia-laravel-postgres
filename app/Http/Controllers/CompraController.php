<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompraRequest;
use App\Models\Fornecedor;
use App\Models\Produto;
use App\Services\CompraService;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    public function __construct(
        private CompraService $service
    ) {}

    public function index()
    {
        $lotes = DB::table('samaraferreira_bd.lotes as l')
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
            )
            ->orderByDesc('l.id_lote')
            ->paginate(15);

        return view('compras.index', compact('lotes'));
    }

    public function create()
    {
        $produtos = Produto::orderBy('nome')->get();
        $fornecedores = Fornecedor::orderBy('nome')->get();

        return view('compras.create', compact('produtos', 'fornecedores'));
    }

    public function store(CompraRequest $request)
    {
        $this->service->registrarLote($request->validated());

        return redirect()
            ->route('compras.index')
            ->with('success', 'Entrada de estoque registrada com sucesso.');
    }
}
