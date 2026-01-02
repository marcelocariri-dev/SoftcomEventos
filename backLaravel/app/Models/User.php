<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;
    protected $table = 'users';
    protected $fillable = [
        'name',
        'email',
        'password',
      
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // ==========================================
    // RELACIONAMENTOS
    // ==========================================

    public function eventosOrganizados()
    {
        return $this->hasMany(Evento::class, 'user_id');
    }

    public function participante()
    {
        return $this->hasOne(Participante::class, 'user_id');
    }

    
    public function inscricoes()
    {
        return $this->hasManyThrough(
            Inscricao::class,
            Participante::class,
            'user_id',      // FK em participantes
            'participante_id', // FK em inscricoes
            'id',           // PK em users
            'id'            // PK em participantes
        );
    }


 



    public function temParticipante()
    {
        return $this->participante()->exists();
    }

    public function criarParticipante(array $dados)
    {
        if ($this->temParticipante()) {
            return $this->participante;
        }

        return Participante::create([
            'user_id' => $this->id,
            'nome' => $dados['nome'] ?? $this->name,
            'email' => $dados['email'] ?? $this->email,
          
            
            'ativo' => true,
        ]);
    }
}