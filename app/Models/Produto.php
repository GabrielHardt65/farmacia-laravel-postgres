<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $table = 'samaraferreira_bd.produtos';

    protected $primaryKey = 'id_produto';

    public $timestamps = false;

    protected $fillable = [
        'nome',
        'descricao',
        'id_categoria',
        'id_fabricante',
        'principio_ativo',
        'forma_farmaceutica',
        'exige_receita',
        'preco_unitario',
        'unidade_medida',
        'codigo_barras',
        'foto',
        'tipo_controle',
    ];

    protected $casts = [
        'id_categoria' => 'integer',
        'id_fabricante' => 'integer',
        'exige_receita' => 'boolean',
        'preco_unitario' => 'decimal:2',
    ];
}