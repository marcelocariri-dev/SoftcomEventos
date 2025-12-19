<?php

namespace App\Models;
use App\Traits\Filterable;

use Illuminate\Database\Eloquent\Model;


class Evento extends Model
{ use Filterable;
protected $table = "eventos";

    protected $fillable = [
        'titulo', 
        'descricao',
        'data_inicio',
        'data_fim',
        'local_id',
        'capacidade_maxima', 
        'imagem',
        'status', 
        'ativo' ,
        'user_id'    
    ];

    
    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $casts = [
        'data_inicio' => 'datetime',
        'data_fim' => 'datetime',
        'ativo' => 'boolean',
        'valor_padrao' => 'decimal:2'
    ];
    //relacionamentos

    public function local (){
        return $this->belongsTo(Local::class);

    }
    

    public function organizador()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ingressos()
    {
        return $this->hasMany(Ingresso::class);
    }

    public function inscricoes()
    {
        return $this->hasMany(Inscricao::class);
    }

    public function participantes()
    {
        return $this->belongsToMany(Participante::class, 'inscricoes')
                    ->withPivot('ingresso_id', 'codigo_inscricao', 'status', 'valor_pago')
                    ->withTimestamps();
    }
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    public function scopeProximos($query)
    {
        return $query->where('data_inicio', '>=', now());
    }
}
