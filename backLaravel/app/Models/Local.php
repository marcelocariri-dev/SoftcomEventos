<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use app\Traits\traits;

class Local extends Model
{ use Filterable;
    
    protected $table = "locais";
  
  
    protected $fillable = [
        'nome',
        'endereco',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'cep',
        'descricao',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        
    ];

    // Relacionamentos
    public function eventos()
    {
        return $this->hasMany(Evento::class);
    }
    // Accessors
    public function getEnderecoCompletoAttribute()
    {
        $endereco = "{$this->endereco}, {$this->numero}";
       
        if($this->complemento) {
            $endereco .= " - {$this->complemento}";
        }
       
        $endereco .= " - {$this->bairro}, {$this->cidade}/{$this->estado}";
       
        return $endereco;
    }

    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }
    public function scopeInativos($query)
    {
        return $query->where('ativo', false);
    }


    public function ultimoEvento()
    {
        return $this->eventos()
                    ->where('data_fim', '<', now())
                    ->orderBy('data_fim', 'desc')
                    ->first();
    }

    public function scopePorCidade($query, $cidade)
    {
        return $query->where('cidade', 'like', "%{$cidade}%");
    }

    public function scopeComEventos($query){
        return $query->has('eventos');
      }
      
      public function scopeSemEventos($query)
      {
         return $query->doesntHave('eventos');
      }
}


