<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


Class IngressoResource extends JsonResource
{

//transformando a resposta em array

public function toArray($request)
{
    return [
        'id' => $this->id,
        'evento_id' => $this->evento_id,
        'tipo_ingresso' => $this->tipo_ingresso,
        'tipo_ingresso_formatado' => $this->tipo_ingresso_formatado,
        'valor' => $this->valor,
        'valor_formatado' => $this->valor_formatado,
        'quantidade_disponivel' => $this->quantidade_disponivel,
        'descricao' => $this->descricao,
        'ativo' => $this->ativo,

         
            // Dados calculados
            'quantidade_vendida' => $this->quantidade_vendida,
            'quantidade_restante' => $this->quantidade_restante,
            'percentual_vendido' => $this->percentual_vendido,
            'esta_esgotado' => $this->esta_esgotado,
            'esta_gratuito' => $this->esta_gratuito,
    
    'evento' => new EventoResource($this->whenLoaded('evento')),
    // PRo Timestamps
                'criado_em' => $this->created_at->format('Y-m-d H:i:s'),
                'atualizado_em' => $this->updated_at->format('Y-m-d H:i:s'),

    ];
}



}