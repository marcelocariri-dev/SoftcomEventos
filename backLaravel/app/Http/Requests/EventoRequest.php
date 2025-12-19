<?php
// app/Http/Requests/EventoRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class EventoRequest extends FormRequest
{
    /**
     * Autorização (sempre true para API)
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Regras de validação
     */
    public function rules()
    {
        $id = $this->route('id');

        return [
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'data_inicio' => 'required|date|after_or_equal:today',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'local_id' => 'required|exists:locais,id',
            'capacidade_maxima' => 'required|integer|min:1',
            'valor_padrao' => 'nullable|numeric|min:0',
            'imagem' => 'nullable|string|max:500',
            'status' => 'required|in:rascunho,publicado,cancelado,finalizado',
            'user_id' => 'nullable|exists:users,id',
            'ativo' => 'boolean'
        ];
    }

    /**
     * Mensagens personalizadas
     */
    public function messages()
    {
        return [
            'titulo.required' => 'O título é obrigatório',
            'titulo.max' => 'O título deve ter no máximo 255 caracteres',
            'descricao.required' => 'A descrição é obrigatória',
            'data_inicio.required' => 'A data de início é obrigatória',
            'data_inicio.after_or_equal' => 'A data de início deve ser hoje ou futura',
            'data_fim.required' => 'A data de término é obrigatória',
            'data_fim.after_or_equal' => 'A data de término deve ser posterior ou igual à data de início',
            'local_id.required' => 'O local é obrigatório',
            'local_id.exists' => 'Local inválido',
            'capacidade_maxima.required' => 'A capacidade máxima é obrigatória',
            'capacidade_maxima.min' => 'A capacidade deve ser no mínimo 1',
            'valor_padrao.min' => 'O valor não pode ser negativo',
            'status.required' => 'O status é obrigatório',
            'status.in' => 'Status inválido',
            'user_id.exists' => 'Usuário inválido'
        ];
    }

    /**
     * Tratamento de erro para API (retorna JSON)
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Erro de validação',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}