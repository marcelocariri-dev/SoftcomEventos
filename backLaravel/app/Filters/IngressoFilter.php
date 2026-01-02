<?php
// app/Filters/IngressoFilter.php

namespace App\Filters;

class IngressoFilter extends QueryFilter
{
    /**
     * Filtrar por evento
     */
    public function eventoId($value)
    {
        if (is_numeric($value)) {
            $this->builder->where('evento_id', $value);
        }
    }

    /**
     * Filtrar por múltiplos eventos
     */
    public function eventosIn($value)
    {
        if (is_array($value)) {
            $this->whereIn('evento_id', $value);
        }
    }

    /**
     * Filtrar por título do evento
     */
    public function eventoTitulo($value)
    {
        $this->builder->whereHas('evento', function($query) use ($value) {
            $query->where('titulo', 'like', "%{$value}%");
        });
    }

    /**
     * Filtrar por tipo de ingresso
     */
    public function tipoIngresso($value)
    {
        $allowedTypes = ['inteira', 'meia', 'vip', 'cortesia'];
        
        if (in_array($value, $allowedTypes)) {
            $this->builder->where('tipo_ingresso', $value);
        }
    }

    /**
     * Filtrar por múltiplos tipos
     */
    public function tiposIn($value)
    {
        if (is_array($value)) {
            $allowedTypes = ['inteira', 'meia', 'vip', 'cortesia'];
            $validTypes = array_intersect($value, $allowedTypes);
            
            if (!empty($validTypes)) {
                $this->whereIn('tipo_ingresso', $validTypes);
            }
        }
    }

    /**
     * Filtrar por valor mínimo
     */
    public function valorMinimo($value)
    {
        if (is_numeric($value) && $value >= 0) {
            $this->builder->where('valor', '>=', $value);
        }
    }

    /**
     * Filtrar por valor máximo
     */
    public function valorMaximo($value)
    {
        if (is_numeric($value) && $value >= 0) {
            $this->builder->where('valor', '<=', $value);
        }
    }

    /**
     * Filtrar por range de valor
     */
    public function valorRange($value)
    {
        if (is_array($value) && isset($value['min']) && isset($value['max'])) {
            $this->whereBetween('valor', $value['min'], $value['max']);
        }
    }

    /**
     * Filtrar ingressos gratuitos
     */
    public function gratuitos($value)
    {
        if ($value == '1' || $value === true) {
            $this->builder->where('valor', 0);
        }
    }

    /**
     * Filtrar ingressos pagos
     */
    public function pagos($value)
    {
        if ($value == '1' || $value === true) {
            $this->builder->where('valor', '>', 0);
        }
    }

    /**
     * Filtrar por quantidade disponível mínima
     */
    public function quantidadeMinima($value)
    {
        if (is_numeric($value) && $value >= 0) {
            $this->builder->where('quantidade_disponivel', '>=', $value);
        }
    }

    /**
     * Filtrar por quantidade disponível máxima
     */
    public function quantidadeMaxima($value)
    {
        if (is_numeric($value) && $value >= 0) {
            $this->builder->where('quantidade_disponivel', '<=', $value);
        }
    }

    /**
     * Filtrar ingressos disponíveis
     */
    public function disponiveis($value)
    {
        if ($value == '1' || $value === true) {
            $this->builder->where('quantidade_disponivel', '>', 0);
        }
    }

    /**
     * Filtrar ingressos esgotados
     */
    public function esgotados($value)
    {
        if ($value == '1' || $value === true) {
            $this->builder->where('quantidade_disponivel', '<=', 0);
        }
    }

    /**
    

   
     * Filtrar por quantidade vendida mínima
     */
    public function quantidadeVendidaMinima($value)
    {
        if (is_numeric($value) && $value >= 0) {
            $this->builder->has('inscricoes', '>=', $value);
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
     * Filtrar por descrição
     */
    public function descricao($value)
    {
        $this->whereLike('descricao', $value);
    }

    /**
     * Filtrar ingressos de eventos futuros
     */
    public function eventosFuturos($value)
    {
        if ($value == '1' || $value === true) {
            $this->builder->whereHas('evento', function($query) {
                $query->where('data_inicio', '>=', now());
            });
        }
    }

    /**
     * Filtrar ingressos de eventos ativos
     */
    public function eventosAtivos($value)
    {
        if ($value == '1' || $value === true) {
            $this->builder->whereHas('evento', function($query) {
                $query->where('ativo', true)
                      ->where('status', 'publicado');
            });
        }
    }

    /**
     * Filtrar por cidade do evento
     */
    public function cidade($value)
    {
        $this->builder->whereHas('evento.local', function($query) use ($value) {
            $query->where('cidade', 'like', "%{$value}%");
        });
    }

    /**
     * Filtrar por estado do evento
     */
    public function estado($value)
    {
        $this->builder->whereHas('evento.local', function($query) use ($value) {
            $query->where('estado', strtoupper($value));
        });
    }

    /**
     * Busca geral (descrição OU título do evento)
     */
    public function busca($value)
    {
        $this->builder->where(function($query) use ($value) {
            $query->where('descricao', 'like', "%{$value}%")
                  ->orWhereHas('evento', function($q) use ($value) {
                      $q->where('titulo', 'like', "%{$value}%");
                  });
        });
    }

    /**
     * Ordenar resultados
     */
    public function orderBy($value)
    {
        $allowedFields = [
            'tipo_ingresso',
            'valor',
            'quantidade_disponivel',
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
     * Ordenar por vendas (mais vendidos)
     */
    public function maisVendidos($value)
    {
        if ($value == '1' || $value === true) {
            $this->builder->withCount(['inscricoes' => function($query) {
                $query->where('status', 'confirmado');
            }])->orderBy('inscricoes_count', 'desc');
        }
    }

    /**
     * Filtrar por arrecadação mínima
     */
    public function arrecadacaoMinima($value)
    {
        if (is_numeric($value) && $value >= 0) {
            $this->builder->whereHas('inscricoes', function($query) use ($value) {
                $query->where('status', 'confirmado')
                      ->havingRaw('SUM(valor_pago) >= ?', [$value]);
            });
        }
    }
}
