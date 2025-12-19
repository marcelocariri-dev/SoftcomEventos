<?php

namespace App\Filters;

class InscricaoFilter extends QueryFilter
{
    // ==========================================
    // FILTROS POR ID
    // ==========================================

    /**
     * Filtrar por evento
     */
    public function eventoId($valor)
    {
        return $this->builder->where('evento_id', $valor);
    }

    /**
     * Filtrar por múltiplos eventos
     */
    public function eventosIn($valores)
    {
        $ids = explode(',', $valores);
        return $this->builder->whereIn('evento_id', $ids);
    }

    /**
     * Filtrar por participante
     */
    public function participanteId($valor)
    {
        return $this->builder->where('participante_id', $valor);
    }

    /**
     * Filtrar por múltiplos participantes
     */
    public function participantesIn($valores)
    {
        $ids = explode(',', $valores);
        return $this->builder->whereIn('participante_id', $ids);
    }

    /**
     * Filtrar por ingresso
     */
    public function ingressoId($valor)
    {
        return $this->builder->where('ingresso_id', $valor);
    }

    /**
     * Filtrar por múltiplos ingressos
     */
    public function ingressosIn($valores)
    {
        $ids = explode(',', $valores);
        return $this->builder->whereIn('ingresso_id', $ids);
    }

    /**
     * Inscrições sem ingresso associado
     */
    public function semIngresso($valor = true)
    {
        if ($this->hasValue($valor)) {
            return $this->builder->whereNull('ingresso_id');
        }
        return $this->builder->whereNotNull('ingresso_id');
    }

    // ==========================================
    // FILTROS POR CÓDIGO
    // ==========================================

    /**
     * Filtrar por código de inscrição exato
     */
    public function codigoInscricao($valor)
    {
        return $this->builder->where('codigo_inscricao', $valor);
    }

    /**
     * Busca parcial por código
     */
    public function codigo($valor)
    {
        return $this->whereLike('codigo_inscricao', $valor);
    }

    /**
     * Filtrar por múltiplos códigos
     */
    public function codigosIn($valores)
    {
        $codigos = explode(',', $valores);
        return $this->builder->whereIn('codigo_inscricao', $codigos);
    }

    // ==========================================
    // FILTROS POR STATUS
    // ==========================================

    /**
     * Filtrar por status
     */
    public function status($valor)
    {
        return $this->builder->where('status', $valor);
    }

    /**
     * Filtrar por múltiplos status
     */
    public function statusIn($valores)
    {
        $status = explode(',', $valores);
        return $this->builder->whereIn('status', $status);
    }

    /**
     * Apenas pendentes
     */
    public function pendentes($valor = true)
    {
        if ($this->hasValue($valor)) {
            return $this->builder->pendentes();
        }
        return $this->builder;
    }

    /**
     * Apenas confirmadas
     */
    public function confirmadas($valor = true)
    {
        if ($this->hasValue($valor)) {
            return $this->builder->confirmadas();
        }
        return $this->builder;
    }

    /**
     * Apenas canceladas
     */
    public function canceladas($valor = true)
    {
        if ($this->hasValue($valor)) {
            return $this->builder->canceladas();
        }
        return $this->builder;
    }

    /**
     * Apenas ativas (pendentes + confirmadas)
     */
    public function ativas($valor = true)
    {
        if ($this->hasValue($valor)) {
            return $this->builder->ativas();
        }
        return $this->builder;
    }

    // ==========================================
    // FILTROS POR VALOR
    // ==========================================

    /**
     * Filtrar por valor mínimo pago
     */
    public function valorMinimo($valor)
    {
        return $this->builder->where('valor_pago', '>=', $valor);
    }

    /**
     * Filtrar por valor máximo pago
     */
    public function valorMaximo($valor)
    {
        return $this->builder->where('valor_pago', '<=', $valor);
    }

    /**
     * Filtrar por valor exato
     */
    public function valorPago($valor)
    {
        return $this->builder->where('valor_pago', $valor);
    }

    /**
     * Filtrar por faixa de valor
     */
    public function valorRange($valores)
    {
        $range = explode(',', $valores);
        if (count($range) === 2) {
            return $this->builder->whereBetween('valor_pago', [$range[0], $range[1]]);
        }
        return $this->builder;
    }

    /**
     * Apenas inscrições gratuitas
     */
    public function gratuitas($valor = true)
    {
        if ($this->hasValue($valor)) {
            return $this->builder->where('valor_pago', 0);
        }
        return $this->builder->where('valor_pago', '>', 0);
    }

    /**
     * Apenas inscrições pagas
     */
    public function pagas($valor = true)
    {
        if ($this->hasValue($valor)) {
            return $this->builder->where('valor_pago', '>', 0);
        }
        return $this->builder->where('valor_pago', 0);
    }

    // ==========================================
    // FILTROS POR PARTICIPANTE
    // ==========================================

    /**
     * Filtrar por nome do participante
     */
    public function nomeParticipante($valor)
    {
        return $this->builder->whereHas('participante', function ($query) use ($valor) {
            $query->where('nome', 'like', "%{$valor}%");
        });
    }

    /**
     * Filtrar por CPF do participante
     */
    public function cpfParticipante($valor)
    {
        $cpf = preg_replace('/\D/', '', $valor);
        return $this->builder->whereHas('participante', function ($query) use ($cpf) {
            $query->where('cpf', $cpf);
        });
    }

    /**
     * Filtrar por email do participante
     */
    public function emailParticipante($valor)
    {
        return $this->builder->whereHas('participante', function ($query) use ($valor) {
            $query->where('email', 'like', "%{$valor}%");
        });
    }

    /**
     * Filtrar por telefone do participante
     */
    public function telefoneParticipante($valor)
    {
        $telefone = preg_replace('/\D/', '', $valor);
        return $this->builder->whereHas('participante', function ($query) use ($telefone) {
            $query->where('telefone', 'like', "%{$telefone}%");
        });
    }

    
   
    public function tituloEvento($valor)
    {
        return $this->builder->whereHas('evento', function ($query) use ($valor) {
            $query->where('titulo', 'like', "%{$valor}%");
        });
    }

    /**
     * Filtrar por status do evento
     */
    public function statusEvento($valor)
    {
        return $this->builder->whereHas('evento', function ($query) use ($valor) {
            $query->where('status', $valor);
        });
    }

    /**
     * Apenas inscrições de eventos publicados
     */
    public function eventosPublicados($valor = true)
    {
        if ($this->hasValue($valor)) {
            return $this->builder->whereHas('evento', function ($query) {
                $query->where('status', 'publicado');
            });
        }
        return $this->builder;
    }

    /**
     * Filtrar por eventos futuros
     */
    public function eventosFuturos($valor = true)
    {
        if ($this->hasValue($valor)) {
            return $this->builder->whereHas('evento', function ($query) {
                $query->where('data_inicio', '>=', now());
            });
        }
        return $this->builder;
    }

    /**
     * Filtrar por eventos passados
     */
    public function eventosPassados($valor = true)
    {
        if ($this->hasValue($valor)) {
            return $this->builder->whereHas('evento', function ($query) {
                $query->where('data_fim', '<', now());
            });
        }
        return $this->builder;
    }

    /**
     * Filtrar por eventos em andamento
     */
    public function eventosEmAndamento($valor = true)
    {
        if ($this->hasValue($valor)) {
            return $this->builder->whereHas('evento', function ($query) {
                $query->where('data_inicio', '<=', now())
                      ->where('data_fim', '>=', now());
            });
        }
        return $this->builder;
    }

    /**
     * Filtrar por cidade do evento
     */
    public function cidadeEvento($valor)
    {
        return $this->builder->whereHas('evento.local', function ($query) use ($valor) {
            $query->where('cidade', 'like', "%{$valor}%");
        });
    }

    /**
     * Filtrar por estado do evento
     */
    public function estadoEvento($valor)
    {
        return $this->builder->whereHas('evento.local', function ($query) use ($valor) {
            $query->where('estado', $valor);
        });
    }

    /**
     * Filtrar por local do evento
     */
    public function localId($valor)
    {
        return $this->builder->whereHas('evento', function ($query) use ($valor) {
            $query->where('local_id', $valor);
        });
    }

    /**
     * Filtrar por nome do local
     */
    public function nomeLocal($valor)
    {
        return $this->builder->whereHas('evento.local', function ($query) use ($valor) {
            $query->where('nome', 'like', "%{$valor}%");
        });
    }

    // ==========================================
    // FILTROS POR TIPO DE INGRESSO
    // ==========================================

    /**
     * Filtrar por tipo de ingresso
     */
    public function tipoIngresso($valor)
    {
        return $this->builder->whereHas('ingresso', function ($query) use ($valor) {
            $query->where('tipo_ingresso', $valor);
        });
    }

    /**
     * Filtrar por múltiplos tipos de ingresso
     */
    public function tiposIngressoIn($valores)
    {
        $tipos = explode(',', $valores);
        return $this->builder->whereHas('ingresso', function ($query) use ($tipos) {
            $query->whereIn('tipo_ingresso', $tipos);
        });
    }

    /**
     * Apenas ingressos inteira
     */
    public function ingressosInteira($valor = true)
    {
        if ($this->hasValue($valor)) {
            return $this->builder->whereHas('ingresso', function ($query) {
                $query->where('tipo_ingresso', 'inteira');
            });
        }
        return $this->builder;
    }

    /**
     * Apenas ingressos meia
     */

    /**
     * Apenas ingressos VIP
    


     * Apenas cortesias
  
 
     * Filtrar por data de inscrição (a partir de)
     */
    public function dataInscricaoInicio($valor)
    {
        return $this->builder->where('created_at', '>=', $valor);
    }

    /**
     * Filtrar por data de inscrição (até)
     */
    public function dataInscricaoFim($valor)
    {
        return $this->builder->where('created_at', '<=', $valor);
    }

    /**
     * Filtrar por período de inscrição
     */
    public function periodoInscricao($valores)
    {
        $datas = explode(',', $valores);
        if (count($datas) === 2) {
            return $this->builder->whereBetween('created_at', [$datas[0], $datas[1]]);
        }
        return $this->builder;
    }

    /**
     * Inscrições de hoje
     */
    public function inscritasHoje($valor = true)
    {
        if ($this->hasValue($valor)) {
            return $this->builder->whereDate('created_at', today());
        }
        return $this->builder;
    }

    /**
     * Inscrições desta semana
     */
    public function inscritasSemana($valor = true)
    {
        if ($this->hasValue($valor)) {
            return $this->builder->whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ]);
        }
        return $this->builder;
    }

    /**
     * Inscrições deste mês
     */
    public function inscritasMes($valor = true)
    {
        if ($this->hasValue($valor)) {
            return $this->builder->whereMonth('created_at', now()->month)
                                 ->whereYear('created_at', now()->year);
        }
        return $this->builder;
    }

    /**
     * Inscrições dos últimos X dias
     */
    public function ultimosDias($valor)
    {
        $data = now()->subDays($valor);
        return $this->builder->where('created_at', '>=', $data);
    }

    /**
     * Filtrar por data do evento (início)
     */
    public function dataEventoInicio($valor)
    {
        return $this->builder->whereHas('evento', function ($query) use ($valor) {
            $query->where('data_inicio', '>=', $valor);
        });
    }

    /**
     * Filtrar por data do evento (fim)
     */
    public function dataEventoFim($valor)
    {
        return $this->builder->whereHas('evento', function ($query) use ($valor) {
            $query->where('data_inicio', '<=', $valor);
        });
    }

    // ==========================================
    // BUSCA GERAL
    // ==========================================

    /**
     * Busca geral (código, nome participante, email, título evento)
     */
    public function busca($valor)
    {
        return $this->builder->where(function ($query) use ($valor) {
            $query->where('codigo_inscricao', 'like', "%{$valor}%")
                  ->orWhereHas('participante', function ($q) use ($valor) {
                      $q->where('nome', 'like', "%{$valor}%")
                        ->orWhere('email', 'like', "%{$valor}%")
                        ->orWhere('cpf', 'like', "%{$valor}%");
                  })
                  ->orWhereHas('evento', function ($q) use ($valor) {
                      $q->where('titulo', 'like', "%{$valor}%");
                  });
        });
    }

    // ==========================================
    // ORDENAÇÃO
    // ==========================================

    /**
     * Ordenação customizada
     */
    public function orderBy($valor)
    {
        $campos = [
            'recentes' => ['created_at', 'desc'],
            'antigas' => ['created_at', 'asc'],
            'codigo' => ['codigo_inscricao', 'asc'],
            'codigo_desc' => ['codigo_inscricao', 'desc'],
            'valor_maior' => ['valor_pago', 'desc'],
            'valor_menor' => ['valor_pago', 'asc'],
            'status' => ['status', 'asc'],
            'status_desc' => ['status', 'desc'],
        ];

        if (isset($campos[$valor])) {
            return $this->builder->orderBy($campos[$valor][0], $campos[$valor][1]);
        }

        // Ordenação por nome do participante
        if ($valor === 'participante') {
            return $this->builder->join('participantes', 'inscricoes.participante_id', '=', 'participantes.id')
                                 ->orderBy('participantes.nome', 'asc')
                                 ->select('inscricoes.*');
        }

        // Ordenação por evento
        if ($valor === 'evento') {
            return $this->builder->join('eventos', 'inscricoes.evento_id', '=', 'eventos.id')
                                 ->orderBy('eventos.titulo', 'asc')
                                 ->select('inscricoes.*');
        }

        return $this->builder;
    }

    // ==========================================
    // FILTROS AVANÇADOS
    // ==========================================

    /**
     * Inscrições que podem ser canceladas
   

  
     * Inscrições de eventos lotados
     */
    public function eventosLotados($valor = true)
    {
        if ($this->hasValue($valor)) {
            return $this->builder->whereHas('evento', function ($query) {
                $query->whereRaw('(SELECT COUNT(*) FROM inscricoes WHERE evento_id = eventos.id AND status IN ("pendente", "confirmado")) >= capacidade_maxima');
            });
        }
        return $this->builder;
    }

    /**
     * Inscrições de eventos com vagas
     */
    public function eventosComVagas($valor = true)
    {
        if ($this->hasValue($valor)) {
            return $this->builder->whereHas('evento', function ($query) {
                $query->whereRaw('(SELECT COUNT(*) FROM inscricoes WHERE evento_id = eventos.id AND status IN ("pendente", "confirmado")) < capacidade_maxima');
            });
        }
        return $this->builder;
    }

    /**
     * Filtrar por quantidade mínima de inscrições do participante
     */
   
}