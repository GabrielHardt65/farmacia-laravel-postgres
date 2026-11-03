<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClienteResource extends JsonResource
{


    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'cpf' => $this->cpf,
            'email' => $this->email,
            'telefone' => $this->telefone,
            'endereco' => $this->endereco,
            'criado_em' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'atualizado_em' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }





    
}