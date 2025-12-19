<?php

namespace App\Http\Controllers\Api;

use App\Repository\LocaisRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\LocalRequest;
use App\Http\Resources\LocalResource;
use App\Repository\EventoRepository;
use App\Filters\LocalFilter;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class  LocalApiController extends Controller
{ private $repository;
   
    public function __construct(LocaisRepository $repository)
    {  
      $this->repository = $repository;
        
    } 

   //GET /api/local - Listar todos os locais - INDEX => convenção

   public function index(Request $request){
    try{
$filters = new LocalFilter($request);
$perPage = $request->input('per_page', 15);
$local = $this->repository->listagemComFiltrosPaginado($filters, $perPage);

return LocalResource::collection($local);

    }
    catch (\Exception $ex){ 
      return response()->json(['message' => 'Erro ao listar local',
    'error' => $ex->getMessage()
    
    ], 500);
      

    }
   }

//mostra local específico

public function show($id)
    {
        try {
            $local = $this->repository->capturar($id);

            if (!$local) {
                return response()->json([
                    'message' => 'Evento não encontrado'
                ], 404);
            }

            return new LocalResource($local);
            
          } catch (\Exception $ex) {
            // ver storage/logs)
            Log::error('Erro ao buscar local', [
                'id' => $id,
                'exception' => $ex,
                'trace' => $ex->getTraceAsString()
            ]);
    
           
            return response()->json([
                'message' => 'Erro ao buscar local'
            ], 500);
        }
    }

//post - criar /api/local

public function store (LocalRequest $request){
try {
  
  $dados = $request->all();

  $local = $this->repository->salvar($dados);
  return response()->json([
    'message' => 'Evento criado com sucesso',
    'data' => new LocalResource($local)
], 201);
} catch(\Exception $ex){
  return response()->json(['message' =>"Erro ao criar local" ,
  'error' => $ex->getmessage(),
  ],500);
}
}



//put  PUT /api/local/{id} 
public function update (LocalRequest $request, $id){
  $local = $this->repository->capturar($id);
try{
  if(!$local){
    return response()->json([
      'message' => 'Evento não encontrado'
  ], 404);
}
$dados = $request->all();
$dados['id'] = $id;

$local = $this->repository->salvar($dados);
return response()->json([
  'message' => 'Evento atualizado com sucesso',
  'data' => new LocalResource($local)
], 200);

} catch(\Exception $ex) {
  return response()->json([
    'message' => 'Nõ foi possível atualiza o local',
    'error' => $ex->getmessage()], 500);
  
}
}

//delete  /api/local/{id}
public function destroy ($id){ try{
  $local = $this->repository->capturar($id);
  if(!$local){
    return response()->json([
      'message' => 'Evento não encontrado'
  ], 404); }
 
 
  $this->repository->excluir($local);
 return response()->json([
  'message' => 'Evento excluído com sucesso'
], 200);


}catch (\Exception $ex) {
  return response()->json([
    'message' => 'Erro ao excluir local',
    'error' => $ex->getMessage()
], 500);
}
}

 /**
     * GET /api/locais/cidades
     */
    public function cidades()
    {
        try {
            $cidades = $this->repository->contarPorCidade();

            return response()->json([
                'data' => $cidades
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao listar cidades',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/locais/estatisticas
     */
    public function estatisticas()
    {
        try {
            $stats = $this->repository->estatisticas();

            return response()->json([
                'data' => $stats
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar estatísticas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
