<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CompraService
{
    
public function registrarLote(array $dados): void
{
    try {
        DB::statement(
            'CALL samaraferreira_bd.sp_registrar_compra_lote(?, ?, ?, ?, ?, ?, ?)',
            [
                $dados['id_produto'],
                $dados['id_fornecedor'],
                $dados['numero_lote'],
                $dados['data_fabricacao'],
                $dados['data_validade'],
                $dados['quantidade'],
                $dados['preco_custo'],
            ]
        );
    } catch (\Exception $e) {
        throw ValidationException::withMessages([
            'compra' => 'Não foi possível registrar a entrada do estoque: '
                . $e->getMessage(),
        ]);
    }
}

}
