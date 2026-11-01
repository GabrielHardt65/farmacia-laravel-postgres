<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VendaController;
use App\Http\Controllers\Api\CompraController;

Route::post('/vendas/{venda}/finalizar', [VendaController::class, 'finalizar']);
Route::get('/vendas/{venda}', [VendaController::class, 'show']);
Route::post('/compras', [CompraController::class, 'store']);