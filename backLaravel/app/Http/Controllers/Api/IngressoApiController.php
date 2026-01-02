<?php
// app/Http/Controllers/Api/IngressoApiController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IngressoRequest;
use App\Http\Resources\IngressoResource;
use App\Repository\IngressosRepository;
use App\Filters\IngressoFilter;
use Illuminate\Http\Request;

class IngressoApiController extends Controller
{
    private $repository;

    public function __construct(IngressosRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * GET /api/ingressos
     */
    public function index(Request $request)
    {
        try {
            $filters = new IngressoFilter($request);
            $perPage = $request->input('per_page', 15);
            
            $ingressos = $this->repository->listagemComFiltrosPaginado($filters, $perPage);

            return IngressoResource::collection($ingressos);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao listar ingressos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/ingressos/{id}
     */
    public function show($id)
    {
        try {
            $ingresso = $this->repository->capturar($id);

            if (!$ingresso) {
                return response()->json([
                    'message' => 'Ingresso não encontrado'
                ], 404);
            }

            return new IngressoResource($ingresso);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar ingresso',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * POST /api/ingressos
     */
    public function store(IngressoRequest $request)
    {
        try {
            $dados = $request->all();
            $ingresso = $this->repository->salvar($dados);

            return response()->json([
                'message' => 'Ingresso criado com sucesso',
                'data' => new IngressoResource($ingresso)
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar ingresso',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * PUT /api/ingressos/{id}
     */
    public function update(IngressoRequest $request, $id)
    {
        try {
            $ingresso = $this->repository->capturar($id);

            if (!$ingresso) {
                return response()->json([
                    'message' => 'Ingresso não encontrado'
                ], 404);
            }

            $dados = $request->all();
            $dados['id'] = $id;
            
            $ingresso = $this->repository->salvar($dados);

            return response()->json([
                'message' => 'Ingresso atualizado com sucesso',
                'data' => new IngressoResource($ingresso)
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar ingresso',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * DELETE /api/ingressos/{id}
     */
    public function destroy($id)
    {
        try {
            $this->repository->excluir($id);

            return response()->json([
                'message' => 'Ingresso excluído com sucesso'
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao excluir ingresso',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/ingressos/evento/{evento_id}
     */
    public function porEvento($evento_id)
    {
        try {
            $ingressos = $this->repository->buscarPorEvento($evento_id);

            return IngressoResource::collection($ingressos);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao listar ingressos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/ingressos/evento/{evento_id}/disponiveis
     */
    public function disponiveisPorEvento($evento_id)
    {
        try {
            $ingressos = $this->repository->ingressosDisponiveis($evento_id);

            return IngressoResource::collection($ingressos);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao listar ingressos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

   
}