<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Funcionario extends Authenticatable
{
    protected $table = 'samaraferreira_bd.funcionarios';

    protected $primaryKey = 'id_funcionario';

    public $timestamps = false;

    protected $fillable = [
        'nome',
        'cpf',
        'cargo',
        'e_responsavel_tecnico',
        'data_admissao',
        'salario',
        'telefone',
        'email',
        'id_supervisor',
        'login',
        'senha',
    ];

    protected $hidden = [
        'senha',
    ];

    protected $casts = [
        'e_responsavel_tecnico' => 'boolean',
        'data_admissao' => 'date',
        'salario' => 'decimal:2',
    ];

    public function getAuthPasswordName()
    {
        return 'senha';
    }

    public function getAuthPassword()
    {
        return $this->senha;
    }
}
