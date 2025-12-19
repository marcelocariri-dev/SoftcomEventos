<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientesRequest;
use App\Http\Requests\GruposRequest;
use Illuminate\Http\Request;
use App\Models\Clientes;
use APP\Repository;
use App\Repository\GruposRepository;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Cache\Repository as CacheRepository;

class  GruposController extends Controller
{ private $repository;
   
    public function __construct()
    {  
      $this->repository = new GruposRepository();
        
    } 

    public function listagem(){
      $grupos = $this->repository->listagem();
      return view('grupo.listagemGrupo', [
        'grupos' => $grupos
    ]);
    }
    

public function formulario(Request $request, $id = null){
  $grupos = $this->repository->capturar($id);
  
  return view('grupo.formularioGrupo',['grupos' => $grupos]);
}


public function salvar(GruposRequest $request){
$dados = $request->all();
$grupo = $this->repository->salvar($dados);


Toastr::success("Cliente salvo com sucesso!", "Sucesso!");

return redirect()->to(url("/grupos"));
}
public function excluir (Request $request, $id){
$retorno = $this->repository->excluir($id);

return response()->json([
  "status" => $retorno
]);
}
}
