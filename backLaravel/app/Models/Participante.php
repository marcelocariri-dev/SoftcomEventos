<?php
// app/Models/Participante.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Filterable;
use Carbon\Carbon;

class Participante extends Model
{
    use Filterable;

    protected $table = 'participantes';

    protected $fillable = [
        'user_id',
        'nome',
        'cpf',
        'email',
        'telefone',
        'data_nascimento',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'data_nascimento' => 'date'
    ];

    protected $dates = [
        'data_nascimento',
        'created_at',
        'updated_at'
    ];

    
    // RELACIONAMENTOS
   
    /**
     * Usuário associado
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Inscrições do participante
     */
    public function inscricoes()
    {
        return $this->hasMany(Inscricao::class, 'participante_id');
    }

    /**
     * Eventos (via inscrições)
     */
    public function eventos()
    {
        return $this->belongsToMany(Evento::class, 'inscricoes', 'participante_id', 'evento_id')
                    ->withPivot('ingresso_id', 'codigo_inscricao', 'status', 'valor_pago')
                    ->withTimestamps();
    }

    // ==========================================
    // SCOPES
    // ==========================================

    /**
     * Scope para participantes ativos
     */
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Scope para participantes inativos
     */
    public function scopeInativos($query)
    {
        return $query->where('ativo', false);
    }

    /**
     * Scope para participantes com inscrições
     */
    public function scopeComInscricoes($query)
    {
        return $query->has('inscricoes');
    }

    /**
     * Scope para participantes sem inscrições
     */
    public function scopeSemInscricoes($query)
    {
        return $query->doesntHave('inscricoes');
    }

    /**
     * Scope para participantes com inscrições confirmadas
     */
    public function scopeComInscricoesConfirmadas($query)
    {
        return $query->whereHas('inscricoes', function($q) {
            $q->where('status', 'confirmado');
        });
    }

     
    public function getIdadeAttribute()
    {
        if (!$this->data_nascimento) {
            return null;
        }
        
        return Carbon::parse($this->data_nascimento)->age;
    }

    /**
     * Data de nascimento formatada (BR)
     */
    public function getDataNascimentoFormatadaAttribute()
    {
        if (!$this->data_nascimento) {
            return null;
        }
        
        return $this->data_nascimento->format('d/m/Y');
    }

    /**
     * Quantidade de inscrições
     */
    public function getQuantidadeInscricoesAttribute()
    {
        return $this->inscricoes()->count();
    }

    /**
     * Quantidade de inscrições confirmadas
     */
    public function getQuantidadeInscricoesConfirmadasAttribute()
    {
        return $this->inscricoes()
                    ->where('status', 'confirmado')
                    ->count();
    }

    /**
     * Quantidade de inscrições pendentes
     */
    public function getQuantidadeInscricoesPendentesAttribute()
    {
        return $this->inscricoes()
                    ->where('status', 'pendente')
                    ->count();
    }

    /**
     * Total gasto em eventos
     */
    public function getTotalGastoAttribute()
    {
        return $this->inscricoes()
                    ->where('status', 'confirmado')
                    ->sum('valor_pago');
    }


    

    /**
     * Nome completo do usuário associado
     */
    public function getNomeUsuarioAttribute()
    {
        return optional($this->user)->name;
    }

   
    public function setTelefoneAttribute($value)
    {
        $this->attributes['telefone'] = preg_replace('/[^0-9]/', '', $value);
    }


    /**
     * Verifica se tem inscrição em um evento
     */
    public function temInscricaoEmEvento($eventoId)
    {
        return $this->inscricoes()
                    ->where('evento_id', $eventoId)
                    ->exists();
    }

    /**
     * Próximos eventos
     */
    public function proximosEventos($limit = 5)
    {
        return $this->eventos()
                    ->where('data_inicio', '>=', now())
                    ->orderBy('data_inicio', 'asc')
                    ->limit($limit)
                    ->get();
    }

    /**
     * Eventos passados
     */
    public function eventosPassados($limit = 10)
    {
        return $this->eventos()
                    ->where('data_fim', '<', now())
                    ->orderBy('data_fim', 'desc')
                    ->limit($limit)
                    ->get();
    }

    /**
     * Última inscrição
     */
    public function ultimaInscricao()
    {
        return $this->inscricoes()
                    ->orderBy('created_at', 'desc')
                    ->first();
    }

   
   
    public function podeSeInscreverNoEvento($eventoId)
    {
        return $this->ativo && !$this->temInscricaoEmEvento($eventoId);
    }
}