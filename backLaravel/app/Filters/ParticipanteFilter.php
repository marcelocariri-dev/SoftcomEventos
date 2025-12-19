<?php
// app/Filters/ParticipanteFilter.php

namespace App\Filters;

use Carbon\Carbon;

class ParticipanteFilter extends QueryFilter
{
    /**
     * Filtrar por nome
     */
    public function nome($value)
    {
        $this->whereLike('nome', $value);
    }

    /**
     * Filtrar por CPF
     */
    public function cpf($value)
    {
        $cpf = preg_replace('/[^0-9]/', '', $value);
        $this->builder->where('cpf', 'like', "%{$cpf}%");
    }

    /**
     * Filtrar por CPF exato
     */
    public function cpfExato($value)
    {
        $cpf = preg_replace('/[^0-9]/', '', $value);
        $this->builder->where('cpf', $cpf);
    }

    /**
     * Filtrar por email
     */
    public function email($value)
    {
        $this->whereLike('email', $value);
    }

    /**
     * Filtrar por email exato
     */
    public function emailExato($value)
    {
        $this->builder->where('email', strtolower(trim($value)));
    }

    /**
     * Filtrar por telefone
     */
    public function telefone($value)
    {
        $telefone = preg_replace('/[^0-9]/', '', $value);
        $this->builder->where('telefone', 'like', "%{$telefone}%");
    }

    /**
     * Filtrar por telefone exato
     */
    public function telefoneExato($value)
    {
        $telefone = preg_replace('/[^0-9]/', '', $value);
        $this->builder->where('telefone', $telefone);
    }

    /**
     * Filtrar por data de nascimento (início)
     */
    public function dataNascimentoInicio($value)
    {
        $this->whereDate('data_nascimento', '>=', $value);
    }

    /**
     * Filtrar por data de nascimento (fim)
     */
    public function dataNascimentoFim($value)
    {
        $this->whereDate('data_nascimento', '<=', $value);
    }

    /**
     * Filtrar por período de nascimento
     */
    public function periodoNascimento($value)
    {
        if (is_array($value) && isset($value['inicio']) && isset($value['fim'])) {
            $this->whereBetween('data_nascimento', $value['inicio'], $value['fim']);
        }
    }

    /**
     * Filtrar por idade mínima
     */
    public function idadeMinima($value)
    {
        if (is_numeric($value) && $value >= 0) {
            $dataMaxima = Carbon::now()->subYears($value)->format('Y-m-d');
            $this->whereDate('data_nascimento', '<=', $dataMaxima);
        }
    }

    /**
     * Filtrar por idade máxima
     */
    public function idadeMaxima($value)
    {
        if (is_numeric($value) && $value >= 0) {
            $dataMinima = Carbon::now()->subYears($value + 1)->addDay()->format('Y-m-d');
            $this->whereDate('data_nascimento', '>=', $dataMinima);
        }
    }

    /**
     * Filtrar por faixa etária
     */
    public function faixaEtaria($value)
    {
        if (is_array($value) && isset($value['min']) && isset($value['max'])) {
            $dataMax = Carbon::now()->subYears($value['min'])->format('Y-m-d');
            $dataMin = Carbon::now()->subYears($value['max'] + 1)->addDay()->format('Y-m-d');
            
            $this->whereBetween('data_nascimento', $dataMin, $dataMax);
        }
    }

    /**
     * Filtrar apenas maiores de idade
     */
    public function maioresDeIdade($value)
    {
        if ($value == '1' || $value === true) {
            $dataLimite = Carbon::now()->subYears(18)->format('Y-m-d');
            $this->whereDate('data_nascimento', '<=', $dataLimite);
        }
    }

    /**
     * Filtrar apenas menores de idade
     */
    public function menoresDeIdade($value)
    {
        if ($value == '1' || $value === true) {
            $dataLimite = Carbon::now()->subYears(18)->format('Y-m-d');
            $this->whereDate('data_nascimento', '>', $dataLimite);
        }
    }

    /**
     * Filtrar participantes com inscrições
     */
    public function comInscricoes($value)
    {
        if ($value == '1' || $value === true) {
            $this->builder->has('inscricoes');
        }
    }

    /**
     * Filtrar participantes sem inscrições
     */
    public function semInscricoes($value)
    {
        if ($value == '1' || $value === true) {
            $this->builder->doesntHave('inscricoes');
        }
    }

    /**
     * Filtrar participantes com inscrições confirmadas
     */
    public function comInscricoesConfirmadas($value)
    {
        if ($value == '1' || $value === true) {
            $this->builder->whereHas('inscricoes', function($query) {
                $query->where('status', 'confirmado');
            });
        }
    }

    /**
     * Filtrar participantes com inscrições pendentes
     */
    public function comInscricoesPendentes($value)
    {
        if ($value == '1' || $value === true) {
            $this->builder->whereHas('inscricoes', function($query) {
                $query->where('status', 'pendente');
            });
        }
    }

    /**
     * Filtrar por quantidade mínima de inscrições
     */
    public function quantidadeInscricoesMinima($value)
    {
        if (is_numeric($value) && $value >= 0) {
            $this->builder->has('inscricoes', '>=', $value);
        }
    }

    /**
     * Filtrar por quantidade máxima de inscrições
     */
    public function quantidadeInscricoesMaxima($value)
    {
        if (is_numeric($value) && $value >= 0) {
            $this->builder->has('inscricoes', '<=', $value);
        }
    }

    /**
     * Filtrar participantes inscritos em evento específico
     */
    public function eventoId($value)
    {
        if (is_numeric($value)) {
            $this->builder->whereHas('inscricoes', function($query) use ($value) {
                $query->where('evento_id', $value);
            });
        }
    }

    /**
     * Filtrar participantes inscritos em múltiplos eventos
     */
    public function eventosIn($value)
    {
        if (is_array($value)) {
            $this->builder->whereHas('inscricoes', function($query) use ($value) {
                $query->whereIn('evento_id', $value);
            });
        }
    }

    /**
     * Filtrar participantes vinculados a usuário
     */
    public function comUsuario($value)
    {
        if ($value == '1' || $value === true) {
            $this->builder->whereNotNull('user_id');
        }
    }

    /**
     * Filtrar participantes sem usuário
     */
    public function semUsuario($value)
    {
        if ($value == '1' || $value === true) {
            $this->builder->whereNull('user_id');
        }
    }

    /**
     * Filtrar por ID do usuário
     */
    public function userId($value)
    {
        if (is_numeric($value)) {
            $this->builder->where('user_id', $value);
        }
    }

    /**
     * Filtrar por status ativo
     */
    public function ativo($value)
    {
        $this->builder->where('ativo', (bool) $value);
    }

 

    /**
     * Filtrar por cadastros recentes (últimos X dias)
     */
    public function cadastradosUltimosDias($value)
    {
        if (is_numeric($value) && $value > 0) {
            $dataLimite = Carbon::now()->subDays($value);
            $this->builder->where('created_at', '>=', $dataLimite);
        }
    }

    /**
     * Busca geral (nome OU email)
     */
    public function busca($value)
    {
        $this->builder->where(function($query) use ($value) {
            $query->where('nome', 'like', "%{$value}%")
                  ->orWhere('email', 'like', "%{$value}%");
        });
    }

    /**
     * Ordenar resultados
     */
    public function orderBy($value)
    {
        $allowedFields = [
            'nome',
            'email',
            'data_nascimento',
            'created_at',
            'updated_at'
        ];
        
        if (in_array($value, $allowedFields)) {
            $direction = $this->get('orderDirection', 'asc');
            
            if (!in_array($direction, ['asc', 'desc'])) {
                $direction = 'asc';
            }
            
            $this->builder->orderBy($value, $direction);
        }
    }

    /**
     * Ordenar por atividade (mais inscrições)
     */
    public function maisAtivos($value)
    {
        if ($value == '1' || $value === true) {
            $this->builder->withCount('inscricoes')
                          ->orderBy('inscricoes_count', 'desc');
        }
    }
}