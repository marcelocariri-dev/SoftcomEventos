<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\EventoRequest;
use App\Http\Resources\EventoResource;
use App\Repository\EventoRepository;
use App\Filters\EventoFilter;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class  EventoApiController extends Controller
{ private $repository;
   
    public function __construct(EventoRepository $repository)
    {  
      $this->repository = $repository;
        
    } 

   //GET /api/eventos - Listar todos os eventos - INDEX => convenção

   public function index(Request $request){
    try{
$filters = new EventoFilter($request);
$perPage = $request->input('per_page', 15);
$eventos = $this->repository->listagemComFiltrosPaginado($filters, $perPage);

return EventoResource::collection($eventos);

    }
    catch (\Exception $ex){ 
      return response()->json(['message' => 'Erro ao listar eventos',
    'error' => $ex->getMessage()
    
    ], 500);
      

    }
   }

//mostra um evento específico

public function show($id)
    {
        try {
            $evento = $this->repository->capturar($id);

            if (!$evento) {
                return response()->json([
                    'message' => 'Evento não encontrado'
                ], 404);
            }

            return new EventoResource($evento);
            
          } catch (\Exception $ex) {
            // ver storage/logs)
            Log::error('Erro ao buscar evento', [
                'id' => $id,
                'exception' => $ex,
                'trace' => $ex->getTraceAsString()
            ]);
    
           
            return response()->json([
                'message' => 'Erro ao buscar evento'
            ], 500);
        }
    }

//post - criar /api/eventos

public function store (EventoRequest $request){
try {

  $dados = $request->all();
  if (!isset($dados['user_id'])) {
    $dados['user_id'] = auth()->id();
}

  $evento = $this->repository->salvar($dados);

  if ($request->hasFile('imagem') && $request->file('imagem')->isValid()) {
    
    $this->repository->uploadImage($request->file('imagem'));
    
  
    $evento->refresh(); 
}
  return response()->json([
    'message' => 'Evento criado com sucesso',
    'data' => new EventoResource($evento)
], 201);
} catch(\Exception $ex){
  return response()->json(['message' =>"Erro ao criar evento" ,
  'error' => $ex->getmessage(),
  ],500);
}
}



//put  PUT /api/eventos/{id} 
public function update (EventoRequest $request, $id){
  $evento = $this->repository->capturar($id);
try{
  if(!$evento){
    return response()->json([
      'message' => 'Evento não encontrado'
  ], 404);
}
$dados = $request->all();
$dados['id'] = $id;

$evento = $this->repository->salvar($dados);
return response()->json([
  'message' => 'Evento atualizado com sucesso',
  'data' => new EventoResource($evento)
], 200);

} catch(\Exception $ex) {
  return response()->json([
    'message' => 'Nõ foi possível atualiza o evento',
    'error' => $ex->getmessage()], 500);
  
}
}

//delete  /api/eventos/{id}
public function destroy ($id){ 
  try{
  $evento = $this->repository->capturar($id);
  if(!$evento){
    return response()->json([
      'message' => 'Evento não encontrado'
  ], 404); }
 
 
  $this->repository->excluir($id); 
 return response()->json([
  'message' => 'Evento excluído com sucesso'
], 200);


}catch (\Exception $ex) {
  return response()->json([
    'message' => 'Erro ao excluir evento',
    'error' => $ex->getMessage()
], 500);
}
}

 /**
     * GET /api/eventos/proximos
     * Listar próximos eventos
     */
    public function proximos()
    {
        try {
            $eventos = $this->repository->eventosProximos(10);

            return EventoResource::collection($eventos);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao listar eventos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/eventos/disponiveis
     * Listar eventos com vagas disponíveis
     */
    public function disponiveis()
    {
        try {
            $eventos = $this->repository->eventosComVagasDisponiveis();

            return EventoResource::collection($eventos);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao listar eventos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/eventos/estatisticas
     * Estatísticas gerais
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
