<?php

namespace App\Http\Controllers;

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
}
