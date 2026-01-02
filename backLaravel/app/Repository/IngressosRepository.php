<?php
// app/Repository/IngressosRepository.php

namespace App\Repository;

use App\Models\Ingresso;
use App\Filters\IngressoFilter;

class IngressosRepository
{
    private $model;
    
    public function __construct()
    {  
        $this->model = new Ingresso();
    }


    public function listagem()
    {
        return $this->model
            ->with(['evento.local'])
            ->orderBy('evento_id', 'desc')
            ->orderBy('tipo_ingresso', 'asc')
            ->get();
    }

    public function listagemAtivos()
    {
        return $this->model
            ->ativos()
            ->with(['evento'])
            ->orderBy('evento_id', 'desc')
            ->get();
    }

    public function capturar($id)
    {
        return $this->model
            ->with(['evento.local', 'inscricoes'])
            ->find($id);
    }

    public function salvar(array $dados)
    { 
        $id = $dados['id'] ?? null;

        $ingresso = $this->model->findOrNew($id);
        $ingresso->fill($dados);
        $ingresso->save();
        
        return $this->capturar($ingresso->id);
    }

    public function excluir($id)
    {
        $ingresso = $this->model->find($id);
        
        if (!$ingresso) {
            throw new \Exception("Ingresso não encontrado");
        }

        if ($ingresso->inscricoes()->count() > 0) {
            throw new \Exception("Não é possível excluir ingresso com inscrições cadastradas");
        }

        $ingresso->delete();
        return true;
    }

  
    public function listagemComFiltros(IngressoFilter $filters)
    {
        return $this->model
            ->with(['evento'])
            ->filter($filters)
            ->orderBy('evento_id', 'desc')
            ->get();
    }

    public function listagemComFiltrosPaginado(IngressoFilter $filters, int $perPage = 15)
    {
        return $this->model
            ->with(['evento'])
            ->filter($filters)
            ->orderBy('evento_id', 'desc')
            ->paginate($perPage);
    }


   

    public function buscarPorEvento($eventoId)
    {
        return $this->model
            ->where('evento_id', $eventoId)
            ->ativos()
            ->orderBy('tipo_ingresso', 'asc')
            ->get();
    }

    public function ingressosDisponiveis($eventoId)
    {
        return $this->model
            ->where('evento_id', $eventoId)
            ->ativos()
            ->disponiveis()
            ->orderBy('valor', 'asc')
            ->get();
    }

  

 
  

    public function alterarStatus($id, bool $status)
    {
        $ingresso = $this->model->find($id);
        
        if (!$ingresso) {
            throw new \Exception("Ingresso não encontrado");
        }
        
        $ingresso->ativo = $status;
        $ingresso->save();
        
        return $this->capturar($ingresso->id);
    }

    //public function estatisticas()
    //{
       // return [
         //   'total' => $this->model->count(),
         ////   'ativos' => $this->model->where('ativo', true)->count(),
           // 'inativos' => $this->model->where('ativo', false)->count(),
          //  'disponiveis' => $this->model->where('quantidade_disponivel', '>', 0)->count(),
           // 'esgotados' => $this->model->where('quantidade_disponivel', '<=', 0)->count(),
           // 'gratuitos' => $this->model->where('valor', 0)->count(),
           // 'pagos' => $this->model->where('valor', '>', 0)->count(),
       // ];
   // }
}