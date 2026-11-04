<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    protected $table = 'samaraferreira_bd.lotes';

    protected $primaryKey = 'id_lote';

    public $timestamps = false;

    protected $fillable = [
        'id_produto',
        'id_fornecedor',
        'numero_lote',
        'data_fabricacao',
        'data_validade',
        'quantidade_recebida',
        'quantidade_atual',
        'preco_custo',
    ];

    protected $casts = [
        'data_fabricacao' => 'date',
        'data_validade' => 'date',
        'quantidade_recebida' => 'integer',
        'quantidade_atual' => 'integer',
        'preco_custo' => 'decimal:2',
    ];
}
