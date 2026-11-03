<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ReceitaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProdutoController;


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

Route::get('/compras', function () {
    return view('compras.index');
})->name('compras.index');

Route::get('/compras/nova', function () {
    return view('compras.create');
})->name('compras.create');

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

Route::get('/funcionarios', function () {
    return view('funcionarios.index');
})->name('funcionarios.index');

Route::get('/funcionarios/novo', function () {
    return view('funcionarios.create');
})->name('funcionarios.create');

Route::get('/relatorios', function () {
    return view('relatorios.index');
})->name('relatorios.index');