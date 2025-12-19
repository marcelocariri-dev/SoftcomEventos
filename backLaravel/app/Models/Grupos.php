<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Grupos extends Model
{
protected $table = "grupo";

    protected $fillable = [
        'nome', 'ativo'     
    ];

    
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
