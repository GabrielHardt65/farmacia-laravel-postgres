<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CriarVendaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_funcionario' => [
                'required',
                'integer',
                'exists:samaraferreira_bd.funcionarios,id_funcionario',
            ],
            'id_cliente' => [
                'nullable',
                'integer',
                'exists:samaraferreira_bd.clientes,id_cliente',
            ],
        ];
    }
}