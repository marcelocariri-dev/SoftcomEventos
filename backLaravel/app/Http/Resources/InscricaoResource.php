<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InscricaoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'codigo_inscricao' => $this->codigo_inscricao,
            
            // Status
            'status' => $this->status,
            'status_formatado' => $this->status_formatado,
            
            // Valores
            'valor_pago' => $this->valor_pago,
            'valor_formatado' => $this->valor_formatado,
            
            // Datas
            'data_inscricao' => $this->created_at->format('Y-m-d H:i:s'),
            'data_inscricao_formatada' => $this->created_at->format('d/m/Y H:i'),
            'atualizado_em' => $this->updated_at->format('Y-m-d H:i:s'),
            
          
            
          
            'evento' => $this->when($this->relationLoaded('evento') && $this->evento, function () {
                return [
                    'id' => $this->evento->id,
                    'titulo' => $this->evento->titulo,
                    'descricao' => $this->evento->descricao,
                    'data_inicio' => $this->evento->data_inicio->format('Y-m-d H:i:s'),
                    'data_inicio_formatada' => $this->evento->data_inicio->format('d/m/Y H:i'),
                    'data_fim' => $this->evento->data_fim->format('Y-m-d H:i:s'),
                    'data_fim_formatada' => $this->evento->data_fim->format('d/m/Y H:i'),
                    'status' => $this->evento->status,
                    'imagem' => $this->evento->imagem,
                   'imagem_url' => $this->imagem_url,
                    // Local do evento (se carregado)
                    'local' => $this->when(
                        $this->evento->relationLoaded('local') && $this->evento->local,
                        function () {
                            return [
                                'id' => $this->evento->local->id,
                                'nome' => $this->evento->local->nome,
                                'endereco_completo' => $this->evento->local->endereco_completo ?? null,
                                'cidade' => $this->evento->local->cidade,
                                'estado' => $this->evento->local->estado,
                            ];
                        }
                    ),
                ];
            }),
            
          
            'participante' => $this->when($this->relationLoaded('participante') && $this->participante, function () {
                return [
                    'id' => $this->participante->id,
                    'nome' => $this->participante->nome,
                    'email' => $this->participante->email,
                    //'cpf' => $this->participante->cpf,
                    //'cpf_formatado' => $this->participante->cpf_formatado ?? $this->formatarCpf($this->participante->cpf),
                    //'telefone' => $this->participante->telefone,
                    //'telefone_formatado' => $this->participante->telefone_formatado ?? $this->formatarTelefone($this->participante->telefone),
                ];
            }),
            
      
            'ingresso' => $this->when($this->relationLoaded('ingresso') && $this->ingresso, function () {
                return [
                    'id' => $this->ingresso->id,
                    'tipo_ingresso' => $this->ingresso->tipo_ingresso,
                  
                    'valor' => $this->ingresso->valor,
                    'valor_formatado' => 'R$ ' . number_format($this->ingresso->valor, 2, ',', '.'),
                    'descricao' => $this->ingresso->descricao,
                ];
            }),
        ];
    }

    
    /**
     * Get additional data that should be returned with the resource array.
     */
    public function with($request)
    {
        return [
            'meta' => [
                'version' => '1.0',
                'timestamp' => now()->toIso8601String(),
            ],
        ];
    }
}