<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VendaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_venda' => $this->id_venda,
            'id_cliente' => $this->id_cliente,
            'id_funcionario' => $this->id_funcionario,
            'valor_total' => (float) $this->valor_total,
            'desconto_aplicado' => (float) $this->desconto_aplicado,
            'id_forma_pagamento' => $this->id_forma_pagamento,
            'parcelas' => $this->parcelas,
            'data_venda' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}