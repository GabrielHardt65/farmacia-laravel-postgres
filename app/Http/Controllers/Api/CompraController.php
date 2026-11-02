<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompraRequest;
use App\Services\CompraService;
use Illuminate\Http\JsonResponse;

class CompraController extends Controller
{
    public function __construct(
    private CompraService $service
    ) {}

    public function store(CompraRequest $request): JsonResponse
    {
        $dados = $request->validated();

        $this->service->registrarLote($dados);

        return response()->json([
            'message' => 'Entrada de estoque registrada com sucesso.',
        ], 201);
    }
}
