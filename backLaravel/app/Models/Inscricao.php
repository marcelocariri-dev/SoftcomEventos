<?php
// app/Models/Inscricao.php

namespace App\Models;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;

class Inscricao extends Model
{ use Filterable;
    protected $table = 'inscricoes';

    protected $fillable = [
        'evento_id',
        'participante_id',
        'ingresso_id',
        'codigo_inscricao',
        'status',
        'valor_pago'
    ];

    protected $casts = [
        'valor_pago' => 'decimal:2'
    ];

    public function getImagemUrlAttribute()
    {
        if ($this->imagem) {
            return asset('storage/' . $this->imagem);
        }
        
        // Imagem padrão (coloque uma em public/images/default-evento.jpg)
        return asset('eventos/default.png');
    }
    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }

    public function participante()
    {
        return $this->belongsTo(Participante::class, 'participante_id');
    }

    public function ingresso()
    {
        return $this->belongsTo(Ingresso::class, 'ingresso_id');
    }

    // ==========================================
    // SCOPES
    // ==========================================

    public function scopePendentes($query)
    {
        return $query->where('status', 'pendente');
    }

    public function scopeConfirmadas($query)
    {
        return $query->where('status', 'confirmado');
    }

    public function scopeCanceladas($query)
    {
        return $query->where('status', 'cancelado');
    }

    public function scopePorEvento($query, $eventoId)
    {
        return $query->where('evento_id', $eventoId);
    }

    public function scopePorParticipante($query, $participanteId)
    {
        return $query->where('participante_id', $participanteId);
    }

    // ==========================================
    // ACCESSORS
    // ==========================================

    public function getStatusFormatadoAttribute()
    {
        $status = [
            'pendente' => 'Pendente',
            'confirmado' => 'Confirmado',
            'cancelado' => 'Cancelado'
        ];

        return $status[$this->status] ?? $this->status;
    }

    public function getValorFormatadoAttribute()
    {
        return 'R$ ' . number_format($this->valor_pago, 2, ',', '.');
    }

    // ==========================================
    // MÉTODOS AUXILIARES
    // ==========================================

    public function confirmar()
    {
        $this->status = 'confirmado';
        $this->save();
    }

    public function cancelar()
    {
        $this->status = 'cancelado';
        $this->save();
    }

    public function estaPendente()
    {
        return $this->status === 'pendente';
    }

    public function estaConfirmada()
    {
        return $this->status === 'confirmado';
    }

    public function estaCancelada()
    {
        return $this->status === 'cancelado';
    }

    /**
     * Gerar código único de inscrição
     */
    public static function gerarCodigoInscricao()
    {
        do {
            $codigo = 'INS-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 10));
        } while (self::where('codigo_inscricao', $codigo)->exists());

        return $codigo;
    }
}