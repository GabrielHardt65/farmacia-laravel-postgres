<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VendaRequest;
use App\Http\Resources\VendaResource;
use App\Models\Venda;
use App\Services\VendaService;
use Illuminate\Http\JsonResponse;

class VendaController extends Controller
{
    public function __construct(private VendaService $service) {}

    public function receitasPendentes(Venda $venda): JsonResponse
    {
        $pendentes = $this->service->receitasPendentes(
            $venda->id_venda
        );

        return response()->json([
            'exige_receita' => $pendentes->isNotEmpty(),
            'quantidade_pendente' => $pendentes->count(),
            'itens' => $pendentes,
        ]);
    }

    public function finalizar(VendaRequest $request, Venda $venda)
    {
        $dados = $request->validated();

        $pendentes = $this->service->receitasPendentes(
            $venda->id_venda
        );

        if ($pendentes->isNotEmpty()) {
            return response()->json([
                'message' => 'Existem medicamentos que precisam de receita antes de finalizar a venda.',
                'exige_receita' => true,
                'itens' => $pendentes,
            ], 422);
        }

        $this->service->finalizar($venda->id_venda, $dados);

        $venda->refresh();

        $exigeReceita = $this->service->verificaExigeReceita(
            $venda->id_venda
        );

        return (new VendaResource($venda))->additional([
            'meta' => [
                'exige_receita' => $exigeReceita,
                'message' => 'Venda finalizada com sucesso.',
            ],
        ]);
    }

    public function show(Venda $venda)
    {
        return new VendaResource($venda);
    }
}
