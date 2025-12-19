<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Clientes extends Model
{


    protected $fillable = [
        'nome', 'data_nascimento', 'cpf', 'email', 'telefone', 'ativo'        
    ];

    
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
