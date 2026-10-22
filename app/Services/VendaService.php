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
                "CALL samaraferreira_bd.sp_finalizar_venda(?, ?, ?)",
                [
                    $idVenda,
                    $dados['id_forma_pagamento'],
                    $dados['parcelas'] ?? 1,
                ]
            );
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'venda' => 'Erro ao processar a venda no banco: ' . $e->getMessage(),
            ]);
        }
    }

    public function verificaExigeReceita(int $idVenda): bool
    {
        $resultado = DB::select(
            "SELECT samaraferreira_bd.fn_venda_exige_receita(?) as exige",
            [$idVenda]
        );

        return (bool) ($resultado[0]->exige ?? false);
    }
}   