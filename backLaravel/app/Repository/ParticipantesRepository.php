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

    // ==========================================
    // MÉTODOS ESPECÍFICOS
    // ==========================================

    public function buscarPorCpf(string $cpf)
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        return $this->model
            ->where('cpf', $cpf)
            ->with(['user', 'inscricoes'])
            ->first();
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

    public function estatisticas()
    {
        return [
            'total' => $this->model->count(),
            'ativos' => $this->model->where('ativo', true)->count(),
            'inativos' => $this->model->where('ativo', false)->count(),
            'com_inscricoes' => $this->model->has('inscricoes')->count(),
            'sem_inscricoes' => $this->model->doesntHave('inscricoes')->count(),
            'com_usuario' => $this->model->whereNotNull('user_id')->count(),
            'sem_usuario' => $this->model->whereNull('user_id')->count(),
        ];
    }
}