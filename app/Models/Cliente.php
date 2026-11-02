<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'samaraferreira_bd.clientes';

    protected $primaryKey = 'id_cliente';

    public $timestamps = false;

    protected $fillable = [
        'nome',
        'cpf',
        'data_nascimento',
        'telefone',
        'email',
        'programa_fidelidade',
        'pontos_fidelidade',
        'data_cadastro',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'data_cadastro' => 'date',
        'programa_fidelidade' => 'boolean',
        'pontos_fidelidade' => 'integer',
    ];
}