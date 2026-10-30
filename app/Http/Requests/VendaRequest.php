<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
                'exists:samaraferreira_bd.formas_pagamento,id_forma_pagamento',
            ],
            'parcelas' => [
                'required_if:id_forma_pagamento,3',
                'nullable',
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
            'id_forma_pagamento.exists' => 'Forma de pagamento inválida.',
            'parcelas.required_if' => 'Informe as parcelas do cartão de crédito.',
            'parcelas.min' => 'O mínimo é uma parcela.',
            'parcelas.max' => 'O máximo é 12 parcelas.',
        ];
    }
}