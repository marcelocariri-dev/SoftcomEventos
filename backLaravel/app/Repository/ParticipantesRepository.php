<?php
// app/Repository/ParticipantesRepository.php

namespace App\Repository;

use App\Models\Participante;
use App\Filters\ParticipanteFilter;

class ParticipantesRepository
{
    private $model;
    
    public function __construct()
    {  
        $this->model = new Participante();
    }

    // ==========================================
    // CRUD BÁSICO
    // ==========================================

    public function listagem()
    {
        return $this->model
            ->with(['user', 'inscricoes.evento'])
            ->orderBy('nome', 'asc')
            ->get();
    }

    public function listagemAtivos()
    {
        return $this->model
            ->ativos()
            ->with(['user'])
            ->orderBy('nome', 'asc')
            ->get();
    }

    public function capturar($id)
    {
        return $this->model
            ->with(['user', 'inscricoes.evento', 'inscricoes.ingresso'])
            ->find($id);
    }

    public function salvar(array $dados)
    { 
        $id = $dados['id'] ?? null;

        $participante = $this->model->findOrNew($id);
        $participante->fill($dados);
        $participante->save();
        
        return $this->capturar($participante->id);
    }

    public function excluir($id)
    {
        $participante = $this->model->find($id);
        
        if (!$participante) {
            throw new \Exception("Participante não encontrado");
        }

        if ($participante->inscricoes()->count() > 0) {
            throw new \Exception("Não é possível excluir participante com inscrições cadastradas");
        }

        $participante->delete();
        return true;
    }

    // ==========================================
    // MÉTODOS COM FILTROS
    // ==========================================

    public function listagemComFiltros(ParticipanteFilter $filters)
    {
        return $this->model
            ->with(['user', 'inscricoes'])
            ->filter($filters)
            ->orderBy('nome', 'asc')
            ->get();
    }

    public function listagemComFiltrosPaginado(ParticipanteFilter $filters, int $perPage = 15)
    {
        return $this->model
            ->with(['user'])
            ->filter($filters)
            ->orderBy('nome', 'asc')
            ->paginate($perPage);
    }

 
public function ParticipantePorUser($user_ID){
    return $this->model
    ->where('user_id', $user_ID)
    ->pluck('id') //pega todos e coloca no array
    ->toArray();
    //->value('id'); where so desse valor
    // ->first(['id']);  // ------   retorna 1 registro ou null->model->id) // retorna o objeto;
}

    public function participantesComInscricoes()
    {
        return $this->model
            ->comInscricoes()
            ->with(['inscricoes.evento'])
            ->orderBy('nome', 'asc')
            ->get();
    }

    public function participantesSemInscricoes()
    {
        return $this->model
            ->semInscricoes()
            ->orderBy('nome', 'asc')
            ->get();
    }

    public function alterarStatus($id, bool $status)
    {
        $participante = $this->model->find($id);
        
        if (!$participante) {
            throw new \Exception("Participante não encontrado");
        }
        
        $participante->ativo = $status;
        $participante->save();
        
        return $this->capturar($participante->id);
    }

    
}