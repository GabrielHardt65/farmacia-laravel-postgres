<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    protected $table = 'samaraferreira_bd.vendas';

    protected $primaryKey = 'id_venda';

    public $timestamps = false;

    protected $fillable = [
        'id_cliente',
        'id_funcionario',
        'id_forma_pagamento',
        'valor_total',
        'desconto_aplicado',
        'parcelas',
        'data_venda',
    ];

    protected function casts(): array
    {
        return [
            'data_venda' => 'datetime',
            'valor_total' => 'decimal:2',
            'desconto_aplicado' => 'decimal:2',
        ];
    }
}