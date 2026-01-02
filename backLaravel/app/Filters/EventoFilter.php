<?php
namespace App\Filters;
use Illuminate\Support\Facades\Log;
Class EventoFilter extends QueryFilter{
  
   public function titulo($value)
   {
       $this->builder->where('titulo', 'like', "%{$value}%");
   }

   /**
    * Filtrar por descrição
    
    */
   public function descricao($value)
   {
       $this->builder->where('descricao', 'like', "%{$value}%");
   }

   /**
    * Filtrar por local
    *
*/
   public function localId($value)
   {
       $this->builder->where('local_id', $value);
   }

   /**
    * Filtrar por cidade do local
    *
    * @param string $value
    * @return void
    */
   public function cidade($value)
   {
       $this->builder->whereHas('local', function($query) use ($value) {
           $query->where('cidade', 'like', "%{$value}%");
       });
   }
  
   public function data_inicio($value)
    { Log::info('🔥 Filtro data_inicio chamado!', ['value' => $value]);
        return $this->builder->whereDate('data_inicio', '>=', $value);
    }
    public function datainicio($value)
    { Log::info('🔥 Filtro data_inicio chamado!', ['value' => $value]);
        return $this->builder->whereDate('data_inicio', '>=', $value);
    }

   
    public function data_fim($value)
    {
        return $this->builder->whereDate('data_inicio', '<=', $value);
    }
    public function datafim($value)
    {
        return $this->builder->whereDate('data_inicio', '<=', $value);
    }
   /**
    * Filtrar por estado do local
    *
    * @param string $value
    * @return void
    */
   public function estado($value)
   {
       $this->builder->whereHas('local', function($query) use ($value) {
           $query->where('estado', $value);
       });
   }

   /**
    * Filtrar por organizador
    *
    * @param int $value
    * @return void
    */
   public function userId($value)
   {
       $this->builder->where('user_id', $value);
   }
   public function busca($value)
   {
       $this->builder->where(function($query) use ($value) {
           $query->where('titulo', 'like', "%{$value}%")
                 ->orWhere('descricao', 'like', "%{$value}%");
                 /** SQLSELECT * FROM Eventos
                 *                       WHERE (*titulo LIKE '%AcampamentoAguaViva%' *OR descriao LIKE '%%'*)
                  * 
                  */
       });
   }
}
