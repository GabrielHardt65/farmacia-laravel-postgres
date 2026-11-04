<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VendaController;
use App\Http\Controllers\Api\CompraController;
use App\Http\Controllers\Api\FluxoVendaController;
use App\Http\Controllers\Api\ConsultaController;
use App\Http\Controllers\Api\ProdutoController;
use App\Http\Controllers\Api\ClienteController;

// Consultas
Route::get('/clientes/buscar', [ConsultaController::class, 'cliente']);
Route::get('/produtos/buscar', [ConsultaController::class, 'produtos']);

// CRUD REST
Route::apiResource('clientes', ClienteController::class);
Route::apiResource('produtos', ProdutoController::class);

// Fluxo de vendas
Route::post('/vendas', [FluxoVendaController::class, 'store']);
Route::post('/vendas/{venda}/itens', [FluxoVendaController::class, 'adicionarItem']);
Route::post('/vendas/{venda}/finalizar', [VendaController::class, 'finalizar']);
Route::get('/vendas/{venda}', [VendaController::class, 'show']);

// Compras
Route::post('/compras', [CompraController::class, 'store']);