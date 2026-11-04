<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProdutoResource;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

class ProdutoController extends Controller
{
    private function consultaProdutos()
    {
        return Produto::query()
            ->select('samaraferreira_bd.produtos.*')
            ->selectSub(
                DB::table('samaraferreira_bd.lotes')
                    ->selectRaw('COALESCE(SUM(quantidade_atual), 0)')
                    ->whereColumn(
                        'samaraferreira_bd.lotes.id_produto',
                        'samaraferreira_bd.produtos.id_produto'
                    ),
                'estoque'
            );
    }

    private function regras(int $id = null): array
    {
        return [
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'id_categoria' => [
                'required',
                'integer',
                Rule::exists('pgsql.samaraferreira_bd.categorias', 'id_categoria'),
            ],
            'id_fabricante' => [
                'required',
                'integer',
                Rule::exists('pgsql.samaraferreira_bd.fabricantes', 'id_fabricante'),
            ],
            'principio_ativo' => 'nullable|string|max:255',
            'forma_farmaceutica' => 'nullable|string|max:255',
            'exige_receita' => 'sometimes|boolean',
            'preco_unitario' => 'required|numeric|min:0',
            'unidade_medida' => 'nullable|string|max:50',
            'codigo_barras' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('pgsql.samaraferreira_bd.produtos', 'codigo_barras')
                    ->ignore($id, 'id_produto'),
            ],
            'foto' => 'nullable|string|max:255',
            'tipo_controle' => [
                'required',
                Rule::in([
                    'livre',
                    'antibiotico',
                    'tarja_vermelha',
                    'tarja_preta',
                ]),
            ],
        ];
    }

    public function index()
    {
        $produtos = $this->consultaProdutos()
            ->orderBy('nome')
            ->get();

        return ProdutoResource::collection($produtos);
    }

    public function store(Request $request)
    {
        $dados = $request->validate($this->regras());

        try {
            $produto = Produto::create($dados);

            return (new ProdutoResource(
                $this->consultaProdutos()
                    ->where('id_produto', $produto->id_produto)
                    ->firstOrFail()
            ))->response()->setStatusCode(201);

        } catch (QueryException $e) {
            return response()->json([
                'mensagem' => 'Não foi possível cadastrar o produto.',
            ], 409);
        }
    }

    public function show(string $id)
    {
        $produto = $this->consultaProdutos()
            ->where('id_produto', $id)
            ->firstOrFail();

        return new ProdutoResource($produto);
    }

    public function update(Request $request, string $id)
    {
        $produto = Produto::findOrFail($id);

        $dados = $request->validate([
            'nome' => 'sometimes|required|string|max:255',
            'descricao' => 'sometimes|nullable|string',
            'id_categoria' => [
                'sometimes',
                'required',
                'integer',
                Rule::exists('pgsql.samaraferreira_bd.categorias', 'id_categoria'),
            ],
            'id_fabricante' => [
                'sometimes',
                'required',
                'integer',
                Rule::exists('pgsql.samaraferreira_bd.fabricantes', 'id_fabricante'),
            ],
            'principio_ativo' => 'sometimes|nullable|string|max:255',
            'forma_farmaceutica' => 'sometimes|nullable|string|max:255',
            'exige_receita' => 'sometimes|boolean',
            'preco_unitario' => 'sometimes|required|numeric|min:0',
            'unidade_medida' => 'sometimes|nullable|string|max:50',
            'codigo_barras' => [
                'sometimes',
                'nullable',
                'string',
                'max:100',
                Rule::unique('pgsql.samaraferreira_bd.produtos', 'codigo_barras')
                    ->ignore($produto->id_produto, 'id_produto'),
            ],
            'foto' => 'sometimes|nullable|string|max:255',
            'tipo_controle' => [
                'sometimes',
                'required',
                Rule::in([
                    'livre',
                    'antibiotico',
                    'tarja_vermelha',
                    'tarja_preta',
                ]),
            ],
        ]);

        $produto->update($dados);

        $atualizado = $this->consultaProdutos()
            ->where('id_produto', $id)
            ->firstOrFail();

        return new ProdutoResource($atualizado);
    }

    public function destroy(string $id)
    {
        $produto = Produto::findOrFail($id);

        try {
            $produto->delete();

            return response()->noContent();

        } catch (QueryException $e) {
            return response()->json([
                'mensagem' => 'Não é possível excluir este produto porque existem registros relacionados a ele.',
            ], 409);
        }
    }
}