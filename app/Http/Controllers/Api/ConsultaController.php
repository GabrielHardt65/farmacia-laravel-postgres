<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class ConsultaController extends Controller
{
    public function cliente(Request $request)
    {
        $validator = Validator::make($request->query(), [
            'cpf' => ['required', 'string'],
        ], [
            'cpf.required' => 'Informe o CPF do cliente.',
            'cpf.string' => 'O CPF informado é inválido.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Dados inválidos.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $cpf = preg_replace('/\D/', '', $request->query('cpf', ''));

        if (strlen($cpf) !== 11) {
            return response()->json([
                'message' => 'Informe um CPF válido.',
                'errors' => [
                    'cpf' => ['O CPF deve conter 11 números.'],
                ],
            ], 422);
        }

        try {
            $cliente = DB::table('samaraferreira_bd.clientes')
                ->select('id_cliente', 'nome', 'cpf')
                ->whereRaw(
                    "regexp_replace(cpf, '[^0-9]', '', 'g') = ?",
                    [$cpf]
                )
                ->first();

            if (!$cliente) {
                return response()->json([
                    'message' => 'Cliente não encontrado.',
                ], 404);
            }

            return response()->json([
                'data' => $cliente,
            ], 200);
        } catch (QueryException $e) {
            Log::error('Erro ao buscar cliente.', [
                'exception' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Não foi possível consultar o cliente agora.',
            ], 500);
        }
    }

    public function produtos(Request $request)
    {
        $validator = Validator::make($request->query(), [
            'busca' => ['required', 'string', 'max:100'],
        ], [
            'busca.required' => 'Informe o nome ou código do produto.',
            'busca.string' => 'O termo de busca é inválido.',
            'busca.max' => 'A busca deve ter no máximo 100 caracteres.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Dados inválidos.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $busca = trim($request->query('busca', ''));

        if ($busca === '') {
            return response()->json([
                'message' => 'Informe o nome ou código do produto.',
                'errors' => [
                    'busca' => ['O campo de busca não pode estar vazio.'],
                ],
            ], 422);
        }

        try {
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
                ->limit(50)
                ->get();

            return response()->json([
                'data' => $produtos,
            ], 200);
        } catch (QueryException $e) {
            Log::error('Erro ao buscar produtos.', [
                'exception' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Não foi possível consultar os produtos agora.',
            ], 500);
        }
    }
}