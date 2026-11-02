<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
                Rule::exists(
                    'pgsql.samaraferreira_bd.funcionarios',
                    'id_funcionario'
                ),
            ],

            'id_cliente' => [
                'nullable',
                'integer',
                Rule::exists(
                    'pgsql.samaraferreira_bd.clientes',
                    'id_cliente'
                ),
            ],
        ];
    }
}