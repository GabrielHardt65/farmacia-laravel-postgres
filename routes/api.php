<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VendaController;
use App\Http\Controllers\Api\CompraController;
use App\Http\Controllers\Api\FluxoVendaController;
use App\Http\Controllers\Api\ConsultaController;
use App\Http\Controllers\Api\ProdutoController;
use App\Http\Controllers\Api\ClienteController;

Route::name('api.')->group(function () {

    Route::get('/clientes/buscar', [ConsultaController::class, 'cliente']);
    Route::get('/produtos/buscar', [ConsultaController::class, 'produtos']);

    Route::apiResource('clientes', ClienteController::class);
    Route::apiResource('produtos', ProdutoController::class);

    Route::post('/vendas', [FluxoVendaController::class, 'store']);

    Route::post('/vendas/{venda}/itens', [
        FluxoVendaController::class,
        'adicionarItem'
    ]);

    Route::get('/vendas/{venda}/receitas-pendentes', [
        VendaController::class,
        'receitasPendentes'
    ]);

    Route::post('/vendas/{venda}/finalizar', [
        VendaController::class,
        'finalizar'
    ]);

    Route::get('/vendas/{venda}', [
        VendaController::class,
        'show'
    ]);

    Route::post('/compras', [CompraController::class, 'store']);
});
