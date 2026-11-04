<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RelatorioController extends Controller
{
    public function index(Request $request)
    {
        $tipo = $request->query('tipo');
        $dados = collect();

        if ($tipo === 'vendas') {
            $request->validate([
                'inicio' => ['nullable', 'date'],
                'fim' => ['nullable', 'date', 'after_or_equal:inicio'],
            ]);

            $dados = DB::table('samaraferreira_bd.vendas')
                ->when($request->inicio, fn ($q) => $q->whereDate('data_venda', '>=', $request->inicio))
                ->when($request->fim, fn ($q) => $q->whereDate('data_venda', '<=', $request->fim))
                ->orderByDesc('data_venda')
                ->get();
        }

        if ($tipo === 'estoque') {
            $request->validate([
                'situacao' => ['required', 'in:todos,baixo,sem_estoque'],
            ]);

            $dados = DB::table('samaraferreira_bd.produtos as p')
                ->leftJoin('samaraferreira_bd.lotes as l', 'p.id_produto', '=', 'l.id_produto')
                ->select(
                    'p.id_produto',
                    'p.nome',
                    DB::raw('COALESCE(SUM(l.quantidade_atual), 0) AS estoque_atual')
                )
                ->groupBy('p.id_produto', 'p.nome')
                ->when($request->situacao === 'baixo', function ($q) {
                    $q->havingRaw('COALESCE(SUM(l.quantidade_atual), 0) BETWEEN 1 AND 10');
                })
                ->when($request->situacao === 'sem_estoque', function ($q) {
                    $q->havingRaw('COALESCE(SUM(l.quantidade_atual), 0) = 0');
                })
                ->orderBy('p.nome')
                ->get();
        }

        if ($tipo === 'compras') {
            $dados = DB::table('samaraferreira_bd.lotes')
                ->select(
                    'id_lote',
                    'id_produto',
                    'id_fornecedor',
                    'numero_lote',
                    'data_fabricacao',
                    'data_validade',
                    'quantidade_recebida',
                    'quantidade_atual',
                    'preco_custo'
                )
                ->orderByDesc('id_lote')
                ->get();
        }

        if ($tipo === 'validade') {
            $request->validate([
                'dias' => ['required', 'integer', 'in:30,60,90'],
            ]);

            $dados = DB::table('samaraferreira_bd.vw_samaraferreira_lotes_proximos_vencimento')
                ->where('dias_para_vencer', '>=', 0)
                ->where('dias_para_vencer', '<=', (int) $request->dias)
                ->orderBy('dias_para_vencer')
                ->get();
        }

        return view('relatorios.index', compact('tipo', 'dados'));
    }
}
