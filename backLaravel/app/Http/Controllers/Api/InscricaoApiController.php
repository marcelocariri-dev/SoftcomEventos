<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InscricaoRequest;
use App\Http\Resources\InscricaoResource;
use App\Repository\InscricoesRepository;
use App\Filters\InscricaoFilter;
use Illuminate\Http\Request;

class InscricaoApiController extends Controller
{
    private $repository;

    public function __construct(InscricoesRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * GET /api/v1/inscricoes
     * Listar todas as inscrições
     */
    public function index(Request $request)
    {
        try {
            $filters = new InscricaoFilter($request);
            $perPage = $request->input('per_page', 15);
            
            $inscricoes = $this->repository->listagemComFiltrosPaginado($filters, $perPage);

            return InscricaoResource::collection($inscricoes);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao listar inscrições',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/v1/inscricoes/{id}
     * Buscar inscrição por ID
     */
    public function show($id)
    {
        try {
            $inscricao = $this->repository->capturar($id);

            if (!$inscricao) {
                return response()->json([
                    'message' => 'Inscrição não encontrada'
                ], 404);
            }

            return new InscricaoResource($inscricao);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar inscrição',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/v1/inscricoes/codigo/{codigo}
     * Buscar inscrição por código
     */
    public function buscarPorCodigo($codigo)
    {
        try {
            $inscricao = $this->repository->buscarPorCodigo($codigo);

            if (!$inscricao) {
                return response()->json([
                    'message' => 'Inscrição não encontrada'
                ], 404);
            }

            return new InscricaoResource($inscricao);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar inscrição',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * POST /api/v1/inscricoes
     * Criar inscrição
     */
    public function store(InscricaoRequest $request)
    {
        try {
            $dados = $request->validated();
            
            // Se usuário autenticado e não enviou participante_id
            if (auth()->check() && !isset($dados['participante_id'])) {
                $inscricao = $this->repository->criarInscricao(
                    $dados['evento_id'],
                    auth()->id(),
                    [
                        'nome' => $dados['nome'] ?? auth()->user()->name,
                        'cpf' => $dados['cpf'],
                        'email' => $dados['email'] ?? auth()->user()->email,
                        'telefone' => $dados['telefone'],
                        'data_nascimento' => $dados['data_nascimento'],
                    ],
                    $dados['ingresso_id'] ?? null,
                    $dados['valor_pago'] ?? 0
                );
            } else {
                // Criar inscrição normalmente
                $inscricao = $this->repository->salvar($dados);
            }

            return response()->json([
                'message' => 'Inscrição realizada com sucesso',
                'data' => new InscricaoResource($inscricao)
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar inscrição',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * PUT /api/v1/inscricoes/{id}
     * Atualizar inscrição
     */
    public function update(InscricaoRequest $request, $id)
    {
        try {
            $inscricao = $this->repository->capturar($id);

            if (!$inscricao) {
                return response()->json([
                    'message' => 'Inscrição não encontrada'
                ], 404);
            }

            $dados = $request->validated();
            $dados['id'] = $id;
            
            $inscricao = $this->repository->salvar($dados);

            return response()->json([
                'message' => 'Inscrição atualizada com sucesso',
                'data' => new InscricaoResource($inscricao)
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar inscrição',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * DELETE /api/v1/inscricoes/{id}
     * Excluir inscrição
     */
    public function destroy($id)
    {
        try {
            $this->repository->excluir($id);

            return response()->json([
                'message' => 'Inscrição excluída com sucesso'
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao excluir inscrição',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * PUT /api/v1/inscricoes/{id}/confirmar
     * Confirmar inscrição
     */
    public function confirmar($id)
    {
        try {
            $inscricao = $this->repository->confirmar($id);

            return response()->json([
                'message' => 'Inscrição confirmada com sucesso',
                'data' => new InscricaoResource($inscricao)
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao confirmar inscrição',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * PUT /api/v1/inscricoes/{id}/cancelar
     * Cancelar inscrição
     */
    public function cancelar($id)
    {
        try {
            $inscricao = $this->repository->cancelar($id);

            return response()->json([
                'message' => 'Inscrição cancelada com sucesso',
                'data' => new InscricaoResource($inscricao)
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao cancelar inscrição',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/v1/inscricoes/evento/{evento_id}
     * Listar inscrições de um evento
     */
    public function porEvento($evento_id)
    {
        try {
            $inscricoes = $this->repository->buscarPorEvento($evento_id);

            return InscricaoResource::collection($inscricoes);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao listar inscrições',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/v1/inscricoes/participante/{participante_id}
     * Listar inscrições de um participante
     */
    public function porParticipante($participante_id)
    {
        try {
            $inscricoes = $this->repository->buscarPorParticipante($participante_id);

            return InscricaoResource::collection($inscricoes);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao listar inscrições',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/v1/inscricoes/minhas
     * Minhas inscrições (usuário autenticado)
     */
    public function minhas(Request $request)
    {
        try {
            $user = $request->user();
            
            if (!$user->temParticipante()) {
                return response()->json([
                    'message' => 'Você ainda não possui dados de participante cadastrados',
                    'data' => []
                ], 200);
            }

            $inscricoes = $this->repository->buscarPorParticipante($user->participante->id);

            return InscricaoResource::collection($inscricoes);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao listar inscrições',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/v1/inscricoes/estatisticas
     * Estatísticas gerais de inscrições
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

    /**
     * GET /api/v1/inscricoes/evento/{evento_id}/estatisticas
     * Estatísticas de inscrições de um evento específico
     */
    public function estatisticasPorEvento($evento_id)
    {
        try {
            $stats = [
                'total' => $this->repository->contarInscritosPorEvento($evento_id),
                'confirmadas' => $this->repository->contarInscritosPorEvento($evento_id, 'confirmado'),
                'pendentes' => $this->repository->contarInscritosPorEvento($evento_id, 'pendente'),
                'canceladas' => $this->repository->contarInscritosPorEvento($evento_id, 'cancelado'),
            ];

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