<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


Class ParticipanteResource extends JsonResource
{

//transformando a resposta em array

public function toArray($request)
{
    return [
      
    'id' => $this->id,
            'nome' => $this->nome,
           // 'cpf' => $this->cpf,
            //'cpf_formatado' => $this->cpf_formatado,
            'email' => $this->email,
            //'telefone' => $this->telefone,
            //'telefone_formatado' => $this->telefone_formatado,
            //'data_nascimento' => $this->data_nascimento->format('Y-m-d'),
            //'idade' => $this->idade,
            'ativo' => $this->ativo,
            
            // Dados calculados
            'quantidade_inscricoes' => $this->quantidade_inscricoes,
            'quantidade_inscricoes_confirmadas' => $this->quantidade_inscricoes_confirmadas,
            
            // Relacionamentos
            'usuario' => [
                'id' => $this->user_id,
                'nome' => optional($this->user)->name,
            ],
            'inscricoes' => $this->whenLoaded('inscricoes'),
            
    // PRo Timestamps
                'criado_em' => $this->created_at->format('Y-m-d H:i:s'),
                'atualizado_em' => $this->updated_at->format('Y-m-d H:i:s'),

    ];
}



}