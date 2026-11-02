<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class FluxoVendaService
{
    public function criarVenda(array $dados): int
    {
        return DB::table('samaraferreira_bd.vendas')
            ->insertGetId([
                'id_funcionario' => $dados['id_funcionario'],
                'id_cliente' => $dados['id_cliente'] ?? null,
            ], 'id_venda');
    }

    public function adicionarItem(
        int $idVenda,
        array $dados
    ): int {
        return DB::transaction(function () use ($idVenda, $dados) {

            $venda = DB::table('samaraferreira_bd.vendas')
                ->where('id_venda', $idVenda)
                ->first();

            if (!$venda) {
                throw ValidationException::withMessages([
                    'venda' => 'Venda não encontrada.',
                ]);
            }

            $produto = DB::table('samaraferreira_bd.produtos')
                ->where('id_produto', $dados['id_produto'])
                ->first();

            $lote = DB::table('samaraferreira_bd.lotes')
                ->where('id_lote', $dados['id_lote'])
                ->first();

            if (!$produto) {
                throw ValidationException::withMessages([
                    'id_produto' => 'Produto não encontrado.',
                ]);
            }

            if (!$lote) {
                throw ValidationException::withMessages([
                    'id_lote' => 'Lote não encontrado.',
                ]);
            }

            if ((int) $lote->id_produto !== (int) $produto->id_produto) {
                throw ValidationException::withMessages([
                    'id_lote' => 'O lote informado não pertence ao produto.',
                ]);
            }

            $preco = (float) $produto->preco_unitario;
            $quantidade = (int) $dados['quantidade'];

           return DB::table('samaraferreira_bd.itens_venda')
            ->insertGetId([
                'id_venda' => $idVenda,
                'id_produto' => $produto->id_produto,
                'id_lote' => $lote->id_lote,
                'quantidade' => $quantidade,
                'preco_unitario_venda' => $preco,
            ], 'id_item_venda');
        });
    }
}