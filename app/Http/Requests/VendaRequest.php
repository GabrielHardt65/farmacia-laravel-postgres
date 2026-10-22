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
            'id_forma_pagamento' => 'required|integer|exists:samaraferreira_bd.formas_pagamento,id_forma_pagamento',
            'parcelas' => 'required|integer|min:1|max:12',
        ];
    }

    public function messages(): array
    {
        return [
            'id_forma_pagamento.required' => 'Selecione uma forma de pagamento.',
            'id_forma_pagamento.exists' => 'Forma de pagamento inválida.',
            'parcelas.min' => 'O número de parcelas deve ser no mínimo 1.',
            'parcelas.max' => 'O número máximo de parcelas é 12.',
        ];
    }
}