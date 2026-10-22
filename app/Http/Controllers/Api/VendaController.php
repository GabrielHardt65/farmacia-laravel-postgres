<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VendaRequest;
use App\Http\Resources\VendaResource;
use App\Models\Venda;
use App\Services\VendaService;

class VendaController extends Controller
{
    public function __construct(private VendaService $service) {}

    public function finalizar(VendaRequest $request, Venda $venda)
    {
        $dados = $request->validated();

        $this->service->finalizar($venda->id_venda, $dados);

        $venda->refresh();

        $exigeReceita = $this->service->verificaExigeReceita($venda->id_venda);

        return (new VendaResource($venda))
            ->additional([
                'meta' => [
                    'exige_receita' => $exigeReceita,
                    'message' => $exigeReceita
                        ? 'Venda finalizada. Medicamento controlado exige registro de receita.'
                        : 'Venda finalizada com sucesso.',
                ]
            ]);
    }


    public function show(Venda $venda)
    {
        return new VendaResource($venda);
    }
}