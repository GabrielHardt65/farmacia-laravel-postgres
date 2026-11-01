<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CriarVendaRequest;
use App\Http\Requests\AdicionarItemVendaRequest;
use App\Services\FluxoVendaService;
use Illuminate\Http\JsonResponse;

class FluxoVendaController extends Controller
{
    public function __construct(
        private FluxoVendaService $service
    ) {}

    public function store(CriarVendaRequest $request): JsonResponse
    {
        $idVenda = $this->service->criarVenda(
            $request->validated()
        );

        return response()->json([
            'message' => 'Venda criada com sucesso.',
            'id_venda' => $idVenda,
        ], 201);
    }

    public function adicionarItem(
        AdicionarItemVendaRequest $request,
        int $venda
    ): JsonResponse {
        $idItem = $this->service->adicionarItem(
            $venda,
            $request->validated()
        );

        return response()->json([
            'message' => 'Produto adicionado à venda.',
            'id_item_venda' => $idItem,
        ], 201);
    }
}