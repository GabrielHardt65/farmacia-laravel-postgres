<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProdutoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_produto' => $this->id_produto,
            'nome' => $this->nome,
            'descricao' => $this->descricao,
            'id_categoria' => $this->id_categoria,
            'id_fabricante' => $this->id_fabricante,
            'principio_ativo' => $this->principio_ativo,
            'forma_farmaceutica' => $this->forma_farmaceutica,
            'exige_receita' => $this->exige_receita,
            'preco_unitario' => $this->preco_unitario,
            'unidade_medida' => $this->unidade_medida,
            'codigo_barras' => $this->codigo_barras,
            'foto' => $this->foto,
            'tipo_controle' => $this->tipo_controle,
            'estoque' => (int) ($this->estoque ?? 0),
        ];
    }
}