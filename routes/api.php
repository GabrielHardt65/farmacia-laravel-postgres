<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VendaController;

Route::post('/vendas/{venda}/finalizar', [VendaController::class, 'finalizar']);
Route::get('/vendas/{venda}', [VendaController::class, 'show']);