<?php

namespace App\Repository;

use Illuminate\Http\Request;
use App\Models\Local;
use Illuminate\Cache\Repository;
use App\Filters\LocalFilter;

class  LocaisRepository extends Repository
{
   private $model;
    public function __construct()
    {  
      $this->model = new Local();
        
    } 
    public function listagem (){
      return $this->model->with(['eventos'])->orderby('nome', 'asc')->get();
    }

    public function capturar ($id){
      return $this->model
      ->with(['eventos' => function($query) {
          $query->orderBy('data_inicio', 'desc');
      }])
      ->find($id);
    }


public function salvar ($dados)

{ 
  $id = $dados['id'] ?? null;

$local = $this->model->findOrNew($id);
 $local->fill($dados);
 $local->save();
 return $this->capturar($local->id);

}

public function excluir ($id){

  $models = $this->model->find($id);
 

 if ($models->eventos()->count() > 0) {
  throw new \Exception("Não é possível excluir local com eventos cadastrados");
}
$models->delete();
 return true;

}

public function listagemComFiltros(LocalFilter $filters){
  return $this->model
  ->with(['eventos'])
  ->filter($filters)
  ->orderBy('nome', 'asc')
  ->get();

}


public function listagemComFiltrosPaginado(LocalFilter $filters, $perPage = 15){
  return $this->model
  ->with(['eventos'])
  ->filter($filters)
  ->orderBy('nome', 'asc')
  ->paginate($perPage);

}
public function ativos (){
    return $this->model->ativos()->get();
}

public function BuscaNome($nome){
  return $this->model->where('nome', 'like', "%{$nome}%")
  ->ativos()
  ->orderBy('nome', 'desc')
  ->get();
}

public function buscarPorCidade(string $cidade)
    {
        return $this->model
            ->porCidade($cidade)
            ->ativos()
            ->orderBy('nome', 'asc')
            ->get();
    }

public function FiltroOpcional(LocalFilter $filters){
  return $this->model->Filter($filters)->get();
}

public function buscarSemEvento(){
  return $this->model
  ->SemEventos()
  ->orderBy('nome', 'asc')
  ->get();
}


public function buscarComEvento(){
  return $this->model
  ->ComEventos()
  ->orderBy('nome', 'asc')
  ->get();
}

}
