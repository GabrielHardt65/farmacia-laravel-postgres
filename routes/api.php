<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VendaController;
use App\Http\Controllers\Api\CompraController;
use App\Http\Controllers\Api\FluxoVendaController;
use App\Http\Controllers\Api\ConsultaController;

Route::post('/vendas', [FluxoVendaController::class, 'store']);
Route::post('/vendas/{venda}/itens', [FluxoVendaController::class, 'adicionarItem']);

Route::post('/vendas/{venda}/finalizar', [VendaController::class, 'finalizar']);
Route::get('/vendas/{venda}', [VendaController::class, 'show']);

Route::post('/compras', [CompraController::class, 'store']);

Route::get('/clientes/buscar', [ConsultaController::class, 'cliente']);
Route::get('/produtos/buscar', [ConsultaController::class, 'produtos']);