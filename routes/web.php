<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ReceitaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\RelatorioController;

Route::get('/', function () {
    return view('inicio');
})->middleware('auth')->name('inicio');

Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.login')->name('login');

    Route::post('/login', [LoginController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.authenticate');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::get('/vendas/nova', function () {
    return view('vendas.create');
})->name('vendas.create');

Route::get('/receitas/nova', [ReceitaController::class, 'create'])
    ->name('receitas.create');

Route::post('/receitas', [ReceitaController::class, 'store'])
    ->name('receitas.store');

Route::get('/receitas', [ReceitaController::class, 'index'])
    ->name('receitas.index');

Route::get('/pagamentos/novo', function () {
    return view('pagamentos.create');
})->name('pagamentos.create');

Route::get('/comprovantes/venda', function () {
    return view('comprovantes.show');
})->name('comprovantes.show');

Route::get('/produtos', [ProdutoController::class, 'index'])
    ->name('produtos.index');

Route::get('/produtos/novo', function () {
    return view('produtos.create');
})->name('produtos.create');

// Compras e entradas de estoque
Route::get('/compras', [CompraController::class, 'index'])
    ->name('compras.index');

Route::get('/compras/nova', [CompraController::class, 'create'])
    ->name('compras.create');

Route::post('/compras', [CompraController::class, 'store'])
    ->name('compras.store');

Route::get('/categorias', function () {
    return view('categorias.index');
})->name('categorias.index');

Route::get('/categorias/nova', function () {
    return view('categorias.create');
})->name('categorias.create');

Route::get('/fornecedores', function () {
    return view('fornecedores.index');
})->name('fornecedores.index');

Route::get('/fornecedores/novo', function () {
    return view('fornecedores.create');
})->name('fornecedores.create');

Route::get('/clientes', [ClienteController::class, 'index'])
    ->name('clientes.index');

Route::get('/clientes/novo', [ClienteController::class, 'create'])
    ->name('clientes.create');

Route::post('/clientes', [ClienteController::class, 'store'])
    ->name('clientes.store');

Route::get('/funcionarios', [FuncionarioController::class, 'index'])
    ->name('funcionarios.index');

Route::get('/funcionarios/novo', [FuncionarioController::class, 'create'])
    ->name('funcionarios.create');

Route::post('/funcionarios', [FuncionarioController::class, 'store'])
    ->name('funcionarios.store');

Route::get('/relatorios', [RelatorioController::class, 'index'])
    ->name('relatorios.index');