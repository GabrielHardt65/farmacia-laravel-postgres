<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VendaService
{
    public function finalizar(int $idVenda, array $dados): void
    {
        try {
            DB::statement(
                'CALL samaraferreira_bd.sp_finalizar_venda(?, ?, ?)',
                [
                    $idVenda,
                    $dados['id_forma_pagamento'],
                    $dados['parcelas'] ?? 1,
                ]
            );
        } catch (\Throwable $e) {
            throw ValidationException::withMessages([
                'venda' => 'Erro ao processar a venda no banco: '
                    . $e->getMessage(),
            ]);
        }
    }

    public function verificaExigeReceita(int $idVenda): bool
    {
        $resultado = DB::select(
            'SELECT samaraferreira_bd.fn_venda_exige_receita(?) AS exige',
            [$idVenda]
        );

        return (bool) ($resultado[0]->exige ?? false);
    }

    public function receitasPendentes(int $idVenda)
    {
        return DB::table('samaraferreira_bd.itens_venda as iv')
            ->join(
                'samaraferreira_bd.produtos as p',
                'p.id_produto',
                '=',
                'iv.id_produto'
            )
            ->where('iv.id_venda', $idVenda)
            ->where(function ($query) {
                $query->where('p.exige_receita', true)
                    ->orWhere('p.tipo_controle', '<>', 'livre');
            })
            ->whereNotExists(function ($query) {
                $query->selectRaw('1')
                    ->from('samaraferreira_bd.receitas as r')
                    ->whereColumn(
                        'r.id_item_venda',
                        'iv.id_item_venda'
                    );
            })
            ->select(
                'iv.id_item_venda',
                'p.nome as medicamento',
                'p.tipo_controle'
            )
            ->get();
    }
}
