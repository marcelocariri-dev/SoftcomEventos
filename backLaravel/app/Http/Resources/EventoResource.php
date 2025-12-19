<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


Class EventoResource extends JsonResource
{

//transformando a resposta em array

public function toArray($request)
{
    return [
        'id' => $this->id,
        'titulo' => $this->titulo,
        'descricao' => $this->descricao,
        'data_inicio' => $this->data_inicio->format('Y-m-d H:i:s'),
        'data_fim'=> $this->data_fim->format('Y-m-d H:i:s'),
        'capacidade_maxima' => $this->capacidade_maxima,
        'imagem' => $this->imagem,
        'status' => $this->status,
        'status_formatado' => $this->status_formatado,
        'ativo'=> $this->ativo,
//quantidades calculadas
        'quantidade_inscritos' => $this->quantidade_inscritos,
        'vagas_disponiveis' => $this->vagas_disponiveis,
        'esta_lotado' => $this->esta_lotado,
        'duracao_em_dias' => $this->duracao_em_dias,

        //relacionamentos

        'local' => new LocalResource($this->whenloaded('local')),
      'organizador' => [
            'id ' => $this->user_id,
            'nome' => optional($this->organizador)->name,
        ],
   // 'ingressos' => IngressosResource::collection($this->whenloaded('ingressos')),

    
    // PRo Timestamps
                'criado_em' => $this->created_at->format('Y-m-d H:i:s'),
                'atualizado_em' => $this->updated_at->format('Y-m-d H:i:s'),

    ];
}



}