<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClienteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_cliente' => $this->id_cliente,
            'nome' => $this->nome,
            'cpf' => $this->cpf,
            'data_nascimento' => $this->data_nascimento?->format('Y-m-d'),
            'telefone' => $this->telefone,
            'email' => $this->email,
            'programa_fidelidade' => $this->programa_fidelidade,
            'pontos_fidelidade' => $this->pontos_fidelidade,
            'data_cadastro' => $this->data_cadastro?->format('Y-m-d'),
        ];
    }
}