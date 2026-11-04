<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fornecedor extends Model
{
    protected $table = 'samaraferreira_bd.fornecedores';

    protected $primaryKey = 'id_fornecedor';

    public $timestamps = false;

    protected $fillable = [
        'nome',
        'cnpj',
        'telefone',
        'email',
        'prazo_entrega_dias',
    ];

    protected $casts = [
        'prazo_entrega_dias' => 'integer',
    ];
}