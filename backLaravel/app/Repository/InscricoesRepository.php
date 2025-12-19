<?php

namespace App\Repository;

use App\Models\Inscricao;
use App\Models\Participante;
use App\Filters\InscricaoFilter;

class InscricoesRepository
{
    private $model;
    
    public function __construct()
    {
        $this->model = new Inscricao();
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
            ->with(['evento', 'participante', 'ingresso'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Listagem de inscrições ativas (pendentes + confirmadas)
     */
    public function listagemAtivas()
    {
        return $this->model
            ->ativas()
            ->with(['evento', 'participante', 'ingresso'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Capturar inscrição por ID com relacionamentos
     */
    public function capturar($id)
    {
        return $this->model
            ->with(['evento.local', 'participante', 'ingresso'])
            ->find($id);
    }

    /**
     * Buscar por código de inscrição
     */
    public function buscarPorCodigo(string $codigo)
    {
        return $this->model
            ->with(['evento.local', 'participante', 'ingresso'])
            ->where('codigo_inscricao', $codigo)
            ->first();
    }

    /**
     * Salvar inscrição (criar ou atualizar)
     */
    public function salvar(array $dados)
    {
        $id = $dados['id'] ?? null;

        // Gerar código se for nova inscrição
        if (!$id && !isset($dados['codigo_inscricao'])) {
            $dados['codigo_inscricao'] = Inscricao::gerarCodigoInscricao();
        }

        // Status padrão
        if (!isset($dados['status'])) {
            $dados['status'] = 'pendente';
        }

        // Valor padrão
        if (!isset($dados['valor_pago'])) {
            $dados['valor_pago'] = 0;
        }

        $inscricao = $this->model->findOrNew($id);
        $inscricao->fill($dados);
        $inscricao->save();
        
        // Recarrega com relacionamentos
        return $this->capturar($inscricao->id);
    }

    /**
     * Criar inscrição para usuário autenticado
     */
    public function criarInscricao(int $eventoId, int $userId, array $dadosParticipante, $ingressoId = null, $valorPago = 0)
    {
        // Verificar se user já tem participante
        $participante = Participante::where('user_id', $userId)->first();

        // Se não tiver, criar
        if (!$participante) {
            $participante = Participante::create([
                'user_id' => $userId,
                'nome' => $dadosParticipante['nome'],
                'cpf' => $dadosParticipante['cpf'],
                'email' => $dadosParticipante['email'],
                'telefone' => $dadosParticipante['telefone'],
                'data_nascimento' => $dadosParticipante['data_nascimento'],
                'ativo' => true,
            ]);
        }

        // Verificar se já existe inscrição ativa
        $inscricaoExistente = $this->model
            ->where('evento_id', $eventoId)
            ->where('participante_id', $participante->id)
            ->ativas()
            ->first();

        if ($inscricaoExistente) {
            throw new \Exception('Você já está inscrito neste evento');
        }

        // Criar inscrição
        return $this->salvar([
            'evento_id' => $eventoId,
            'participante_id' => $participante->id,
            'ingresso_id' => $ingressoId,
            'codigo_inscricao' => Inscricao::gerarCodigoInscricao(),
            'status' => 'pendente',
            'valor_pago' => $valorPago,
        ]);
    }

    /**
     * Excluir inscrição
     */
    public function excluir($id)
    {
        $inscricao = $this->model->find($id);
        
        if (!$inscricao) {
            throw new \Exception("Inscrição não encontrada");
        }

        // Verificar se pode excluir
        if ($inscricao->estaConfirmada()) {
            throw new \Exception("Não é possível excluir inscrição confirmada. Use cancelar ao invés disso.");
        }

        $inscricao->delete();
        return true;
    }

    // ==========================================
    // FILTROS E LISTAGENS
    // ==========================================

    public function listagemComFiltros(InscricaoFilter $filters)
    {
        return $this->model
            ->with(['evento', 'participante', 'ingresso'])
            ->filter($filters)
            ->orderBy('created_at', 'desc')
            ->get();
    }



    public function listagemComFiltrosPaginado(InscricaoFilter $filters, int $perPage = 15)
    {
        return $this->model
            ->with(['evento', 'participante', 'ingresso'])
            ->filter($filters)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
    public function buscarPorEvento(int $eventoId)
    {
        return $this->model
            ->where('evento_id', $eventoId)
            ->with(['participante', 'ingresso'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Buscar inscrições por participante
     */
    public function buscarPorParticipante(int $participanteId)
    {
        return $this->model
            ->where('participante_id', $participanteId)
            ->with(['evento.local', 'ingresso'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Buscar inscrições por ingresso
     */
    public function buscarPorIngresso(int $ingressoId)
    {
        return $this->model
            ->where('ingresso_id', $ingressoId)
            ->with(['evento', 'participante'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // ==========================================
    // MÉTODOS POR STATUS
    // ==========================================

    /**
     * Inscrições confirmadas de um evento
     */
    public function confirmadasPorEvento(int $eventoId)
    {
        return $this->model
            ->where('evento_id', $eventoId)
            ->confirmadas()
            ->with(['participante'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Inscrições pendentes de um evento
     */
    public function pendentesPorEvento(int $eventoId)
    {
        return $this->model
            ->where('evento_id', $eventoId)
            ->pendentes()
            ->with(['participante'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Inscrições canceladas de um evento
     */
    public function canceladasPorEvento(int $eventoId)
    {
        return $this->model
            ->where('evento_id', $eventoId)
            ->canceladas()
            ->with(['participante'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Todas as inscrições pendentes (geral)
     */
    public function todasPendentes()
    {
        return $this->model
            ->pendentes()
            ->with(['evento', 'participante'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Todas as inscrições confirmadas (geral)
     */
    public function todasConfirmadas()
    {
        return $this->model
            ->confirmadas()
            ->with(['evento', 'participante'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // ==========================================
    // AÇÕES DE ALTERAÇÃO DE STATUS
    // ==========================================

    /**
     * Confirmar inscrição
     */
    public function confirmar(int $id)
    {
        $inscricao = $this->capturar($id);
        
        if (!$inscricao) {
            throw new \Exception("Inscrição não encontrada");
        }

        if ($inscricao->estaConfirmada()) {
            throw new \Exception("Inscrição já está confirmada");
        }

        if ($inscricao->estaCancelada()) {
            throw new \Exception("Não é possível confirmar inscrição cancelada");
        }

        $inscricao->confirmar();
        
        return $this->capturar($inscricao->id);
    }

    /**
     * Cancelar inscrição
     */
    public function cancelar(int $id)
    {
        $inscricao = $this->capturar($id);
        
        if (!$inscricao) {
            throw new \Exception("Inscrição não encontrada");
        }

        if ($inscricao->estaCancelada()) {
            throw new \Exception("Inscrição já está cancelada");
        }

        if (!$inscricao->podeCancelar()) {
            throw new \Exception("Esta inscrição não pode mais ser cancelada (evento já iniciou)");
        }

        $inscricao->cancelar();
        
        return $this->capturar($inscricao->id);
    }

    /**
     * Confirmar múltiplas inscrições de uma vez
     */
    public function confirmarEmLote(array $ids)
    {
        $confirmadas = [];
        $erros = [];

        foreach ($ids as $id) {
            try {
                $confirmadas[] = $this->confirmar($id);
            } catch (\Exception $e) {
                $erros[$id] = $e->getMessage();
            }
        }

        return [
            'confirmadas' => $confirmadas,
            'erros' => $erros,
            'total_confirmadas' => count($confirmadas),
            'total_erros' => count($erros),
        ];
    }

    // ==========================================
    // VERIFICAÇÕES E VALIDAÇÕES
    // ==========================================

    /**
     * Verificar se participante já está inscrito em evento
     */
    public function participanteEstaInscrito(int $eventoId, int $participanteId)
    {
        return $this->model
            ->where('evento_id', $eventoId)
            ->where('participante_id', $participanteId)
            ->ativas()
            ->exists();
    }

    /**
     * Verificar se evento está lotado
     */
    public function eventoEstaLotado(int $eventoId)
    {
        $evento = \App\Models\Evento::find($eventoId);
        
        if (!$evento) {
            throw new \Exception("Evento não encontrado");
        }

        $inscritosAtivos = $this->contarInscritosPorEvento($eventoId);

        return $inscritosAtivos >= $evento->capacidade_maxima;
    }

    // ==========================================
    // CONTAGENS E ESTATÍSTICAS
    // ==========================================

    /**
     * Contar inscritos por evento
     */
    public function contarInscritosPorEvento(int $eventoId, string $status = null)
    {
        $query = $this->model->where('evento_id', $eventoId);

        if ($status) {
            $query->where('status', $status);
        } else {
            $query->ativas();
        }

        return $query->count();
    }

    /**
     * Contar inscrições por participante
     */
    public function contarInscricoesPorParticipante(int $participanteId, string $status = null)
    {
        $query = $this->model->where('participante_id', $participanteId);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->count();
    }

    /**
     * Estatísticas gerais de inscrições
     */
    public function estatisticas()
    {
        return [
            'total' => $this->model->count(),
            'pendentes' => $this->model->pendentes()->count(),
            'confirmadas' => $this->model->confirmadas()->count(),
            'canceladas' => $this->model->canceladas()->count(),
            'ativas' => $this->model->ativas()->count(),
            'valor_total_arrecadado' => number_format($this->model->confirmadas()->sum('valor_pago'), 2, '.', ''),
            'valor_pendente' => number_format($this->model->pendentes()->sum('valor_pago'), 2, '.', ''),
            'ticket_medio' => number_format($this->model->confirmadas()->avg('valor_pago'), 2, '.', ''),
        ];
    }

    /**
     * Estatísticas por período
     */
    public function estatisticasPorPeriodo(\DateTime $dataInicio, \DateTime $dataFim)
    {
        $query = $this->model
            ->whereBetween('created_at', [$dataInicio, $dataFim]);

        return [
            'total' => $query->count(),
            'confirmadas' => (clone $query)->confirmadas()->count(),
            'pendentes' => (clone $query)->pendentes()->count(),
            'canceladas' => (clone $query)->canceladas()->count(),
            'valor_arrecadado' => number_format((clone $query)->confirmadas()->sum('valor_pago'), 2, '.', ''),
        ];
    }

   
  
   

   
   

   

    
        
}