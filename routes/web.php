<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;

Route::get('/', function () {
    return view('inicio');
})->name('inicio');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/vendas/nova', function () {
    return view('vendas.create');
})->name('vendas.create');

Route::get('/receitas/nova', function () {
    return view('receitas.create');
})->name('receitas.create');

Route::get('/receitas', function () {
    return view('receitas.index');
})->name('receitas.index');

Route::get('/pagamentos/novo', function () {
    return view('pagamentos.create');
})->name('pagamentos.create');

Route::get('/comprovantes/venda', function () {
    return view('comprovantes.show');
})->name('comprovantes.show');

Route::get('/produtos', function () {
    return view('produtos.index');
})->name('produtos.index');

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