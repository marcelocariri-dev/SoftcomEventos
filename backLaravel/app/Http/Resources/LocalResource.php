<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

Class LocalResource extends JsonResource
{

//transformando a resposta em array

public function toArray($request)
{
    return [

      'id' => $this->id,
            'nome' => $this->nome,
            'endereco' => $this->endereco,
            'numero' => $this->numero,
            'complemento' => $this->complemento,
            'bairro' => $this->bairro,
            'cidade' => $this->cidade,
            'estado' => $this->estado,
            'cep' => $this->cep,
            'cep_formatado' => $this->cep_formatado,
            'capacidade' => $this->capacidade,
            'descricao' => $this->descricao,
            'ativo' => $this->ativo,
            
            // Dados calculados
            'endereco_completo' => $this->endereco_completo,
            'quantidade_eventos' => $this->quantidade_eventos,
            'quantidade_eventos_ativos' => $this->quantidade_eventos_ativos,
            'quantidade_eventos_futuros' => $this->quantidade_eventos_futuros,
            
            // Relacionamentos (quando necessário)
            'eventos' => EventoResource::collection($this->whenLoaded('eventos')),
      
  
                'criado_em' => $this->created_at->format('Y-m-d H:i:s'),
                'atualizado_em' => $this->updated_at->format('Y-m-d H:i:s'),

    ];
}



}