<?php
// app/Http/Controllers/Api/ParticipanteApiController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ParticipanteRequest;
use App\Http\Resources\ParticipanteResource;
use App\Repository\ParticipantesRepository;
use App\Filters\ParticipanteFilter;
use Illuminate\Http\Request;

class ParticipanteApiController extends Controller
{
    private $repository;

    public function __construct(ParticipantesRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * GET /api/participantes
     */
    public function index(Request $request)
    {
        try {
            $filters = new ParticipanteFilter($request);
            $perPage = $request->input('per_page', 15);
            
            $participantes = $this->repository->listagemComFiltrosPaginado($filters, $perPage);

            return ParticipanteResource::collection($participantes);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao listar participantes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/participantes/{id}
     */
    public function show($id)
    {
        try {
            $participante = $this->repository->capturar($id);

            if (!$participante) {
                return response()->json([
                    'message' => 'Participante não encontrado'
                ], 404);
            }

            return new ParticipanteResource($participante);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar participante',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * POST /api/participantes
     */
    public function store(ParticipanteRequest $request)
    {
        try {
            $dados = $request->all();
            
            if (!isset($dados['user_id']) && auth()->check()) {
                $dados['user_id'] = auth()->id();
            }

            $participante = $this->repository->salvar($dados);

            return response()->json([
                'message' => 'Participante criado com sucesso',
                'data' => new ParticipanteResource($participante)
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar participante',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * PUT /api/participantes/{id}
     */
    public function update(ParticipanteRequest $request, $id)
    {
        try {
            $participante = $this->repository->capturar($id);

            if (!$participante) {
                return response()->json([
                    'message' => 'Participante não encontrado'
                ], 404);
            }

            $dados = $request->all();
            $dados['id'] = $id;
            
            $participante = $this->repository->salvar($dados);

            return response()->json([
                'message' => 'Participante atualizado com sucesso',
                'data' => new ParticipanteResource($participante)
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar participante',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * DELETE /api/participantes/{id}
     */
    public function destroy($id)
    {
        try {
            $this->repository->excluir($id);

            return response()->json([
                'message' => 'Participante excluído com sucesso'
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao excluir participante',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/participantes/cpf/{cpf}
     */
    public function buscarPorCpf($cpf)
    {
        try {
            $participante = $this->repository->buscarPorCpf($cpf);

            if (!$participante) {
                return response()->json([
                    'message' => 'Participante não encontrado'
                ], 404);
            }

            return new ParticipanteResource($participante);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar participante',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/participantes/estatisticas
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