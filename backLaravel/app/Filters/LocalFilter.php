<?php
namespace App\Filters;


class LocalFilter extends QueryFilter{


    public function nome($value)
    {
        $this->builder->where('nome', 'like', "%{$value}%");
    }


    public function cidade($value)
    {
        $this->builder->where('cidade', 'like', "%{$value}%");
    }

    public function estado($value)
    {
        $this->builder->where('estado', $value);
    }


    public function ativo($value)
    {
        $this->builder->where('ativo', (bool) $value);
    }


    public function comEventos($value)
    {
        if ($value == '1') {
            $this->builder->has('eventos');
        }
    }
    public function busca($value)
    {
        $this->builder->where(function($query) use ($value) {
            $query->where('nome', 'like', "%{$value}%")
                  ->orWhere('cidade', 'like', "%{$value}%")
                  ->orWhere('bairro', 'like', "%{$value}%")
                  ->orWhere('descricao', 'like', "%{$value}%");
        });
    }

}