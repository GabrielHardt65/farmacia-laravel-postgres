<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdicionarItemVendaRequest extends FormRequest
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
                Rule::exists(
                    'pgsql.samaraferreira_bd.produtos',
                    'id_produto'
                ),
            ],

            'id_lote' => [
                'required',
                'integer',
                Rule::exists(
                    'pgsql.samaraferreira_bd.lotes',
                    'id_lote'
                ),
            ],

            'quantidade' => [
                'required',
                'integer',
                'min:1',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id_produto.required' => 'Informe o produto.',
            'id_produto.exists' => 'O produto informado não existe.',
            'id_lote.required' => 'Informe o lote.',
            'id_lote.exists' => 'O lote informado não existe.',
            'quantidade.required' => 'Informe a quantidade.',
            'quantidade.min' => 'A quantidade deve ser no mínimo 1.',
        ];
    }
}