<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\Filterable;

class Ingresso extends Model
{
    use Filterable;

    protected $table = 'ingressos';

    protected $fillable = [
        'evento_id',
        'tipo_ingresso',
        'valor',
        'quantidade_disponivel',
        'descricao',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'valor' => 'decimal:2',
        'quantidade_disponivel' => 'integer'
    ];

   //Relacionamentos
   
    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }
    //inscriões com esse ingresso
    public function inscricoes()
    {
        return $this->hasMany(Inscricao::class, 'ingresso_id');
    }

    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Scope para ingressos inativos
     */
    public function scopeInativos($query)
    {
        return $query->where('ativo', false);
    }

    //Scope por evento

    public function scopePorEvento($query, $eventoId)
    {
        return $query->where('evento_id', $eventoId);
    }

    public function getValorTotalArrecadadoAttribute()
    {
        return $this->inscricoes()
                    ->where('status', 'confirmado')
                    ->sum('valor_pago');
    }
    public function scopeDisponiveis($query){
        return $query->where('quantidade_disponivel', '>', 0);
    }

    public function scopeEsgotados($query){
        return $query->where('quantidade_disponivel', '<=', 0);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_ingresso', $tipo);
    }
//define quantidade_vendida na convenção
    public function getQuantidadeVendidaAttribute()
    {
        return $this->inscricoes()
                    ->where('status', 'confirmado')
                    ->count();
    }

    public function getQuantidadeRestanteAttribute()
    {
        return max(0, $this->quantidade_disponivel - $this->quantidade_vendida);
    }

  
}
