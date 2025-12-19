<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientesRequest;
use Illuminate\Http\Request;
use App\Models\Clientes;
use APP\Repository;
use App\Repository\ClientesRepository;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Cache\Repository as CacheRepository;

class  ClientesController extends Controller
{ private $repository;
   
    public function __construct()
    {  
      $this->repository = new ClientesRepository();
        
    } 

    public function listagem(){
      $clientes = $this->repository->listagem();
      return view('clientes.listagem', [
        'clientes' => $clientes
    ]);
    }
    

public function formulario(Request $request, $id = null){
  $clientes = $this->repository->capturar($id);
  
  return view('clientes.formulario',['clientes' => $clientes]);
}


public function salvar(ClientesRequest $request){
$dados = $request->all();
$cliente = $this->repository->salvar($dados);


Toastr::success("Cliente salvo com sucesso!", "Sucesso!");

return redirect()->to(url("/clientes"));
}
public function excluir (Request $request, $id){
$retorno = $this->repository->excluir($id);

return response()->json([
  "status" => $retorno
]);
}
}
