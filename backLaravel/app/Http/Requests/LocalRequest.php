<?php
// app/Http/Requests/LocalRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LocalRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nome' => 'required|string|max:255',
            'endereco' => 'required|string|max:255',
            'numero' => 'required|string|max:20',
            'complemento' => 'nullable|string|max:100',
            'bairro' => 'required|string|max:100',
            'cidade' => 'required|string|max:100',
            'estado' => 'required|string|size:2',
            'cep' => 'required|string|min:8|max:9',
            'capacidade' => 'required|integer|min:1',
            'descricao' => 'nullable|string',
            'ativo' => 'boolean'
        ];
    }

    public function messages()
    {
        return [
            'nome.required' => 'O nome é obrigatório',
            'nome.max' => 'O nome deve ter no máximo 255 caracteres',
            'endereco.required' => 'O endereço é obrigatório',
            'numero.required' => 'O número é obrigatório',
            'bairro.required' => 'O bairro é obrigatório',
            'cidade.required' => 'A cidade é obrigatória',
            'estado.required' => 'O estado é obrigatório',
            'estado.size' => 'O estado deve ter 2 caracteres (UF)',
            'cep.required' => 'O CEP é obrigatório',
            'cep.min' => 'O CEP deve ter 8 dígitos',
            'capacidade.required' => 'A capacidade é obrigatória',
            'capacidade.min' => 'A capacidade deve ser no mínimo 1'
        ];
    }

    protected function prepareForValidation()
    {
        // Remove formatação do CEP
        if ($this->has('cep')) {
            $this->merge([
                'cep' => preg_replace('/[^0-9]/', '', $this->cep)
            ]);
        }

        // Converte estado para maiúsculas
        if ($this->has('estado')) {
            $this->merge([
                'estado' => strtoupper($this->estado)
            ]);
        }
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