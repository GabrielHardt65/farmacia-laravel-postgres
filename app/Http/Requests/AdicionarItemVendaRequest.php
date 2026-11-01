<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
                'exists:samaraferreira_bd.produtos,id_produto',
            ],
            'id_lote' => [
                'required',
                'integer',
                'exists:samaraferreira_bd.lotes,id_lote',
            ],
            'quantidade' => [
                'required',
                'integer',
                'min:1',
            ],
        ];
    }
}