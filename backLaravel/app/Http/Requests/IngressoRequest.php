<?php
// app/Http/Requests/EventoRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;




class IngressoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'evento_id' => 'required|exists:eventos,id',
            'tipo_ingresso' => 'nullable|string',
            'valor' => 'required|numeric|min:0',
            'quantidade_disponivel' => 'required|integer|min:0',
            'descricao' => 'nullable|string',
            'ativo' => 'boolean'
        ];
    }

    public function messages()
    {
        return [
            'evento_id.required' => 'O evento é obrigatório',
            'evento_id.exists' => 'Evento inválido',
            'tipo_ingresso.required' => 'O tipo de ingresso é obrigatório',
            'tipo_ingresso.in' => 'Tipo de ingresso inválido',
            'valor.required' => 'O valor é obrigatório',
            'valor.min' => 'O valor não pode ser negativo',
            'quantidade_disponivel.required' => 'A quantidade disponível é obrigatória',
            'quantidade_disponivel.min' => 'A quantidade não pode ser negativa'
        ];
    }

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