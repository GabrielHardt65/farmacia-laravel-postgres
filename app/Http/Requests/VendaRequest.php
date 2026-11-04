<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VendaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_forma_pagamento' => [
                'required',
                'integer',
                Rule::exists(
                    'pgsql.samaraferreira_bd.formas_pagamento',
                    'id_forma_pagamento'
                ),
            ],

            'parcelas' => [
                'required',
                'integer',
                'min:1',
                'max:12',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id_forma_pagamento.required' => 'Selecione uma forma de pagamento.',
            'id_forma_pagamento.exists' => 'A forma de pagamento selecionada não existe.',
            'parcelas.required' => 'Informe a quantidade de parcelas.',
            'parcelas.min' => 'A quantidade mínima é uma parcela.',
            'parcelas.max' => 'A quantidade máxima é 12 parcelas.',
        ];
    }
}