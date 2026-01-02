<?php
// app/Repository/EventoRepository.php

namespace App\Repository;

use App\Models\Evento;
use App\Filters\EventoFilter;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class EventoRepository
{
    private $model;
    
    public function __construct()
    {  
        $this->model = new Evento();
    }

    // ==========================================
    // CRUD BÁSICO
    // ==========================================

    /**
     * Listagem completa com relacionamentos
     */
    public function listagem()
    {
        return $this->model
            ->with(['local', 'organizador', 'ingressos'])
            ->orderBy('data_inicio', 'desc')
            ->get();
    }


    public function listagemAtivos()
    {
        return $this->model
            ->ativos()
            ->with(['local', 'organizador'])
            ->orderBy('data_inicio', 'desc')
            ->get();
    }

    
    public function capturar($id)
    {
        return $this->model
            ->with(['local', 'organizador', 'ingressos', 'inscricoes'])
            ->find($id);
    }

    /**
     * Salvar evento (criar ou atualizar)
     */
    public function salvar(array $dados)
    { 
        $id = $dados['id'] ?? null;

        $evento = $this->model->findOrNew($id);
        $evento->fill($dados);
        $evento->save();
        
        // Recarrega com relacionamentos
        return $this->capturar($evento->id);
    }

    /**
     * Excluir evento
     */
    public function excluir($id)
    {
        $evento = $this->model->find($id);
        
        if (!$evento) {
            throw new \Exception("Evento não encontrado");
        }

        // Verifica se tem inscrições
       if ($evento->inscricoes()->count() > 0) {
            throw new \Exception("Não é possível excluir evento com inscrições cadastradas");
        }

        $evento->delete();
        return true;
    }

   
    /**
     * Listagem com filtros opcionais
     */
    public function listagemComFiltros(EventoFilter $filters)
    {
        return $this->model
            ->with(['local', 'organizador', 'ingressos'])
            ->filter($filters)
            ->orderBy('data_inicio', 'desc')
            ->get();
    }

    /**
     * Listagem com filtros e paginação
     */
    public function listagemComFiltrosPaginado(EventoFilter $filters, int $perPage = 15)
    {
        return $this->model
             ->proximos()
            ->with(['local', 'organizador', 'ingressos'])
            ->filter($filters)
            ->orderBy('data_inicio', 'asc')
            ->paginate($perPage);
    }

    
    /**
     * Buscar eventos por local
     */
    public function buscarPorLocal($localId)
    {
        return $this->model
            ->where('local_id', $localId)
            ->with(['organizador', 'ingressos'])
            ->orderBy('data_inicio', 'desc')
            ->get();
    }

    /**
     * Buscar eventos por organizador
     */
    public function buscarPorOrganizador($userId)
    {
        return $this->model
            ->where('user_id', $userId)
            ->with(['local', 'ingressos'])
            ->orderBy('data_inicio', 'desc')
            ->get();
    }

    /**
     * Eventos publicados e futuros
     */
    public function eventosPublicados()
    {
        return $this->model
            ->publicados()
            ->proximos()
            ->with(['local', 'organizador'])
            ->orderBy('data_inicio', 'asc')
            ->get();
    }

    /**
     * Eventos com vagas disponíveis
     */
    public function eventosComVagasDisponiveis()
    {
        return $this->model
            ->publicados()
            ->proximos()
            ->comVagas()
            ->with(['local', 'ingressos'])
            ->orderBy('data_inicio', 'asc')
            ->get();
    }

    /**
     * Próximos eventos (limitado)
     */
    public function eventosProximos(int $limit = 10)
    {
        return $this->model->proximos()
            ->with(['local'])
            ->orderBy('data_inicio', 'asc')
            ->get();
    }

    /**
     * Eventos do mês atual
     */
    public function eventosMesAtual()
    {
        return $this->model
            ->whereMonth('data_inicio', now()->month)
            ->whereYear('data_inicio', now()->year)
            ->with(['local'])
            ->orderBy('data_inicio', 'asc')
            ->get();
    }

    /**
     * Alterar status do evento
     */
    public function alterarStatus($id, string $status)
    {
        $evento = $this->model->find($id);
        
        if (!$evento) {
            throw new \Exception("Evento não encontrado");
        }
        
        $evento->status = $status;
        $evento->save();
        
        return $this->capturar($evento->id);
    }
    public function uploadImage($image, $path = 'eventos'){
        if (!$image->isValid()) {
            throw new \Exception('Arquivo de imagem inválido.');
        }
            $extension = $image->getClientOriginalExtension();
            $imageName = md5($image->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $image->storeAS('public/' . $path, $imageName);
          $this->model->imagem = $imageName;
         $this->model->save();
         return $this->model;
    }

    /**
     * Estatísticas de eventos
     */
    public function estatisticas()
    {
        return [
            'total' => $this->model->count(),
            'ativos' => $this->model->where('ativo', true)->count(),
            'publicados' => $this->model->where('status', 'publicado')->count(),
            'futuros' => $this->model->where('data_inicio', '>=', now())->count(),
            'em_andamento' => $this->model->emAndamento()->count(),
            'passados' => $this->model->where('data_fim', '<', now())->count(),
        ];
    }
}