<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_produto' => [
                'required',
                'integer',
                'exists:samaraferreira_bd.produtos,id_produto',
            ],

            'id_fornecedor' => [
                'required',
                'integer',
                'exists:samaraferreira_bd.fornecedores,id_fornecedor',
            ],

            'numero_lote' => [
                'required',
                'string',
                'max:30',
            ],

            'data_fabricacao' => [
                'required',
                'date',
            ],

            'data_validade' => [
                'required',
                'date',
                'after:data_fabricacao',
            ],

            'quantidade' => [
                'required',
                'integer',
                'min:1',
            ],

            'preco_custo' => [
                'required',
                'numeric',
                'min:0',
            ],
        ];
    }
}
