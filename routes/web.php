<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/pagamentos/novo', function () {
    return view('pagamentos.create');
})->name('pagamentos.create');

Route::get('/comprovantes/venda', function () {
    return view('comprovantes.show');
})->name('comprovantes.show');