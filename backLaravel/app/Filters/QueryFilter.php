<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilter
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Builder
     */
    protected $builder;

    /**
     * QueryFilter constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Aplica todos os filtros disponíveis ao query builder
     *
     * @param Builder $builder
     * @return Builder
     */
    public function apply(Builder $builder)
    {
      $this->builder = $builder;

        foreach ($this->filters() as $name => $value) {
      
            $methodName = $this->convertToCamelCase($name);

            if (!method_exists($this, $methodName)) {
                continue;
            }

            // Só aplica o filtro se o valor não for vazio
            if (strlen($value)) {
                $this->$methodName($value);
            }
        }

        return $this->builder;
    }

    /**
     * Retorna todos os filtros do request
     *
     * @return array
     */
    public function filters()
    {
        return $this->request->all();
    }

    /**
     * Converte snake_case para camelCase
     * Exemplo: capacidade_minima -> capacidadeMinima
     *
     * @param string $string
     * @return string
     */
    protected function convertToCamelCase($string)
    {
        return lcfirst(str_replace('_', '', ucwords($string, '_')));
    }

    /**
     * Obtém valor do request
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    protected function get($key, $default = null)
    {
        return $this->request->get($key, $default);
    }

    /**
     * Verifica se o request tem um valor
     *
     * @param string $key
     * @return bool
     */
    protected function has($key)
    {
        return $this->request->has($key) && !empty($this->request->get($key));
    }
    protected function hasValue($valor)
    {
        if (is_bool($valor)) {
            return $valor;
        }

        if (is_numeric($valor)) {
            return (bool) $valor;
        }

        if (is_string($valor)) {
            $valor = strtolower(trim($valor));
            return in_array($valor, ['true', '1', 'yes', 'sim', 'on']);
        }

        return false;
    }
    //Aplica WHERE LIKE com wildcards
 
   protected function whereLike($column, $value)
   {
       $this->builder->where($column, 'like', "%{$value}%");
   }

   
      //Aplica WHERE em data
     
    protected function whereDate($column, $operator, $value)
    {
        $this->builder->whereDate($column, $operator, $value);
    }


    
    protected function whereBetween($column, $min, $max)
    {
        $this->builder->whereBetween($column, [$min, $max]);
    }

  
   protected function whereIn($column, array $values)
   {
       $this->builder->whereIn($column, $values);
   }

   
}