<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsultaController extends Controller
{
    public function cliente(Request $request)
    {
        $cpf = preg_replace(
            '/\D/',
            '',
            (string) $request->query('cpf', '')
        );

        if (strlen($cpf) !== 11) {
            return response()->json([
                'message' => 'Informe um CPF válido.'
            ], 422);
        }

        $cliente = DB::table('samaraferreira_bd.clientes')
            ->select('id_cliente', 'nome', 'cpf')
            ->whereRaw(
                "regexp_replace(cpf, '[^0-9]', '', 'g') = ?",
                [$cpf]
            )
            ->first();

        if (!$cliente) {
            return response()->json([
                'message' => 'Cliente não encontrado.'
            ], 404);
        }

        return response()->json([
            'data' => $cliente
        ]);
    }

    public function produtos(Request $request)
    {
        $busca = trim((string) $request->query('busca', ''));

        if ($busca === '') {
            return response()->json([
                'message' => 'Informe o nome ou código do produto.'
            ], 422);
        }

        $produtos = DB::table('samaraferreira_bd.produtos as p')
            ->join(
                'samaraferreira_bd.lotes as l',
                'l.id_produto',
                '=',
                'p.id_produto'
            )
            ->where('l.quantidade_atual', '>', 0)
            ->whereDate('l.data_validade', '>=', now()->toDateString())
            ->where(function ($query) use ($busca) {
                $query->where('p.nome', 'ILIKE', "%{$busca}%")
                    ->orWhere('p.codigo_barras', 'ILIKE', "%{$busca}%");
            })
            ->select(
                'p.id_produto',
                'p.nome',
                'p.codigo_barras',
                'p.preco_unitario',
                'p.tipo_controle',
                'l.id_lote',
                'l.numero_lote',
                'l.quantidade_atual',
                'l.data_validade'
            )
            ->orderBy('p.nome')
            ->orderBy('l.data_validade')
            ->get();

        return response()->json([
            'data' => $produtos
        ]);
    }
}
