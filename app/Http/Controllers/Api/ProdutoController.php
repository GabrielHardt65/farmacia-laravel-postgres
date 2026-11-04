<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use App\Http\Resources\ProdutoResource;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index()
    {
        $produtos = Produto::all();
        return ProdutoResource::collection($produtos);
    }

    public function store(Request $request)
    {
        $regras = [
            'nome' => 'required|string|max:255',
            'preco' => 'required|numeric|min:0',
            'estoque' => 'required|integer|min:0',
            'descricao' => 'nullable|string'
        ];

        $dadosValidados = $request->validate($regras);
        $produto = Produto::create($dadosValidados);

        return new ProdutoResource($produto);
    }

    public function show(Produto $produto)
    {
        return new ProdutoResource($produto);
    }

    public function update(Request $request, Produto $produto)
    {
        $regras = [
            'nome' => 'sometimes|required|string|max:255',
            'preco' => 'sometimes|required|numeric|min:0',
            'estoque' => 'sometimes|required|integer|min:0',
            'descricao' => 'nullable|string'
        ];

        $dadosValidados = $request->validate($regras);
        $produto->update($dadosValidados);

        return new ProdutoResource($produto);
    }

    public function destroy(Produto $produto)
    {
        $produto->delete();

        return response()->noContent();
    }
}